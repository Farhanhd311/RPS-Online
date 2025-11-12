<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rps extends Model
{
    use HasFactory;

    protected $table = 'rps';
    protected $primaryKey = 'rps_id';

    protected $fillable = [
        'dosen_id',
        'template_id',
        'kode_matakuliah',
        'nama_matakuliah',
        'sks',
        'semester',
        'bahan_kajian',
        'tanggal_penyusunan',
        'dosen_pengembang',
        'dosen_pengembang_id',
        'koordinasi_bk',
        'kaprodi',
        'cpl_prodi',
        'indikator',
        'cpmk',
        'korelasi',
        'asesmen',
        'deskripsi_mk',
        'materi_pembelajaran',
        'pustaka_utama',
        'pustaka_pendukung',
        'perangkat_lunak',
        'perangkat_keras',
        'dosen_pengampu',
        'mk_prasyarat',
        'template_content',
        'pdf_path',
        'status',
        'approved_by',
        'submitted_at',
        'approved_at',
    ];

    protected $casts = [
        'cpl_prodi' => 'array',
        'indikator' => 'array',
        'cpmk' => 'array',
        'korelasi' => 'array',
        'asesmen' => 'array',
        'materi_pembelajaran' => 'array',
        'pustaka_utama' => 'array',
        'pustaka_pendukung' => 'array',
        'perangkat_lunak' => 'array',
        'perangkat_keras' => 'array',
        'dosen_pengampu' => 'array',
        'mk_prasyarat' => 'array',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id', 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'user_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'kode_matakuliah', 'kode_matakuliah');
    }

    public function aktivitasPembelajaran()
    {
        return $this->hasMany(RpsAktivitasPembelajaran::class, 'rps_id', 'rps_id')->orderBy('urutan');
    }
}
