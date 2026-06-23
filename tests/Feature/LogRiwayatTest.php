<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kerusakan;
use App\Models\RiwayatDiagnosa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class LogRiwayatTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $kerusakan;

    protected function setUp(): void
    {
        parent::setUp();


        $this->user = User::create([
            'name' => 'Mekanik1',
            'username' => 'Mekanik1.mekanik',
            'password' => Hash::make('Mekanik123.'),
            'role' => 'mekanik',
        ]);


        $this->kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Aki Lemah'
        ]);
    }

    private function createRiwayat()
    {
        return RiwayatDiagnosa::create([
            'user_id' => $this->user->id,
            'kerusakan_id' => $this->kerusakan->id,
            'nama_kerusakan' => $this->kerusakan->nama_kerusakan,
            'persentase' => 80,
            'jumlah_gejala_cocok' => 4,
            'total_gejala' => 5,
            'gejala_terpilih' => [],
            'solusi' => [],
            'status' => 'Aktif',
        ]);
    }

    #[Test]
    public function test_halaman_riwayat_dapat_diakses()
    {
        $response = $this
            ->actingAs($this->user)
            ->get(route('mekanik.riwayat'));

        $response->assertStatus(200);
        $response->assertViewIs('mekanik.riwayat');
    }

    #[Test]
    public function test_status_berhasil_diubah()
    {
        $riwayat = $this->createRiwayat();

        $response = $this
            ->actingAs($this->user)
            ->put(
                route('mekanik.riwayat.status', $riwayat->id),
                [
                    'status' => 'Done'
                ]
            );

        $response->assertRedirect(route('mekanik.riwayat'));
        $response->assertStatus(302);

        $this->assertDatabaseHas('riwayat_diagnosas', [
            'id' => $riwayat->id,
            'status' => 'Done'
        ]);
    }

    #[Test]
    public function test_data_pelanggan_berhasil_disimpan()
    {
        $riwayat = $this->createRiwayat();

        $response = $this
            ->actingAs($this->user)
            ->put(
                route('mekanik.riwayat.pelanggan', $riwayat->id),
                [
                    'nama_pelanggan' => 'Ariana',
                    'alamat_pelanggan' => 'Surabaya',
                    'nomor_polisi' => 'L 1234 AB',
                    'nomor_telepon' => '081234567890',
                ]
            );

        $response->assertRedirect(route('mekanik.riwayat'));
        $response->assertStatus(302);

        $this->assertDatabaseHas('riwayat_diagnosas', [
            'id' => $riwayat->id,
            'nama_pelanggan' => 'Ariana',
            'alamat_pelanggan' => 'Surabaya',
            'nomor_polisi' => 'L 1234 AB',
            'nomor_telepon' => '081234567890',
        ]);
    }

    #[Test]
    public function test_riwayat_berhasil_dihapus()
    {
        $riwayat = $this->createRiwayat();
        $id = $riwayat->id;

        $response = $this
            ->actingAs($this->user)
            ->delete(route('mekanik.riwayat.hapus', $id));

        $response->assertRedirect(route('mekanik.riwayat'));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('riwayat_diagnosas', [
            'id' => $id
        ]);
    }
}
