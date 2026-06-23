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
    public function index(Request $request)
    {
        $query = Kerusakan::query();
        if ($request->has('search') && !empty($request->search)) {
            $query->where('nama_kerusakan', 'LIKE', '%' . $request->search . '%');
        }

        $kerusakans = $query->orderBy('id')->get();

        $totalKerusakan = Kerusakan::count();
        $totalGejala    = Gejala::count();
        $totalSolusi    = Solusi::count();
        $totalMotor     = Schema::hasTable('riwayat_diagnosas')
            ? RiwayatDiagnosa::count()
            : 0;

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
            'nama_kerusakan' => 'required|string|max:255|unique:kerusakans,nama_kerusakan',
        ], [
            'nama_kerusakan.required' => 'Field Not Must Be Empty!',
            'nama_kerusakan.unique'   => 'Data Kerusakan sudah ada!',
        ]);

        Kerusakan::create([
            'nama_kerusakan' => $request->nama_kerusakan,
        ]);

        return redirect()->route('admin.kerusakan')
            ->with('success', 'Data kerusakan berhasil ditambahkan.');
    }

    public function update(Request $request, Kerusakan $kerusakan)
    {
        try {
            $request->validate([
                'nama_kerusakan' => 'required|string|max:255|unique:kerusakans,nama_kerusakan,' . $kerusakan->id,
            ], [
                'nama_kerusakan.required' => 'Field Not Must Be Empty!',
                'nama_kerusakan.unique'   => 'Data Kerusakan sudah ada!',
            ]);

            $kerusakan->update([
                'nama_kerusakan' => $request->nama_kerusakan,
            ]);

            return redirect()->route('admin.kerusakan')
                ->with('success', 'Data kerusakan berhasil diubah.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('edit_id', $kerusakan->id);
        }
    }

    public function destroy(Kerusakan $kerusakan)
    {
        $kerusakan->delete();

        return redirect()->route('admin.kerusakan')
            ->with('success', 'Data kerusakan berhasil dihapus.');
    }
}
