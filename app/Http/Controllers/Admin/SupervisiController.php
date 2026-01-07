<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupervisiController extends Controller
{
    /**
     * Display a listing of supervisi that need review
     */
    public function index(Request $request)
    {
        $status = $request->get('status', Supervisi::STATUS_SUBMITTED);
        
        // Optimized eager loading - only load needed columns, use withCount for feedback
        $query = Supervisi::with([
                'user:id,name,nik,tingkat',
                'dokumenEvaluasi:id,supervisi_id,jenis_dokumen,nama_file',
                'prosesPembelajaran:id,supervisi_id,link_video,link_meeting'
            ])
            ->withCount('feedback')
            ->whereIn('status', [Supervisi::STATUS_SUBMITTED, Supervisi::STATUS_UNDER_REVIEW, Supervisi::STATUS_COMPLETED]);

        // Filter by status
        if ($status === Supervisi::STATUS_SUBMITTED) {
            $query->where('status', Supervisi::STATUS_SUBMITTED);
            $title = 'Menunggu Peninjauan';
        } elseif ($status === Supervisi::STATUS_UNDER_REVIEW) {
            $query->where('status', Supervisi::STATUS_UNDER_REVIEW);
            $title = 'Sedang Ditinjau';
        } elseif ($status === Supervisi::STATUS_COMPLETED) {
            $query->where('status', Supervisi::STATUS_COMPLETED);
            $title = 'Telah Ditinjau';
        } else {
            $title = 'Semua Supervisi';
        }

        $supervisiList = $query->orderBy('updated_at', 'desc')->paginate(12);

        return view('admin.supervisi.index', compact('supervisiList', 'status', 'title'));
    }

    /**
     * Show the detail of a specific supervisi for review
     */
    public function show($id)
    {
        $supervisi = Supervisi::with([
            'user:id,name,nik,email,tingkat,mata_pelajaran',
            'dokumenEvaluasi',
            'prosesPembelajaran',
            'feedback.user:id,name,role',
            'feedback.replies.user:id,name,role'
        ])->findOrFail($id);

        // Auto-mark as under_review if submitted
        if ($supervisi->status === Supervisi::STATUS_SUBMITTED) {
            $supervisi->update([
                'status' => Supervisi::STATUS_UNDER_REVIEW,
                'reviewer_id' => Auth::id()
            ]);
        }

        return view('admin.supervisi.detail', compact('supervisi'));
    }

    /**
     * Store feedback for supervisi
     */
    public function storeFeedback(Request $request, $id)
    {
        $request->validate([
            'feedback' => 'required|string|min:10',
            'mark_as_completed' => 'nullable|boolean'
        ]);

        $supervisi = Supervisi::findOrFail($id);

        // Create feedback
        Feedback::create([
            'supervisi_id' => $supervisi->id,
            'admin_id' => Auth::id(),
            'feedback' => $request->feedback
        ]);

        // Update status if mark as completed
        if ($request->mark_as_completed) {
            $supervisi->update([
                'status' => Supervisi::STATUS_COMPLETED,
                'reviewed_at' => now(),
                'reviewer_id' => Auth::id()
            ]);

            return redirect()->route('admin.supervisi.show', $supervisi->id)
                ->with('success', 'Feedback berhasil diberikan dan supervisi telah selesai ditinjau!');
        }

        return redirect()->route('admin.supervisi.show', $supervisi->id)
            ->with('success', 'Feedback berhasil diberikan!');
    }

    /**
     * Request revision for supervisi
     */
    public function requestRevision(Request $request, $id)
    {
        $request->validate([
            'revision_notes' => 'required|string|min:10'
        ]);

        $supervisi = Supervisi::findOrFail($id);

        $supervisi->update([
            'status' => 'revision',
            'revision_notes' => $request->revision_notes,
            'revision_count' => ($supervisi->revision_count ?? 0) + 1,
            'reviewer_id' => Auth::id()
        ]);

        // Create feedback about revision
        Feedback::create([
            'supervisi_id' => $supervisi->id,
            'admin_id' => Auth::id(),
            'feedback' => "📝 Revisi diperlukan:\n\n" . $request->revision_notes
        ]);

        return redirect()->route('admin.supervisi.show', $supervisi->id)
            ->with('success', 'Permintaan revisi berhasil dikirim ke guru!');
    }

    /**
     * Download document
     */
    public function downloadDocument($id)
    {
        $dokumen = \App\Models\DokumenEvaluasi::findOrFail($id);
        $supervisi = $dokumen->supervisi;

        // Admin can only download documents from submitted/under_review/completed supervisi
        if (!in_array($supervisi->status, ['submitted', 'under_review', 'completed'])) {
            abort(403, 'Anda tidak memiliki akses ke dokumen ini');
        }

        $filePath = storage_path('app/public/' . $dokumen->path_file);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return response()->download($filePath, $dokumen->nama_file);
    }
}
