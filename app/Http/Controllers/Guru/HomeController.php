<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Get supervisi list for current logged in guru
        $supervisiList = Supervisi::with(['dokumenEvaluasi', 'prosesPembelajaran', 'feedback'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.home', compact('supervisiList'));
    }
}