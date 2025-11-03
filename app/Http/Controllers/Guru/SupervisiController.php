<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Supervisi;
use App\Models\DokumenEvaluasi;
use App\Models\ProsesPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupervisiController extends Controller
{
    public function create()
    {
        // Check if user already has active supervision (only draft or revision, not submitted)
        $activeSupervisi = auth()->user()->supervisi()
            ->whereIn('status', ['draft', 'revision'])
            ->first();

        if ($activeSupervisi) {
            return redirect()->route('guru.supervisi.continue', $activeSupervisi->id);
        }

        // Show form to create new supervisi
        return view('guru.supervisi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_supervisi' => 'nullable|date',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Create new supervision
        $supervisi = Supervisi::create([
            'user_id' => auth()->id(),
            'tanggal_supervisi' => $request->tanggal_supervisi ?? now(),
            'catatan' => $request->catatan,
            'status' => 'draft'
        ]);

        return redirect()->route('guru.supervisi.evaluasi', $supervisi->id)
            ->with('success', 'Supervisi baru berhasil dibuat');
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
        try {
            $request->validate([
                'jenis_dokumen' => 'required|string',
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048'
            ], [
                'file.required' => 'File wajib dipilih',
                'file.mimes' => 'File harus berformat PDF, JPG, JPEG, atau PNG',
                'file.max' => 'Ukuran file maksimal 2MB'
            ]);

            $supervisi = Supervisi::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            // Check if document type already uploaded
            $existingDoc = DokumenEvaluasi::where('supervisi_id', $id)
                ->where('jenis_dokumen', $request->jenis_dokumen)
                ->first();

            if ($existingDoc) {
                // Delete old file
                if (Storage::disk('public')->exists($existingDoc->path_file)) {
                    Storage::disk('public')->delete($existingDoc->path_file);
                }
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteDocument(Request $request, $id)
    {
        try {
            $request->validate([
                'jenis_dokumen' => 'required|string'
            ]);

            // Verify ownership
            Supervisi::where('id', $id)
                ->where('user_id', auth()->id())
                ->firstOrFail();

            $document = DokumenEvaluasi::where('supervisi_id', $id)
                ->where('jenis_dokumen', $request->jenis_dokumen)
                ->firstOrFail();

            // Delete file from storage
            if (Storage::disk('public')->exists($document->path_file)) {
                Storage::disk('public')->delete($document->path_file);
            }

            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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
        $supervisi = Supervisi::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // If status is submitted, under_review, or completed, show detail page
        if (in_array($supervisi->status, ['submitted', 'under_review', 'completed'])) {
            // TODO: redirect to detail page when it's ready
            return redirect()->route('guru.supervisi.proses', $id);
        }

        // For draft or revision status, continue the flow
        // Check if proses data exists and is complete
        $proses = ProsesPembelajaran::where('supervisi_id', $id)->first();

        // If proses exists and has all required fields, go to proses page
        if ($proses &&
            $proses->link_video &&
            $proses->link_meeting &&
            $proses->refleksi_1 &&
            $proses->refleksi_2 &&
            $proses->refleksi_3 &&
            $proses->refleksi_4 &&
            $proses->refleksi_5) {
            return redirect()->route('guru.supervisi.proses', $id);
        } else {
            // Otherwise start from evaluasi
            return redirect()->route('guru.supervisi.evaluasi', $id);
        }
    }

    public function destroy($id)
    {
        try {
            $supervisi = Supervisi::where('id', $id)
                ->where('user_id', auth()->id())
                ->whereIn('status', ['draft', 'revision'])
                ->firstOrFail();

            // Delete all related documents from storage
            foreach ($supervisi->dokumenEvaluasi as $dokumen) {
                if (Storage::disk('public')->exists($dokumen->path_file)) {
                    Storage::disk('public')->delete($dokumen->path_file);
                }
            }

            // Delete supervisi (will cascade delete related records)
            $supervisi->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supervisi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus supervisi: ' . $e->getMessage()
            ], 500);
        }
    }
}