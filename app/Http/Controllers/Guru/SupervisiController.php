<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Supervisi;
use App\Models\SupervisiFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupervisiController extends Controller
{
    public function create()
    {
        return view('guru.supervisi.create');
    }

    public function store(Request $request)
    {
        // validasi 7 file wajib + mime + size
        $rules = [];
        $names = ['file1','file2','file3','file4','file5','file6','file7'];
        foreach($names as $n) {
            $rules[$n] = 'required|mimes:jpg,pdf|max:2048';
        }
        $rules['link_youtube'] = 'required|url';
        $rules['link_meet'] = 'nullable|url';
        // refleksi: 4 pertanyaan
        for($i=1;$i<=4;$i++) $rules["jawaban{$i}"] = 'required|string';

        $data = $request->validate($rules);

        // ambil guru_id
        $guru = Auth::user()->guru;
        if(!$guru) {
            // jika belum ada data guru, buat entri minimal
            $guru = \App\Models\Guru::create([
                'user_id' => Auth::id()
            ]);
        }

        $supervisi = Supervisi::create([
            'guru_id' => $guru->id,
            'judul' => $request->input('judul','Lembar Evaluasi'),
            'deskripsi' => $request->input('deskripsi'),
            'link_youtube' => $request->link_youtube,
            'link_meet' => $request->link_meet,
            'status' => 'pending',
        ]);

        // simpan file
        $berkasNames = [
            'CP','ATP','Kalender','Program Tahunan','Program Semester','Modul Ajar','Bahan Ajar'
        ];
        foreach($names as $idx => $nm) {
            $file = $request->file($nm);
            $path = $file->store("supervisi/guru_{$guru->id}", 'public');
            SupervisiFile::create([
                'supervisi_id' => $supervisi->id,
                'nama_berkas' => $berkasNames[$idx],
                'file_path' => $path
            ]);
        }

        // simpan refleksi
        for($i=1;$i<=4;$i++) {
            \App\Models\Refleksi::create([
                'supervisi_id' => $supervisi->id,
                'pertanyaan' => "Pertanyaan {$i}",
                'jawaban' => $request->input("jawaban{$i}")
            ]);
        }

        return redirect()->route('guru.dashboard')->with('success','Supervisi berhasil dikirim.');
    }
}
