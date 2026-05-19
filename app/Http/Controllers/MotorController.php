<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Kerusakan;
use App\Models\Gejala;
use App\Models\Solusi;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    public function index()
    {
        $motors         = Motor::with('kerusakan')->orderBy('id', 'desc')->get();
        $totalMotor     = Motor::count();
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
