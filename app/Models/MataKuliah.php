<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';
    
    // Cek apakah kolom kode_matakuliah ada sebagai primary key
    // Jika ya, gunakan kode_matakuliah, jika tidak gunakan id
    // Default menggunakan id untuk kompatibilitas
    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'kode_matakuliah',
        'nama_matakuliah',
        'sks',
        'semester',
    ];

    protected $casts = [
        'sks' => 'integer',
        'semester' => 'integer',
    ];
}

