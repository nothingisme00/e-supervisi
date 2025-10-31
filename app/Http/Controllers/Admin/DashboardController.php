<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Supervisi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalGuru = User::where('role', 'guru')->count();
        $totalSupervisi = Supervisi::count();
        $supervisiSubmitted = Supervisi::where('status', 'submitted')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalGuru',
            'totalSupervisi',
            'supervisiSubmitted'
        ));
    }
}
