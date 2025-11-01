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
            ->whereIn('status', ['draft', 'revision'])
            ->firstOrFail();

        $proses = ProsesPembelajaran::where('supervisi_id', $id)->first();

        // Define refleksi questions
        $refleksiQuestions = [
            'refleksi_1' => 'Apa tujuan pembelajaran yang ingin dicapai dalam pembelajaran ini?',
            'refleksi_2' => 'Bagaimana strategi atau metode pembelajaran yang digunakan?',
            'refleksi_3' => 'Apa saja tantangan yang dihadapi selama proses pembelajaran?',
            'refleksi_4' => 'Bagaimana respon dan partisipasi siswa selama pembelajaran?',
            'refleksi_5' => 'Apa rencana tindak lanjut untuk meningkatkan kualitas pembelajaran?'
        ];

        return view('guru.supervisi.proses', compact('supervisi', 'proses', 'refleksiQuestions'));
    }

    public function save(Request $request, $id)
    {
        $request->validate([
            'link_video' => 'required|url',
            'link_meeting' => 'required|url',
            'refleksi_1' => 'required|string|min:10|max:500',
            'refleksi_2' => 'required|string|min:10|max:500',
            'refleksi_3' => 'required|string|min:10|max:500',
            'refleksi_4' => 'required|string|min:10|max:500',
            'refleksi_5' => 'required|string|min:10|max:500'
        ]);

        Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['draft', 'revision'])
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
            ->whereIn('status', ['draft', 'revision'])
            ->firstOrFail();

        // Check if proses data exists and all mandatory fields are filled
        $proses = ProsesPembelajaran::where('supervisi_id', $id)->first();

        if (!$proses ||
            !$proses->link_video ||
            !$proses->link_meeting ||
            !$proses->refleksi_1 ||
            !$proses->refleksi_2 ||
            !$proses->refleksi_3 ||
            !$proses->refleksi_4 ||
            !$proses->refleksi_5) {
            return response()->json([
                'success' => false,
                'message' => 'Harap lengkapi semua data proses pembelajaran yang wajib diisi'
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