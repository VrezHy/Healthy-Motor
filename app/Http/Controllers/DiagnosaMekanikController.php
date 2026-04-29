<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\RiwayatDiagnosa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $hasil = $this->hitungHasil($selectedIds);

        return view('mekanik.diagnosa', compact(
            'gejalas',
            'jawaban',
            'selectedGejalas',
            'hasil'
        ));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'gejala_ids' => 'required|array|min:1',
            'gejala_ids.*' => 'integer|exists:gejalas,id',
        ]);

        $selectedIds = collect($request->input('gejala_ids'))
            ->map(fn ($id) => (int) $id)
            ->values();

        $hasilUtama = $this->hitungHasil($selectedIds)->first();

        if (!$hasilUtama) {
            return redirect()->route('mekanik.diagnosa')
                ->with('error', 'Hasil diagnosa belum dapat disimpan.');
        }

        $selectedGejalas = Gejala::whereIn('id', $selectedIds)
            ->orderBy('kode_gejala')
            ->get()
            ->map(fn ($gejala) => [
                'kode_gejala' => $gejala->kode_gejala,
                'nama_gejala' => $gejala->nama_gejala,
            ])
            ->values();

        $solusies = $hasilUtama->kerusakan->solusies
            ->map(fn ($solusi) => [
                'nama_solusi' => $solusi->nama_solusi,
                'deskripsi' => $solusi->deskripsi,
            ])
            ->values();

        RiwayatDiagnosa::create([
            'user_id' => Auth::id(),
            'kerusakan_id' => $hasilUtama->kerusakan->id,
            'nama_kerusakan' => $hasilUtama->kerusakan->nama_kerusakan,
            'persentase' => $hasilUtama->persentase,
            'jumlah_gejala_cocok' => $hasilUtama->jumlahCocok,
            'total_gejala' => $hasilUtama->totalGejala,
            'gejala_terpilih' => $selectedGejalas,
            'solusi' => $solusies,
            'status' => 'Aktif',
        ]);

        return redirect()->route('mekanik.riwayat')
            ->with('success', 'Hasil diagnosa berhasil disimpan ke log riwayat.');
    }

    public function riwayat()
    {
        $riwayats = RiwayatDiagnosa::with('user')
            ->latest()
            ->get();

        return view('mekanik.riwayat', compact('riwayats'));
    }

    public function hapusRiwayat(RiwayatDiagnosa $riwayatDiagnosa)
    {
        $riwayatDiagnosa->delete();

        return redirect()->route('mekanik.riwayat')
            ->with('success', 'Log riwayat berhasil dibatalkan.');
    }

    private function hitungHasil($selectedIds)
    {
        $selectedIds = collect($selectedIds)->map(fn ($id) => (int) $id)->values();

        if ($selectedIds->isEmpty()) {
            return collect();
        }

        return Kerusakan::with(['gejalas', 'solusies'])
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
}
