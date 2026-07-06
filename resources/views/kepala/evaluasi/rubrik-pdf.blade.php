<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1a1a1a; }
        h1 { font-size: 15px; margin-bottom: 2px; }
        h2 { font-size: 13px; margin-top: 16px; margin-bottom: 6px; background: #f0fdfa; padding: 4px 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        td, th { border: 1px solid #ccc; padding: 4px 6px; text-align: left; vertical-align: top; }
        th { background: #f5f5f5; }
        .center { text-align: center; }
        .skor-col { width: 40px; }
        .summary { margin-top: 12px; }
        .ttd { margin-top: 40px; width: 100%; }
        .ttd td { border: none; text-align: center; padding-top: 40px; }
    </style>
</head>
<body>
    <h1>Instrumen Supervisi Pelaksanaan Pembelajaran</h1>
    <p>
        Nama Guru: <strong>{{ $supervisi->user->name }}</strong> &nbsp;&nbsp;
        Mata Pelajaran: <strong>{{ $supervisi->user->mata_pelajaran }}</strong> &nbsp;&nbsp;
        Tanggal: <strong>{{ optional($supervisi->tanggal_supervisi)->translatedFormat('d F Y') }}</strong>
    </p>

    @foreach ($rubrikItemsBySection as $sectionKey => $items)
        <h2>{{ $sectionKey }}. {{ $items->first()->section_label }}</h2>
        <table>
            <thead>
                <tr><th>Aspek</th><th class="skor-col center">Skor</th></tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->kelompok_nomor }}.{{ $loop->iteration }}. {{ $item->sub_label }}</td>
                        <td class="center">{{ $skorPerItem[$item->id] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="summary">
        <table>
            <tr><td>Skor Total</td><td>{{ $evaluasi->skor_total }} / {{ $evaluasi->skor_maksimal }}</td></tr>
            <tr><td>Nilai Akhir</td><td>{{ $evaluasi->nilai_akhir }}%</td></tr>
            <tr><td>Predikat</td><td>{{ \App\Models\PredikatRubrik::where('kode', $evaluasi->predikat)->value('label') ?? $evaluasi->predikat }}</td></tr>
        </table>
        <p><strong>Masukan terhadap Pelaksanaan Proses Pembelajaran secara umum:</strong><br>{{ $evaluasi->masukan_umum ?: '-' }}</p>
    </div>

    <table class="ttd">
        <tr>
            <td>Guru yang Disupervisi,<br><br><br><br>{{ $supervisi->user->name }}</td>
            <td>Supervisor,<br><br><br><br>{{ optional($evaluasi->reviewer)->name }}</td>
            <td>Pengawas Madrasah,<br><br><br><br>{{ $evaluasi->nama_pengawas ?: '.....................' }}</td>
        </tr>
    </table>
</body>
</html>
