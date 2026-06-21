<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Kerusakan;
use App\Models\Gejala;

class GejalaTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_gejala_bisa_dibuka(): void
    {
        $response = $this->get('/admin/gejala');
        $response->assertStatus(200);
    }

    public function test_bisa_menyimpan_data_gejala_motor_baru(): void
    {
        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Cepat Panas'
        ]);

        $response = $this->post('/admin/gejala', [
            'kode_gejala'  => 'G01',
            'nama_gejala'  => 'Suhu indikator naik di atas normal',
            'kerusakan_id' => $kerusakan->id 
        ]);

        $response->assertRedirect(route('admin.gejala'));

        $this->assertDatabaseHas('gejalas', [
            'kode_gejala'  => 'G01',
            'nama_gejala'  => 'Suhu indikator naik di atas normal',
            'kerusakan_id' => $kerusakan->id
        ]);
    }

    public function test_validasi_gagal_jika_form_gejala_kosong(): void
    {
        $response = $this->post('/admin/gejala', [
            'kode_gejala'  => '',
            'nama_gejala'  => '',
            'kerusakan_id' => ''
        ]);

        $response->assertSessionHasErrors(['kode_gejala', 'nama_gejala', 'kerusakan_id']);
    }

    public function test_validasi_gagal_jika_kode_gejala_sudah_ada(): void
    {
        $kerusakan = Kerusakan::create(['nama_kerusakan' => 'Mesin Berisik']);
        Gejala::create([
            'kode_gejala'  => 'G99',
            'nama_gejala'  => 'Suara kasar dari blok mesin',
            'kerusakan_id' => $kerusakan->id
        ]);

        // Mencoba simpan dengan kode_gejala yang sama ('G99')
        $response = $this->post('/admin/gejala', [
            'kode_gejala'  => 'G99', 
            'nama_gejala'  => 'Suara kletek-kletek',
            'kerusakan_id' => $kerusakan->id
        ]);

        $response->assertSessionHasErrors(['kode_gejala']);
    }
}