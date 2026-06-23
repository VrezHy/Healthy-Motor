<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kerusakan;
use App\Models\RiwayatDiagnosa;

class RiwayatTest extends TestCase
{

    private function createRiwayat()
    {
        $user = User::first();

        if (!$user) {
            $user = User::create([
                'name' => 'Mekanik1',
                'username' => 'Mekanik1.mekanik',
                'password' => bcrypt('Mekanik123.'),
                'role' => 'mekanik',
            ]);
        }

        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Aki Lemah'
        ]);

        $this->assertNotNull(
            $kerusakan,
            'Data kerusakan tidak ditemukan. Jalankan seeder terlebih dahulu.'
        );

        return RiwayatDiagnosa::create([
            'user_id' => $user->id,
            'kerusakan_id' => $kerusakan->id,
            'nama_kerusakan' => $kerusakan->nama_kerusakan,
            'persentase' => 80,
            'jumlah_gejala_cocok' => 4,
            'total_gejala' => 5,
            'gejala_terpilih' => [],
            'solusi' => [],
            'status' => 'Aktif',
        ]);
    }

    public function test_halaman_riwayat_dapat_diakses()
    {
        $user = User::first();

        $response = $this
            ->actingAs($user)
            ->get(route('mekanik.riwayat'));

        $response->assertStatus(200);

        $response->assertViewIs('mekanik.riwayat');
    }

    public function test_status_berhasil_diubah()
    {
        $user = User::first();

        $riwayat = $this->createRiwayat();

        $response = $this
            ->actingAs($user)
            ->put(
                route('mekanik.riwayat.status', $riwayat->id),
                [
                    'status' => 'Done'
                ]
            );

        $response->assertRedirect(
            route('mekanik.riwayat')
        );

        $this->assertDatabaseHas(
            'riwayat_diagnosas',
            [
                'id' => $riwayat->id,
                'status' => 'Done'
            ]
        );
    }

    public function test_data_pelanggan_berhasil_disimpan()
    {
        $user = User::first();

        $riwayat = $this->createRiwayat();

        $response = $this
            ->actingAs($user)
            ->put(
                route('mekanik.riwayat.pelanggan', $riwayat->id),
                [
                    'nama_pelanggan' => 'Ariana',
                    'alamat_pelanggan' => 'Surabaya',
                    'nomor_polisi' => 'L 1234 AB',
                    'nomor_telepon' => '081234567890',
                ]
            );

        $response->assertRedirect(
            route('mekanik.riwayat')
        );

        $this->assertDatabaseHas(
            'riwayat_diagnosas',
            [
                'id' => $riwayat->id,
                'nama_pelanggan' => 'Ariana',
                'alamat_pelanggan' => 'Surabaya',
                'nomor_polisi' => 'L 1234 AB',
                'nomor_telepon' => '081234567890',
            ]
        );
    }

    public function test_riwayat_berhasil_dihapus()
    {
        $user = User::first();

        $riwayat = $this->createRiwayat();

        $id = $riwayat->id;

        $response = $this
            ->actingAs($user)
            ->delete(
                route('mekanik.riwayat.hapus', $id)
            );

        $response->assertRedirect(
            route('mekanik.riwayat')
        );

        $this->assertDatabaseMissing(
            'riwayat_diagnosas',
            [
                'id' => $id
            ]
        );
    }
}