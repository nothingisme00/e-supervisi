<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use App\Models\SupervisiFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SupervisiController extends Controller
{
    public function index()
    {
        $supervisis = Supervisi::with(['files', 'komentar'])
            ->where('guru_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('guru.supervisi.index', compact('supervisis'));
    }

    public function create()
    {
        return view('guru.supervisi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kelas' => 'required|string|max:50',
            'materi' => 'required|string|max:255',
            'kegiatan' => 'required|string',
            'refleksi' => 'required|string',
            'files.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:5120' // 5MB
        ]);

        DB::beginTransaction();
        try {
            // Create supervisi
            $supervisi = Supervisi::create([
                'guru_id' => auth()->id(),
                'tanggal' => $validated['tanggal'],
                'kelas' => $validated['kelas'],
                'materi' => $validated['materi'],
                'kegiatan' => $validated['kegiatan'],
                'refleksi' => $validated['refleksi'],
                'status' => 'pending'
            ]);

            // Handle file uploads
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('supervisi/' . $supervisi->id, $fileName, 'public');
                    
                    SupervisiFile::create([
                        'supervisi_id' => $supervisi->id,
                        'nama_file' => $file->getClientOriginalName(),
                        'path' => $path,
                        'tipe' => $file->getClientOriginalExtension(),
                        'ukuran' => $file->getSize()
                    ]);
                }
            }

            // Clear cache
            Cache::forget('supervisi.pending');
            Cache::forget('user.supervisi.' . auth()->id());

            DB::commit();
            return redirect()->route('guru.supervisi.index')
                ->with('success', 'Supervisi berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Supervisi $supervisi)
    {
        // Authorization check
        if ($supervisi->guru_id !== auth()->id()) {
            abort(403);
        }

        $supervisi->load(['files', 'komentar.user', 'evaluasi']);

        return view('guru.supervisi.show', compact('supervisi'));
    }

    public function edit(Supervisi $supervisi)
    {
        // Authorization check
        if ($supervisi->guru_id !== auth()->id()) {
            abort(403);
        }

        // Tidak bisa edit jika sudah disetujui
        if ($supervisi->status === 'approved') {
            return back()->with('error', 'Supervisi yang sudah disetujui tidak dapat diedit.');
        }

        return view('guru.supervisi.edit', compact('supervisi'));
    }

    public function update(Request $request, Supervisi $supervisi)
    {
        // Authorization check
        if ($supervisi->guru_id !== auth()->id()) {
            abort(403);
        }

        if ($supervisi->status === 'approved') {
            return back()->with('error', 'Supervisi yang sudah disetujui tidak dapat diedit.');
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'kelas' => 'required|string|max:50',
            'materi' => 'required|string|max:255',
            'kegiatan' => 'required|string',
            'refleksi' => 'required|string',
        ]);

        $supervisi->update($validated);

        return redirect()->route('guru.supervisi.show', $supervisi)
            ->with('success', 'Supervisi berhasil diperbarui!');
    }

    public function destroy(Supervisi $supervisi)
    {
        // Authorization check
        if ($supervisi->guru_id !== auth()->id()) {
            abort(403);
        }

        if ($supervisi->status === 'approved') {
            return back()->with('error', 'Supervisi yang sudah disetujui tidak dapat dihapus.');
        }

        // Delete files
        foreach ($supervisi->files as $file) {
            Storage::disk('public')->delete($file->path);
        }

        $supervisi->delete();

        return redirect()->route('guru.supervisi.index')
            ->with('success', 'Supervisi berhasil dihapus!');
    }
}