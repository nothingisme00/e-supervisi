<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use App\Models\ProsesPembelajaran;
use App\Models\DokumenEvaluasi;
use Illuminate\Http\Request;

class ProsesController extends Controller
{
    public function show($id)
    {
        $supervisi = Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'draft')
            ->firstOrFail();

        // Check if all documents uploaded
        $documentCount = DokumenEvaluasi::where('supervisi_id', $id)->count();
        
        if ($documentCount < 7) {
            return redirect()->route('guru.supervisi.evaluasi', $id)
                ->with('error', 'Harap lengkapi semua dokumen terlebih dahulu');
        }

        $proses = ProsesPembelajaran::where('supervisi_id', $id)->first();

        return view('guru.supervisi.proses', compact('supervisi', 'proses'));
    }

    public function save(Request $request, $id)
    {
        $request->validate([
            'link_video' => 'required|url',
            'link_meeting' => 'nullable|url',
            'refleksi_1' => 'required|string|min:10',
            'refleksi_2' => 'required|string|min:10',
            'refleksi_3' => 'required|string|min:10',
            'refleksi_4' => 'required|string|min:10',
            'refleksi_5' => 'required|string|min:10'
        ]);

        Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'draft')
            ->firstOrFail();

        ProsesPembelajaran::updateOrCreate(
            ['supervisi_id' => $id],
            $request->only([
                'link_video', 
                'link_meeting',
                'refleksi_1',
                'refleksi_2',
                'refleksi_3',
                'refleksi_4',
                'refleksi_5'
            ])
        );

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }

    public function submit($id)
    {
        $supervisi = Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->where('status', 'draft')
            ->firstOrFail();

        // Check if proses data exists
        $proses = ProsesPembelajaran::where('supervisi_id', $id)->first();
        
        if (!$proses) {
            return response()->json([
                'success' => false,
                'message' => 'Harap lengkapi semua data proses pembelajaran'
            ], 400);
        }

        // Update status to submitted
        $supervisi->update([
            'status' => 'submitted'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supervisi berhasil disubmit!'
        ]);
    }
}