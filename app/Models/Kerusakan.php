<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kerusakan extends Model
{
    use HasFactory;

    protected $table = 'kerusakans';
    protected $fillable = ['nama_kerusakan'];

    public function gejalas()
    {
        return $this->hasMany(Gejala::class, 'kerusakan_id');
    }

    public function solusies()
    {
        return $this->hasMany(Solusi::class, 'kerusakan_id');
    }
}
