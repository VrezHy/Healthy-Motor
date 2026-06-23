<?php

namespace App\Http\Controllers;

use App\Models\Solusi;
use App\Models\Kerusakan;
use App\Models\Gejala;
use Illuminate\Http\Request;
use App\Models\RiwayatDiagnosa;
use Illuminate\Support\Facades\Schema;

class SolusiController extends Controller
{
    public function index(Request $request)
    {
        $query = Solusi::with('kerusakan');
        if ($request->has('search') && !empty($request->search)) {
            $query->where('nama_solusi', 'LIKE', '%' . $request->search . '%');
        }

        $solusies       = $query->orderBy('id')->get();
        $kerusakans     = Kerusakan::orderBy('id')->get();
        $totalKerusakan = Kerusakan::count();
        $totalGejala    = Gejala::count();
        $totalSolusi    = Solusi::count();
        $totalMotor     = Schema::hasTable('riwayat_diagnosas') ? RiwayatDiagnosa::count() : 0;

        return view('admin.solusi', compact('solusies', 'kerusakans', 'totalMotor', 'totalKerusakan', 'totalGejala', 'totalSolusi'));
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

        if (Solusi::where('nama_solusi', $request->nama_solusi)->where('kerusakan_id', $request->kerusakan_id)->exists()) {
            return redirect()->back()
                ->with('error', 'Data solusi sudah ada.');
        }

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
