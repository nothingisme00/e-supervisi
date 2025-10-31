<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Supervisi::with(['user', 'feedback'])
            ->where('status', 'submitted')
            ->orWhere('status', 'reviewed')
            ->orWhere('status', 'completed');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mata_pelajaran', 'like', "%{$search}%");
            });
        }

        // Filter by tingkat
        if ($request->has('tingkat') && $request->tingkat != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('tingkat', $request->tingkat);
            });
        }

        $supervisiList = $query->latest()->paginate(12);
        
        // Check if current user has active supervision
        $activeSupervisi = auth()->user()->supervisi()
            ->whereIn('status', ['draft', 'submitted'])
            ->first();

        return view('guru.home', compact('supervisiList', 'activeSupervisi'));
    }
}