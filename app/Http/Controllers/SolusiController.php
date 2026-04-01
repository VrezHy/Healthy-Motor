<?php

namespace App\Http\Controllers;

use App\Models\Solusi;
use App\Models\Kerusakan;
use App\Models\Gejala;
use Illuminate\Http\Request;

class SolusiController extends Controller
{
    public function index()
    {
        $solusies       = Solusi::with('kerusakan')->orderBy('id')->get();
        $kerusakans     = Kerusakan::orderBy('id')->get(); // untuk dropdown
        $totalKerusakan = Kerusakan::count();
        $totalGejala    = Gejala::count();
        $totalSolusi    = Solusi::count();
        $totalMotor     = 0;

        return view('admin.solusi', compact(
            'solusies',
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
            'nama_solusi'  => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'kerusakan_id' => 'required|exists:kerusakans,id',
        ], [
            'nama_solusi.required'  => 'Nama solusi tidak boleh kosong!',
            'kerusakan_id.required' => 'Pilih kerusakan terlebih dahulu!',
        ]);

        Solusi::create($request->only('nama_solusi', 'deskripsi', 'kerusakan_id'));

        return redirect()->route('admin.solusi')
            ->with('success', 'Data solusi berhasil ditambahkan.');
    }

    public function update(Request $request, Solusi $solusi)
    {
        $request->validate([
            'nama_solusi'  => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'kerusakan_id' => 'required|exists:kerusakans,id',
        ], [
            'nama_solusi.required'  => 'Nama solusi tidak boleh kosong!',
            'kerusakan_id.required' => 'Pilih kerusakan terlebih dahulu!',
        ]);

        $solusi->update($request->only('nama_solusi', 'deskripsi', 'kerusakan_id'));

        return redirect()->route('admin.solusi')
            ->with('success', 'Data solusi berhasil diubah.');
    }

    public function destroy(Solusi $solusi)
    {
        $solusi->delete();

        return redirect()->route('admin.solusi')
            ->with('success', 'Data solusi berhasil dihapus.');
    }
}
