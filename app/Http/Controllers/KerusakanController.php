<?php

namespace App\Http\Controllers;

use App\Models\Kerusakan;
use Illuminate\Http\Request;

class KerusakanController extends Controller
{
    public function index()
    {
        $kerusakans     = Kerusakan::orderBy('id')->get();
        $totalKerusakan = Kerusakan::count();

        // Ganti dengan model yang sudah kamu buat untuk halaman lain.
        // Jika model belum ada, sementara pakai nilai 0.
        // $totalMotor  = class_exists(\App\Models\Motor::class)  ? \App\Models\Motor::count()  : 0;
        // $totalGejala = class_exists(\App\Models\Gejala::class) ? \App\Models\Gejala::count() : 0;
        // $totalSolusi = class_exists(\App\Models\Solusi::class) ? \App\Models\Solusi::count() : 0;

        return view('admin.kerusakan', compact(
            'kerusakans',
            // 'totalMotor',
            'totalKerusakan',
            // 'totalGejala',
            // 'totalSolusi'
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
