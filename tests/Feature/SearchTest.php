<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\Solusi;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin.admin',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $this->actingAs($user);
    }

    // --- TEST SUCCESS ---

    public function test_berhasil_cari_kerusakan()
    {
        Kerusakan::create(['nama_kerusakan' => 'Ban Bocor']);

        $response = $this->get('/admin/kerusakan?search=Ban');
        $response->assertStatus(200);
        $response->assertSee('Ban Bocor');
    }

    public function test_berhasil_cari_gejala()
    {
        $kerusakan = Kerusakan::create(['nama_kerusakan' => 'Mesin']);
        Gejala::create([
            'kode_gejala' => 'G001',
            'nama_gejala' => 'Mesin Mati',
            'kerusakan_id' => $kerusakan->id
        ]);

        $response = $this->get('/admin/gejala?search=Mesin');
        $response->assertStatus(200);
        $response->assertSee('Mesin Mati');
    }

    public function test_berhasil_cari_solusi()
    {
        $kerusakan = Kerusakan::create(['nama_kerusakan' => 'Mesin Mati']);
        Solusi::create([
            'nama_solusi' => 'Ganti Oli',
            'kerusakan_id' => $kerusakan->id
        ]);

        $response = $this->get('/admin/solusi?search=Oli');
        $response->assertStatus(200);
        $response->assertSee('Ganti Oli');
    }

    // --- TEST GAGAL (Data Tidak Ditemukan) ---
    public function test_gagal_cari_kerusakan()
    {
        $response = $this->get('/admin/kerusakan?search=DataTidakAda');
        $response->assertStatus(200);
        $response->assertSee('Data tidak ditemukan');
    }

    public function test_gagal_cari_gejala()
    {
        $response = $this->get('/admin/gejala?search=DataTidakAda');
        $response->assertStatus(200);
        $response->assertSee('Data tidak ditemukan');
    }

    public function test_gagal_cari_solusi()
    {
        $response = $this->get('/admin/solusi?search=DataTidakAda');
        $response->assertStatus(200);
        $response->assertSee('Data tidak ditemukan');
    }
}
