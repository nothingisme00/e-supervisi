<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use App\Models\DokumenEvaluasi;
use App\Models\ProsesPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupervisiController extends Controller
{
    // Required documents count for supervisi evaluation
    private const REQUIRED_DOCUMENTS_COUNT = 7;

    public function create()
    {
        // Check if user already has active supervision (only draft or revision, not submitted)
        $activeSupervisi = auth()->user()->supervisi()
            ->whereIn('status', ['draft', 'revision'])
            ->first();

        if ($activeSupervisi) {
            return redirect()->route('guru.supervisi.continue', $activeSupervisi->id);
        }

        // Show form to create new supervisi
        return view('guru.supervisi.create');
    }

    public function store(Request $request)
    {
        // Create new supervision (tanggal will be set when submitted)
        $supervisi = Supervisi::create([
            'user_id' => auth()->id(),
            'tanggal_supervisi' => null, // Will be set when submitted
            'catatan' => null,
            'status' => 'draft'
        ]);

        return redirect()->route('guru.supervisi.evaluasi', $supervisi->id)
            ->with('success', 'Supervisi baru berhasil dibuat');
    }

    public function showEvaluasi($id)
    {
        // Allow editing for both draft and revision status
        $supervisi = Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['draft', 'revision'])
            ->firstOrFail();

        $uploadedDocuments = DokumenEvaluasi::where('supervisi_id', $id)
            ->pluck('jenis_dokumen')
            ->toArray();

        return view('guru.supervisi.evaluasi', compact('supervisi', 'uploadedDocuments'));
    }

    public function uploadDocument(Request $request, $id)
    {
        try {
            $request->validate([
                'jenis_dokumen' => 'required|string|alpha_dash|max:50',
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|mimetypes:application/pdf,image/jpeg,image/png|max:2048'
            ], [
                'file.required' => 'File wajib dipilih',
                'file.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG',
                'file.mimetypes' => 'Tipe MIME file tidak valid',
                'file.max' => 'Ukuran file maksimal 2MB',
                'jenis_dokumen.alpha_dash' => 'Jenis dokumen hanya boleh mengandung huruf, angka, dash dan underscore'
            ]);

            // Allow document upload for both draft and revision status
            $supervisi = Supervisi::where('id', $id)
                ->where('user_id', auth()->id())
                ->whereIn('status', ['draft', 'revision'])
                ->firstOrFail();

            // Check if document type already uploaded
            $existingDoc = DokumenEvaluasi::where('supervisi_id', $id)
                ->where('jenis_dokumen', $request->jenis_dokumen)
                ->first();

            if ($existingDoc) {
                // Delete old file
                if (Storage::disk('public')->exists($existingDoc->path_file)) {
                    Storage::disk('public')->delete($existingDoc->path_file);
                }
                $existingDoc->delete();
            }

            // Upload new file with sanitized filename
            $file = $request->file('file');
            $jenisDoc = preg_replace('/[^a-zA-Z0-9_-]/', '', $request->jenis_dokumen);
            $filename = time() . '_' . $jenisDoc . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('supervisi/' . $id, $filename, 'public');

            DokumenEvaluasi::create([
                'supervisi_id' => $id,
                'jenis_dokumen' => $request->jenis_dokumen,
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'tipe_file' => $file->getClientOriginalExtension(),
                'ukuran_file' => $file->getSize()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteDocument(Request $request, $id)
    {
        try {
            $request->validate([
                'jenis_dokumen' => 'required|string'
            ]);

            // Verify ownership and status
            Supervisi::where('id', $id)
                ->where('user_id', auth()->id())
                ->whereIn('status', ['draft', 'revision'])
                ->firstOrFail();

            $document = DokumenEvaluasi::where('supervisi_id', $id)
                ->where('jenis_dokumen', $request->jenis_dokumen)
                ->firstOrFail();

            // Delete file from storage
            if (Storage::disk('public')->exists($document->path_file)) {
                Storage::disk('public')->delete($document->path_file);
            }

            $document->delete();

            return redirect()->back()->with('success', 'Dokumen berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function checkDocuments($id)
    {
        $count = DokumenEvaluasi::where('supervisi_id', $id)->count();

        return response()->json([
            'complete' => $count >= self::REQUIRED_DOCUMENTS_COUNT,
            'count' => $count,
            'required' => self::REQUIRED_DOCUMENTS_COUNT
        ]);
    }

    public function continue($id)
    {
        $supervisi = Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // If status is submitted, under_review, or completed, show detail page
        if (in_array($supervisi->status, ['submitted', 'under_review', 'completed'])) {
            return redirect()->route('guru.supervisi.detail', $id);
        }

        // For draft or revision status, continue the flow
        // Check if proses data exists and is complete
        $proses = ProsesPembelajaran::where('supervisi_id', $id)->first();

        // If proses exists and has all required fields, go to proses page
        if ($proses &&
            $proses->link_video &&
            $proses->link_meeting &&
            $proses->refleksi_1 &&
            $proses->refleksi_2 &&
            $proses->refleksi_3 &&
            $proses->refleksi_4 &&
            $proses->refleksi_5) {
            return redirect()->route('guru.supervisi.proses', $id);
        } else {
            // Otherwise start from evaluasi
            return redirect()->route('guru.supervisi.evaluasi', $id);
        }
    }

    public function destroy($id)
    {
        try {
            $supervisi = Supervisi::where('id', $id)
                ->where('user_id', auth()->id())
                ->whereIn('status', ['draft', 'revision'])
                ->firstOrFail();

            // Delete all related documents from storage
            foreach ($supervisi->dokumenEvaluasi as $dokumen) {
                if (Storage::disk('public')->exists($dokumen->path_file)) {
                    Storage::disk('public')->delete($dokumen->path_file);
                }
            }

            // Delete supervisi (will cascade delete related records)
            $supervisi->delete();

            return redirect()->back()->with('success', 'Supervisi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus supervisi: ' . $e->getMessage());
        }
    }
}