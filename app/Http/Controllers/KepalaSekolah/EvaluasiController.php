<?php

namespace App\Http\Controllers\KepalaSekolah;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use App\Models\Feedback;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Supervisi::with(['user', 'dokumenEvaluasi', 'prosesPembelajaran', 'feedback'])
            ->whereIn('status', ['submitted', 'under_review', 'completed']);

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by guru name
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $supervisiList = $query->latest()->paginate(10);

        return view('kepala.evaluasi.index', compact('supervisiList'));
    }

    public function show($id)
    {
        $supervisi = Supervisi::with([
            'user',
            'dokumenEvaluasi',
            'prosesPembelajaran',
            'feedback.user'
        ])->findOrFail($id);

        // Don't auto mark - let kepala sekolah explicitly start review

        return view('kepala.evaluasi.show', compact('supervisi'));
    }

    public function startReview($id)
    {
        $supervisi = Supervisi::findOrFail($id);

        // Only allow starting review if status is submitted
        if ($supervisi->status !== 'submitted') {
            return redirect()->back()->with('error', 'Supervisi sudah dalam proses review');
        }

        $supervisi->update([
            'status' => 'under_review',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now()
        ]);

        return redirect()->route('kepala.evaluasi.show', $id)
            ->with('success', 'Review dimulai. Supervisi dipindahkan ke "Sedang Ditinjau"');
    }

    public function giveFeedback(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string|min:10',
        ]);

        $supervisi = Supervisi::findOrFail($id);

        // Create feedback without rating
        Feedback::create([
            'supervisi_id' => $id,
            'user_id' => auth()->id(),
            'komentar' => $request->komentar,
        ]);

        // Update supervisi status to under_review if submitted
        if ($supervisi->status == 'submitted') {
            $supervisi->update([
                'status' => 'under_review',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now()
            ]);
        }

        // Optionally mark as completed
        if ($request->has('mark_completed') && $request->mark_completed == '1') {
            $supervisi->update([
                'status' => 'completed'
            ]);
        }

        return redirect()->route('kepala.evaluasi.show', $id)
            ->with('success', 'Feedback berhasil diberikan');
    }

    public function complete($id)
    {
        $supervisi = Supervisi::findOrFail($id);
        
        $supervisi->update([
            'status' => 'completed'
        ]);

        return redirect()->route('kepala.evaluasi.index')
            ->with('success', 'Supervisi telah diselesaikan');
    }

    public function requestRevision(Request $request, $id)
    {
        $request->validate([
            'revision_notes' => 'required|string|min:10'
        ]);

        $supervisi = Supervisi::findOrFail($id);

        // Create feedback with revision request
        Feedback::create([
            'supervisi_id' => $id,
            'user_id' => auth()->id(),
            'komentar' => $request->revision_notes,
            'is_revision_request' => true
        ]);

        // Update status to revision
        $supervisi->update([
            'status' => 'revision',
            'revision_count' => $supervisi->revision_count + 1,
            'revision_notes' => $request->revision_notes
        ]);

        return redirect()->route('kepala.evaluasi.show', $id)
            ->with('success', 'Permintaan revisi berhasil dikirim');
    }

    public function downloadDocument($id)
    {
        $dokumen = \App\Models\DokumenEvaluasi::findOrFail($id);

        // Prefer the normalized column `path_file` but fall back to older names
        $relativePath = $dokumen->path_file ?? $dokumen->file_path ?? null;

        if (!$relativePath) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . ltrim($relativePath, '/'));

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan');
        }

        $downloadName = $dokumen->nama_file ?? basename($relativePath);

        return response()->download($filePath, $downloadName);
    }

    public function previewDocument($id)
    {
        $dokumen = \App\Models\DokumenEvaluasi::findOrFail($id);

        // Prefer the normalized column `path_file` but fall back to older names
        $relativePath = $dokumen->path_file ?? $dokumen->file_path ?? null;

        if (!$relativePath) {
            abort(404, 'File tidak ditemukan');
        }

        $filePath = storage_path('app/public/' . ltrim($relativePath, '/'));

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        // Get file info
        $fileName = $dokumen->nama_file ?? basename($relativePath);
        $mimeType = $dokumen->tipe_file ?? mime_content_type($filePath);
        
        // Return file for inline viewing
        return response()->file($filePath);
    }
}