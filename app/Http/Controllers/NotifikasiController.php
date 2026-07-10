<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Auth::user()->notifications()->paginate(20);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function buka(string $id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        $url = $notif->data['url'] ?? route('notifikasi.index');

        return redirect($url);
    }

    public function bacaSemua()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back();
    }
}
