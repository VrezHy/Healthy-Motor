<?php

namespace App\Http\Controllers;

use App\Models\RiwayatDiagnosa;

class AdminController extends Controller
{
   public function index()
{
    $dataMotor = RiwayatDiagnosa::where('status', 'Done')->get();

    return view('admin.dashboard_admin', compact('dataMotor'));
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

