<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Kerusakan;
use Illuminate\Http\Request;

class DiagnosaMekanikController extends Controller
{
    public function index()
    {
        $gejalas = Gejala::orderBy('kode_gejala')->get();

        return view('mekanik.diagnosa', compact('gejalas'));
    }

    public function proses(Request $request)
    {
        $request->validate([
            'jawaban' => 'nullable|array',
            'jawaban.*' => 'nullable|in:ya,tidak',
        ]);

        $gejalas = Gejala::orderBy('kode_gejala')->get();
        $jawaban = collect($request->input('jawaban', []));

        $selectedIds = $jawaban
            ->filter(fn ($value) => $value === 'ya')
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->values();

        $selectedGejalas = Gejala::with('kerusakan')
            ->whereIn('id', $selectedIds)
            ->orderBy('kode_gejala')
            ->get();

        $hasil = collect();

        if ($selectedIds->isNotEmpty()) {
            $hasil = Kerusakan::with(['gejalas', 'solusies'])
                ->whereHas('gejalas', fn ($query) => $query->whereIn('id', $selectedIds))
                ->get()
                ->map(function ($kerusakan) use ($selectedIds) {
                    $gejalaCocok = $kerusakan->gejalas->whereIn('id', $selectedIds)->values();
                    $totalGejala = max($kerusakan->gejalas->count(), 1);

                    return (object) [
                        'kerusakan' => $kerusakan,
                        'jumlahCocok' => $gejalaCocok->count(),
                        'totalGejala' => $totalGejala,
                        'persentase' => round(($gejalaCocok->count() / $totalGejala) * 100),
                    ];
                })
                ->sortByDesc(fn ($item) => ($item->persentase * 1000) + $item->jumlahCocok)
                ->values();
        }

        return view('mekanik.diagnosa', compact(
            'gejalas',
            'jawaban',
            'selectedGejalas',
            'hasil'
        ));
    }
}
