<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Kerusakan;
use App\Models\Gejala;
use App\Models\Solusi;
use Illuminate\Http\Request;
use App\Models\RiwayatDiagnosa;
use Illuminate\Support\Facades\Schema;

class MotorController extends Controller
{
    public function index()
    {
        $motors = Schema::hasTable('riwayat_diagnosas')
            ? RiwayatDiagnosa::orderBy('id', 'desc')->get()
            : collect();
        $totalMotor = Schema::hasTable('riwayat_diagnosas')
            ? RiwayatDiagnosa::count()
            : 0;
        $totalKerusakan = Kerusakan::count();
        $totalGejala    = Gejala::count();
        $totalSolusi    = Solusi::count();

        return view('admin.motor', compact(
            'motors',
            'totalMotor',
            'totalKerusakan',
            'totalGejala',
            'totalSolusi'
        ));
    }
}
