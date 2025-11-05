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
        // Get all supervisi (timeline) with user relationship
        $supervisiList = Supervisi::with(['user', 'dokumenEvaluasi', 'prosesPembelajaran', 'feedback.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.home', compact('supervisiList'));
    }

    public function detail($id)
    {
        $supervisi = Supervisi::with(['dokumenEvaluasi', 'prosesPembelajaran', 'feedback'])
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('guru.supervisi.detail', compact('supervisi'));
    }

    public function viewOther($id)
    {
        // Get supervisi from other teacher (not own supervisi)
        $supervisi = Supervisi::with(['user', 'dokumenEvaluasi', 'prosesPembelajaran', 'feedback.user'])
            ->where('id', $id)
            ->where('user_id', '!=', auth()->id())
            ->firstOrFail();

        return view('guru.supervisi.view', compact('supervisi'));
    }

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string|max:1000',
        ]);

        // Verify this is not user's own supervisi
        $supervisi = Supervisi::where('id', $id)
            ->where('user_id', '!=', auth()->id())
            ->firstOrFail();

        Feedback::create([
            'supervisi_id' => $id,
            'user_id' => auth()->id(),
            'komentar' => $request->komentar,
            'is_revision_request' => false, // Comments from peers are not revision requests
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }
}
