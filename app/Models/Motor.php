<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;

    protected $table = 'motors';

    protected $fillable = [
        'nama_pemilik',
        'merk_motor',
        'plat_nomor',
        'keluhan',
        'kerusakan_id',
        'status',
        'catatan_mekanik',
    ];

    public function kerusakan()
    {
        return $this->belongsTo(Kerusakan::class, 'kerusakan_id');
    }
}
