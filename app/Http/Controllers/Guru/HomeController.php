<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get all supervisi (timeline) with user relationship
        $supervisiList = Supervisi::with(['user', 'dokumenEvaluasi', 'prosesPembelajaran', 'feedback'])
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
}