<?php

namespace App\Models;

use App\Models\Supervisi;
use Illuminate\Database\Eloquent\Model;

class EvaluasiRubrik extends Model
{
    protected $table = 'evaluasi_rubrik';

    protected $fillable = [
        'supervisi_id', 'reviewed_by', 'skor_total', 'skor_maksimal', 'nilai_akhir', 'predikat', 'masukan_umum', 'nama_pengawas',
    ];

    /**
     * @param  array<int,int>  $skorPerItemId  [rubrik_item_id => skor(0|1|2)]
     */
    public static function hitungDanSimpan(Supervisi $supervisi, int $reviewerId, array $skorPerItemId, ?string $masukanUmum): self
    {
        $skorTotal = array_sum($skorPerItemId);
        $skorMaksimal = RubrikItem::active()->count() * 2;
        $nilaiAkhir = round(($skorTotal / $skorMaksimal) * 100, 2);

        $evaluasi = self::updateOrCreate(
            ['supervisi_id' => $supervisi->id],
            [
                'reviewed_by' => $reviewerId,
                'skor_total' => $skorTotal,
                'skor_maksimal' => $skorMaksimal,
                'nilai_akhir' => $nilaiAkhir,
                'predikat' => self::hitungPredikat($nilaiAkhir),
                'masukan_umum' => $masukanUmum,
            ]
        );

        foreach ($skorPerItemId as $rubrikItemId => $skor) {
            $evaluasi->scores()->updateOrCreate(
                ['rubrik_item_id' => $rubrikItemId],
                ['skor' => $skor]
            );
        }

        return $evaluasi;
    }

    public function scores()
    {
        return $this->hasMany(EvaluasiRubrikScore::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public static function hitungPredikat(float $nilaiAkhir): string
    {
        return PredikatRubrik::where('batas_minimal', '<=', $nilaiAkhir)
            ->orderByDesc('batas_minimal')
            ->value('kode') ?? 'K';
    }
}
