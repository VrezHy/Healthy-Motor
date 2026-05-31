<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\Solusi;
use Illuminate\Http\Request;
use App\Models\RiwayatDiagnosa;
use Illuminate\Support\Facades\Schema;

class GejalaController extends Controller
{
    public function index()
    {
        $gejalas        = Gejala::with('kerusakan')->orderBy('kode_gejala')->get();
        $kerusakans     = Kerusakan::orderBy('id')->get(); // untuk dropdown
        $totalKerusakan = Kerusakan::count();
        $totalGejala    = Gejala::count();
        $totalSolusi    = Solusi::count();
        $totalMotor = Schema::hasTable('riwayat_diagnosas')
            ? RiwayatDiagnosa::count()
            : 0;

        return view('admin.gejala', compact(
            'gejalas',
            'kerusakans',
            'totalMotor',
            'totalKerusakan',
            'totalGejala',
            'totalSolusi'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_gejala'  => 'required|string|max:10|unique:gejalas,kode_gejala',
            'nama_gejala'  => 'required|string|max:255',
            'kerusakan_id' => 'required|exists:kerusakans,id',
        ], [
            'kode_gejala.required'  => 'Kode gejala tidak boleh kosong!',
            'kode_gejala.unique'    => 'Kode gejala sudah digunakan!',
            'nama_gejala.required'  => 'Nama gejala tidak boleh kosong!',
            'kerusakan_id.required' => 'Pilih kerusakan terlebih dahulu!',
        ]);

        Gejala::create($request->only('kode_gejala', 'nama_gejala', 'kerusakan_id'));

        return redirect()->route('admin.gejala')
            ->with('success', 'Data gejala berhasil ditambahkan.');
    }

    public function update(Request $request, Gejala $gejala)
    {
        $request->validate([
            'kode_gejala'  => 'required|string|max:10|unique:gejalas,kode_gejala,' . $gejala->id,
            'nama_gejala'  => 'required|string|max:255',
            'kerusakan_id' => 'required|exists:kerusakans,id',
        ], [
            'kode_gejala.required'  => 'Kode gejala tidak boleh kosong!',
            'kode_gejala.unique'    => 'Kode gejala sudah digunakan!',
            'nama_gejala.required'  => 'Nama gejala tidak boleh kosong!',
            'kerusakan_id.required' => 'Pilih kerusakan terlebih dahulu!',
        ]);

        $gejala->update($request->only('kode_gejala', 'nama_gejala', 'kerusakan_id'));

        return redirect()->route('admin.gejala')
            ->with('success', 'Data gejala berhasil diubah.');
    }

    public function destroy(Gejala $gejala)
    {
        $gejala->delete();

        return redirect()->route('admin.gejala')
            ->with('success', 'Data gejala berhasil dihapus.');
    }
}
