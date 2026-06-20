<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Tambahkan import ini

class RiwayatDiagnosa extends Model
{
    use HasFactory, SoftDeletes; // 2. Tambahkan SoftDeletes di sini

    protected $table = 'riwayat_diagnosas';

    // Tambahkan 'deleted_at' ke dalam fillable jika diperlukan, 
    // tapi opsional karena Laravel mengaturnya secara otomatis.
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
        'nama_pelanggan',
        'alamat_pelanggan',
        'nomor_polisi',
        'nomor_telepon',
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