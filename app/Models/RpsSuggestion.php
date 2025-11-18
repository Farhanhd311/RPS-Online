<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RpsSuggestion extends Model
{
    use HasFactory;

    protected $table = 'rps_suggestion';
    protected $primaryKey = 'suggestion_id';
    public $timestamps = false;

    protected $fillable = [
        'rps_id',
        'mahasiswa_id',
        'saran',
        'status',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class, 'rps_id', 'rps_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'user_id');
    }
}
