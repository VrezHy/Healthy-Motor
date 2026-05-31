<?php

namespace App\Http\Controllers;

use App\Models\Kerusakan;
use App\Models\Gejala;
use App\Models\Solusi;
use Illuminate\Http\Request;
use App\Models\RiwayatDiagnosa;
use Illuminate\Support\Facades\Schema;

class KerusakanController extends Controller
{
    public function index()
    {
        $kerusakans     = Kerusakan::orderBy('id')->get();
        $totalKerusakan = Kerusakan::count();
        $totalGejala    = Gejala::count();
        $totalSolusi    = Solusi::count();
        $totalMotor = Schema::hasTable('riwayat_diagnosas')
            ? RiwayatDiagnosa::count()
            : 0; // ganti nanti setelah model Motor dibuat

        return view('admin.kerusakan', compact(
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
            'nama_kerusakan' => 'required|string|max:255',
        ], [
            'nama_kerusakan.required' => 'Field Not Must Be Empty!',
        ]);

        Kerusakan::create([
            'nama_kerusakan' => $request->nama_kerusakan,
        ]);

        return redirect()->route('admin.kerusakan')
            ->with('success', 'Data kerusakan berhasil ditambahkan.');
    }

    public function update(Request $request, Kerusakan $kerusakan)
    {
        $request->validate([
            'nama_kerusakan' => 'required|string|max:255',
        ], [
            'nama_kerusakan.required' => 'Field Not Must Be Empty!',
        ]);

        $kerusakan->update([
            'nama_kerusakan' => $request->nama_kerusakan,
        ]);

        return redirect()->route('admin.kerusakan')
            ->with('success', 'Data kerusakan berhasil diubah.');
    }

    public function destroy(Kerusakan $kerusakan)
    {
        $kerusakan->delete();

        return redirect()->route('admin.kerusakan')
            ->with('success', 'Data kerusakan berhasil dihapus.');
    }
}
