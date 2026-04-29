<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatDiagnosa extends Model
{
    use HasFactory;

    protected $table = 'riwayat_diagnosas';

    protected $fillable = [
        'user_id',
        'kerusakan_id',
        'nama_kerusakan',
        'persentase',
        'jumlah_gejala_cocok',
        'total_gejala',
        'gejala_terpilih',
        'solusi',
        'status',
    ];

    protected $casts = [
        'gejala_terpilih' => 'array',
        'solusi' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kerusakan()
    {
        return $this->belongsTo(Kerusakan::class);
    }
}
