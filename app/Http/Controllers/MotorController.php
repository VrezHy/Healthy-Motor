<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Kerusakan;
use App\Models\Gejala;
use App\Models\Solusi;
use Illuminate\Http\Request;
use App\Models\RiwayatDiagnosa;

class MotorController extends Controller
{
    public function index()
    {
        $motors = RiwayatDiagnosa::orderBy('id', 'desc')->get();
        $totalMotor     = RiwayatDiagnosa::count();
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
