<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use App\Models\DokumenEvaluasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupervisiController extends Controller
{
    public function create()
    {
        // Check if user already has active supervision
        $activeSupervisi = auth()->user()->supervisi()
            ->whereIn('status', ['draft', 'submitted'])
            ->first();

        if ($activeSupervisi) {
            return redirect()->route('guru.supervisi.continue', $activeSupervisi->id);
        }

        // Create new supervision
        $supervisi = Supervisi::create([
            'user_id' => auth()->id(),
            'tanggal_supervisi' => now(),
            'status' => 'draft'
        ]);

        return redirect()->route('guru.supervisi.evaluasi', $supervisi->id);
    }

    public function showEvaluasi($id)
    {
        $supervisi = Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $uploadedDocuments = DokumenEvaluasi::where('supervisi_id', $id)
            ->pluck('jenis_dokumen')
            ->toArray();

        return view('guru.supervisi.evaluasi', compact('supervisi', 'uploadedDocuments'));
    }

    public function uploadDocument(Request $request, $id)
    {
        $request->validate([
            'jenis_dokumen' => 'required|string',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Check if document type already uploaded
        $existingDoc = DokumenEvaluasi::where('supervisi_id', $id)
            ->where('jenis_dokumen', $request->jenis_dokumen)
            ->first();

        if ($existingDoc) {
            // Delete old file
            Storage::delete($existingDoc->path_file);
            $existingDoc->delete();
        }

        // Upload new file
        $file = $request->file('file');
        $filename = time() . '_' . $request->jenis_dokumen . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('supervisi/' . $id, $filename, 'public');

        DokumenEvaluasi::create([
            'supervisi_id' => $id,
            'jenis_dokumen' => $request->jenis_dokumen,
            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'tipe_file' => $file->getClientOriginalExtension(),
            'ukuran_file' => $file->getSize()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil diupload'
        ]);
    }

    public function deleteDocument(Request $request, $id)
    {
        $request->validate([
            'jenis_dokumen' => 'required|string'
        ]);

        $document = DokumenEvaluasi::where('supervisi_id', $id)
            ->where('jenis_dokumen', $request->jenis_dokumen)
            ->firstOrFail();

        Storage::delete($document->path_file);
        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen berhasil dihapus'
        ]);
    }

    public function checkDocuments($id)
    {
        $count = DokumenEvaluasi::where('supervisi_id', $id)->count();
        
        return response()->json([
            'complete' => $count >= 7,
            'count' => $count,
            'required' => 7
        ]);
    }

    public function continue($id)
    {
        Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Check document count
        $documentCount = DokumenEvaluasi::where('supervisi_id', $id)->count();

        if ($documentCount < 7) {
            return redirect()->route('guru.supervisi.evaluasi', $id);
        } else {
            return redirect()->route('guru.supervisi.proses', $id);
        }
    }
}