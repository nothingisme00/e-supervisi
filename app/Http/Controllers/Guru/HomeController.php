<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use App\Models\Feedback;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get all supervisi (timeline) excluding drafts - only show submitted/reviewed supervisi
        $supervisiList = Supervisi::with(['user', 'dokumenEvaluasi', 'prosesPembelajaran', 'feedback.user', 'feedback.replies.user'])
            ->whereNotIn('status', ['draft'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.home', compact('supervisiList'));
    }

    public function mySupervisi()
    {
        // Get only current user's draft supervisi
        $mySupervisi = Supervisi::with(['dokumenEvaluasi', 'prosesPembelajaran'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.my-supervisi', compact('mySupervisi'));
    }

    public function detail($id)
    {
        $supervisi = Supervisi::with(['dokumenEvaluasi', 'prosesPembelajaran', 'feedback.user', 'feedback.replies.user'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('guru.supervisi.detail', compact('supervisi'));
    }

    public function viewOther($id)
    {
        // Get supervisi from other teacher (not own supervisi)
        $supervisi = Supervisi::with(['user', 'dokumenEvaluasi', 'prosesPembelajaran', 'feedback.user', 'feedback.replies.user'])
            ->where('id', $id)
            ->where('user_id', '!=', auth()->id())
            ->firstOrFail();

        return view('guru.supervisi.view', compact('supervisi'));
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:feedback,id',
        ]);

        // Verify supervisi exists
        $supervisi = Supervisi::findOrFail($id);

        // Allow comments from supervisi owner or others
        // But only if supervisi is not in draft status
        if ($supervisi->status === 'draft') {
            return redirect()->back()->with('error', 'Tidak dapat menambahkan komentar pada supervisi yang masih draft.');
        }

        Feedback::create([
            'supervisi_id' => $id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id,
            'komentar' => $request->komentar,
            'is_revision_request' => false, // Comments from peers/owners are not revision requests
        ]);

        $message = $request->parent_id ? 'Balasan berhasil ditambahkan!' : 'Komentar berhasil ditambahkan!';
        return redirect()->back()->with('success', $message);
    }
}
