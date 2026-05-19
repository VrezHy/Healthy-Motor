<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\Kerusakan;
use Illuminate\Http\Request;

class MekanikController extends Controller
{
    public function index()
    {
        $motors     = Motor::with('kerusakan')->orderBy('id', 'desc')->get();
        $kerusakans = Kerusakan::orderBy('id')->get();
        $totalMotor = Motor::count();

        return view('mekanik.riwayat', compact('motors', 'kerusakans', 'totalMotor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemilik'    => 'required|string|max:255',
            'merk_motor'      => 'required|string|max:255',
            'plat_nomor'      => 'required|string|max:20',
            'keluhan'         => 'required|string',
            'kerusakan_id'    => 'nullable|exists:kerusakans,id',
            'status'          => 'required|in:pending,proses,selesai',
            'catatan_mekanik' => 'nullable|string',
        ], [
            'nama_pemilik.required' => 'Nama pemilik tidak boleh kosong!',
            'merk_motor.required'   => 'Merk motor tidak boleh kosong!',
            'plat_nomor.required'   => 'Plat nomor tidak boleh kosong!',
            'keluhan.required'      => 'Keluhan tidak boleh kosong!',
        ]);

        Motor::create($request->only(
            'nama_pemilik', 'merk_motor', 'plat_nomor',
            'keluhan', 'kerusakan_id', 'status', 'catatan_mekanik'
        ));

        return redirect()->route('mekanik.riwayat')
            ->with('success', 'Data motor berhasil ditambahkan.');
    }

    public function update(Request $request, Motor $motor)
    {
        $request->validate([
            'nama_pemilik'    => 'required|string|max:255',
            'merk_motor'      => 'required|string|max:255',
            'plat_nomor'      => 'required|string|max:20',
            'keluhan'         => 'required|string',
            'kerusakan_id'    => 'nullable|exists:kerusakans,id',
            'status'          => 'required|in:pending,proses,selesai',
            'catatan_mekanik' => 'nullable|string',
        ], [
            'nama_pemilik.required' => 'Nama pemilik tidak boleh kosong!',
            'merk_motor.required'   => 'Merk motor tidak boleh kosong!',
            'plat_nomor.required'   => 'Plat nomor tidak boleh kosong!',
            'keluhan.required'      => 'Keluhan tidak boleh kosong!',
        ]);

        $motor->update($request->only(
            'nama_pemilik', 'merk_motor', 'plat_nomor',
            'keluhan', 'kerusakan_id', 'status', 'catatan_mekanik'
        ));

        return redirect()->route('mekanik.riwayat')
            ->with('success', 'Data motor berhasil diubah.');
    }

    public function destroy(Motor $motor)
    {
        $motor->delete();

        return redirect()->route('mekanik.riwayat')
            ->with('success', 'Data motor berhasil dihapus.');
    }
}
