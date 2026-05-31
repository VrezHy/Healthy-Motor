<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\RiwayatDiagnosa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class DiagnosaMekanikController extends Controller
{
    public function index()
    {
        $gejalas = Gejala::orderBy('kode_gejala')->get();

        // Ubah ke analisis_diagnosa
        return view('mekanik.analisis_diagnosa', compact('gejalas'));
    }

    public function proses(Request $request)
    {
        // CEK APAKAH REQUEST AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $request->validate([
                'jawaban' => 'nullable|array',
                'threshold' => 'nullable|integer|min:0|max:100'
            ]);

            $jawaban = $request->input('jawaban', []);
            $threshold = $request->input('threshold', 60);

            $selectedIds = collect($jawaban)
                ->filter(fn($value) => $value === 'ya')
                ->keys()
                ->map(fn($id) => (int) $id)
                ->values();

            $hasil = $this->hitungHasil($selectedIds);

            // Format untuk JSON response
            $formattedHasil = $hasil->map(function ($item) use ($threshold) {
                return [
                    'id' => $item->kerusakan->id,
                    'kerusakan' => $item->kerusakan->nama_kerusakan,
                    'persentase' => $item->persentase,
                    'gejala_cocok' => $item->jumlahCocok,
                    'total_gejala' => $item->totalGejala,
                    'solusi' => $item->kerusakan->solusies->map(function ($solusi) {
                        return [
                            'nama_solusi' => $solusi->nama_solusi,
                            'deskripsi' => $solusi->deskripsi,
                        ];
                    })
                ];
            })->filter(fn($item) => $item['persentase'] >= $threshold)->values();

            return response()->json([
                'success' => true,
                'hasil' => $formattedHasil,
                'threshold' => $threshold
            ]);
        }

        // UNTUK REQUEST NON-AJAX (fallback)
        $request->validate([
            'jawaban' => 'nullable|array',
            'jawaban.*' => 'nullable|in:ya,tidak',
        ]);

        $gejalas = Gejala::orderBy('kode_gejala')->get();
        $jawaban = collect($request->input('jawaban', []));

        $selectedIds = $jawaban
            ->filter(fn($value) => $value === 'ya')
            ->keys()
            ->map(fn($id) => (int) $id)
            ->values();

        $selectedGejalas = Gejala::with('kerusakan')
            ->whereIn('id', $selectedIds)
            ->orderBy('kode_gejala')
            ->get();

        $hasil = $this->hitungHasil($selectedIds);

        return view('mekanik.analisis_diagnosa', compact(
            'gejalas',
            'jawaban',
            'selectedGejalas',
            'hasil'
        ));
    }

    public function simpan(Request $request)
    {
        // CEK APAKAH REQUEST AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $request->validate([
                'gejala_ids' => 'required|array|min:1',
                'gejala_ids.*' => 'integer|exists:gejalas,id',
            ]);

            $selectedIds = collect($request->input('gejala_ids'))
                ->map(fn($id) => (int) $id)
                ->values();

            $hasilUtama = $this->hitungHasil($selectedIds)->first();

            if (!$hasilUtama) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hasil diagnosa belum dapat disimpan.'
                ], 400);
            }

            $selectedGejalas = Gejala::whereIn('id', $selectedIds)
                ->orderBy('kode_gejala')
                ->get()
                ->map(fn($gejala) => [
                    'kode_gejala' => $gejala->kode_gejala,
                    'nama_gejala' => $gejala->nama_gejala,
                ])
                ->values();

            $solusies = $hasilUtama->kerusakan->solusies
                ->map(fn($solusi) => [
                    'nama_solusi' => $solusi->nama_solusi,
                    'deskripsi' => $solusi->deskripsi,
                ])
                ->values();

            RiwayatDiagnosa::create([
                'user_id' => Auth::user()?->id,
                'kerusakan_id' => $hasilUtama->kerusakan->id,
                'nama_kerusakan' => $hasilUtama->kerusakan->nama_kerusakan,
                'persentase' => $hasilUtama->persentase,
                'jumlah_gejala_cocok' => $hasilUtama->jumlahCocok,
                'total_gejala' => $hasilUtama->totalGejala,
                'gejala_terpilih' => $selectedGejalas,
                'solusi' => $solusies,
                'status' => 'Aktif',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Hasil diagnosa berhasil disimpan ke log riwayat.'
            ]);
        }

        // UNTUK REQUEST NON-AJAX (fallback)
        $request->validate([
            'gejala_ids' => 'required|array|min:1',
            'gejala_ids.*' => 'integer|exists:gejalas,id',
        ]);

        $selectedIds = collect($request->input('gejala_ids'))
            ->map(fn($id) => (int) $id)
            ->values();

        $hasilUtama = $this->hitungHasil($selectedIds)->first();

        if (!$hasilUtama) {
            return redirect()->route('mekanik.diagnosa')
                ->with('error', 'Hasil diagnosa belum dapat disimpan.');
        }

        $selectedGejalas = Gejala::whereIn('id', $selectedIds)
            ->orderBy('kode_gejala')
            ->get()
            ->map(fn($gejala) => [
                'kode_gejala' => $gejala->kode_gejala,
                'nama_gejala' => $gejala->nama_gejala,
            ])
            ->values();

        $solusies = $hasilUtama->kerusakan->solusies
            ->map(fn($solusi) => [
                'nama_solusi' => $solusi->nama_solusi,
                'deskripsi' => $solusi->deskripsi,
            ])
            ->values();

        RiwayatDiagnosa::create([
            'user_id' => Auth::user()?->id,
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
        $riwayats = Schema::hasTable('riwayat_diagnosas')
            ? RiwayatDiagnosa::with('user')->latest()->get()
            : collect();

        return view('mekanik.riwayat', compact('riwayats'));
    }

    public function hapusRiwayat(RiwayatDiagnosa $riwayatDiagnosa)
    {
        $riwayatDiagnosa->delete();

        return redirect()->route('mekanik.riwayat')
            ->with('success', 'Log riwayat berhasil dibatalkan.');
    }

    public function updateStatus(Request $request, RiwayatDiagnosa $riwayatDiagnosa)
    {
        $validated = $request->validate([
            'status' => 'required|in:Draft,Aktif,Done',
        ]);

        $riwayatDiagnosa->update($validated);

        return redirect()->route('mekanik.riwayat')
            ->with('success', 'Status berhasil diperbarui.');
    }

    public function simpanPelanggan(Request $request, RiwayatDiagnosa $riwayatDiagnosa)
    {
        $validator = Validator::make($request->all(), [
            'nama_pelanggan' => ['required', 'string', 'max:255', 'regex:/^[A-Za-z\s.\']+$/'],
            'alamat_pelanggan' => 'required|string|max:255',
            'nomor_polisi' => ['required', 'string', 'max:50', 'regex:/^[A-Za-z0-9\s-]+$/'],
            'nomor_telepon' => ['required', 'string', 'regex:/^[0-9]{10,13}$/'],
        ], [
            '*.required' => 'Field Not Must Be Empty!',
            'nama_pelanggan.regex' => 'Nama hanya boleh berisi huruf, spasi, titik, dan apostrof.',
            'nomor_polisi.regex' => 'Nomor polisi hanya boleh berisi huruf, angka, spasi, dan tanda hubung.',
            'nomor_telepon.regex' => 'Nomor telepon harus berisi 10 sampai 13 angka.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('mekanik.riwayat')
                ->withErrors($validator)
                ->withInput()
                ->with('pelanggan_form_id', $riwayatDiagnosa->id);
        }

        $riwayatDiagnosa->update($validator->validated());

        return redirect()->route('mekanik.riwayat')
            ->with('success', 'Data pelanggan berhasil disimpan.');
    }

    private function hitungHasil($selectedIds)
    {
        $selectedIds = collect($selectedIds)->map(fn($id) => (int) $id)->values();

        if ($selectedIds->isEmpty()) {
            return collect();
        }

        return Kerusakan::with(['gejalas', 'solusies'])
            ->whereHas('gejalas', fn($query) => $query->whereIn('id', $selectedIds))
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
            ->sortByDesc(fn($item) => ($item->persentase * 1000) + $item->jumlahCocok)
            ->values();
    }
}
