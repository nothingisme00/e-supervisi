<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use App\Models\ProsesPembelajaran;
use App\Models\DokumenEvaluasi;
use Illuminate\Http\Request;

class ProsesController extends Controller
{
    // Required documents for supervisi evaluation
    private const REQUIRED_DOCUMENTS = [
        'capaian_pembelajaran',
        'alur_tujuan_pembelajaran',
        'kalender',
        'program_tahunan',
        'program_semester',
        'modul_ajar',
        'bahan_ajar'
    ];

    public function show($id)
    {
        // Allow access to proses page for both draft and revision status
        $supervisi = Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['draft', 'revision'])
            ->firstOrFail();

        // Check if all required documents are uploaded
        $requiredDocuments = self::REQUIRED_DOCUMENTS;
        $requiredCount = count($requiredDocuments);

        $uploadedDocuments = DokumenEvaluasi::where('supervisi_id', $id)
            ->pluck('jenis_dokumen')
            ->toArray();

        $uploadedCount = count(array_intersect($requiredDocuments, $uploadedDocuments));

        if ($uploadedCount < $requiredCount) {
            return redirect()->route('guru.supervisi.evaluasi', $id)
                ->with('error', 'Anda harus mengupload semua ' . $requiredCount . ' dokumen terlebih dahulu sebelum dapat melanjutkan ke tab Proses. Dokumen yang sudah diupload: ' . $uploadedCount . '/' . $requiredCount);
        }

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
        // Validation for partial save - only link_video required, others optional
        $request->validate([
            'link_video' => 'nullable|url',
            'link_meeting' => 'nullable|url',
            'refleksi_1' => 'nullable|string|max:500',
            'refleksi_2' => 'nullable|string|max:500',
            'refleksi_3' => 'nullable|string|max:500',
            'refleksi_4' => 'nullable|string|max:500',
            'refleksi_5' => 'nullable|string|max:500'
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

        // Update status to submitted and set tanggal_supervisi
        $supervisi->update([
            'status' => 'submitted',
            'tanggal_supervisi' => now() // Set tanggal saat submit
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supervisi berhasil disubmit!'
        ]);
    }
}