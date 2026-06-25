<?php

namespace App\Http\Controllers;

use App\Models\RiwayatDiagnosa;
use App\Models\Kerusakan;
use App\Models\Gejala;
use App\Models\Solusi;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
   public function index()
   {
       $dataMotor = RiwayatDiagnosa::where('status', 'Done')->get();
       $totalKerusakan = Kerusakan::count();
       $totalGejala    = Gejala::count();
       $totalSolusi    = Solusi::count();
       $totalMotor     = Schema::hasTable('riwayat_diagnosas') ? RiwayatDiagnosa::count() : 0;

       return view('admin.dashboard_admin', compact('dataMotor', 'totalMotor', 'totalKerusakan', 'totalGejala', 'totalSolusi'));
   }

    public function motor()
    {
        $motors = RiwayatDiagnosa::where('status', 'Done')
            ->latest()
            ->get();

        return view('admin.motor', compact('motors'));
    }
}

// use Illuminate\Http\Request;
// use App\Models\Motor; // WAJIB DIAKTIFKAN: Mengubungkan controller dengan model Motor

// class AdminController extends Controller
// {
//     public function index()
//     {
//         // MENGGUNAKAN DATA ASLI: Mengambil semua data dari tabel motors di database
//         // latest() digunakan agar data motor yang baru masuk muncul di paling atas
//         $dataMotor = Motor::latest()->get();

//         // Mengirim data asli ke view 'admin.dashboard_admin'
//         return view('admin.dashboard_admin', compact('dataMotor'));
//     }
// }

