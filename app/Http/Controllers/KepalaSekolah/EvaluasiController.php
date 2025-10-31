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
        $query = Supervisi::with(['user', 'dokumenEvaluasi', 'prosesPembelajaran'])
            ->whereIn('status', ['submitted', 'reviewed', 'completed']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

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

        return view('kepala.evaluasi.show', compact('supervisi'));
    }

    public function giveFeedback(Request $request, $id)
    {
        $request->validate([
            'komentar' => 'required|string|min:10',
            'rating' => 'nullable|integer|min:1|max:5'
        ]);

        $supervisi = Supervisi::findOrFail($id);

        // Create feedback
        Feedback::create([
            'supervisi_id' => $id,
            'user_id' => auth()->id(),
            'komentar' => $request->komentar,
            'rating' => $request->rating
        ]);

        // Update supervisi status
        if ($supervisi->status == 'submitted') {
            $supervisi->update([
                'status' => 'reviewed',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now()
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
}