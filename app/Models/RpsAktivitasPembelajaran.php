<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RpsAktivitasPembelajaran extends Model
{
    use HasFactory;

    protected $table = 'rps_aktivitas_pembelajaran';
    protected $primaryKey = 'aktivitas_id';

    protected $fillable = [
        'rps_id',
        'minggu_ke',
        'cpmk_kode',
        'indikator_penilaian',
        'bentuk_penilaian_jenis',
        'bentuk_penilaian_bobot',
        'aktivitas_sinkron_luring',
        'aktivitas_sinkron_daring',
        'aktivitas_asinkron_mandiri',
        'aktivitas_asinkron_kolaboratif',
        'media',
        'materi_pembelajaran',
        'referensi',
        'urutan',
    ];

    protected $casts = [
        'bentuk_penilaian_bobot' => 'decimal:2',
        'urutan' => 'integer',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class, 'rps_id', 'rps_id');
    }
}
