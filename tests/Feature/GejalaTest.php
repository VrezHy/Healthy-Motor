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
        // === ARRANGE ===
        $url = '/admin/gejala';

        // === ACT ===
        $response = $this->get($url);

        // === ASSERT ===
        $response->assertStatus(200);
    }

    public function test_bisa_menyimpan_data_gejala_motor_baru(): void
    {
        // === ARRANGE ===
        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Cepat Panas'
        ]);

        $dataInput = [
            'kode_gejala'  => 'G01',
            'nama_gejala'  => 'Suhu indikator naik di atas normal',
            'kerusakan_id' => $kerusakan->id 
        ];

        // === ACT ===
        $response = $this->post('/admin/gejala', $dataInput);

        // === ASSERT ===
        $response->assertRedirect(route('admin.gejala'));

        $this->assertDatabaseHas('gejalas', [
            'kode_gejala'  => 'G01',
            'nama_gejala'  => 'Suhu indikator naik di atas normal',
            'kerusakan_id' => $kerusakan->id
        ]);
    }

    public function test_validasi_gagal_jika_form_gejala_kosong(): void
    {
        // === ARRANGE ===
        $dataKosong = [
            'kode_gejala'  => '',
            'nama_gejala'  => '',
            'kerusakan_id' => ''
        ];

        // === ACT ===
        $response = $this->post('/admin/gejala', $dataKosong);

        // === ASSERT ===
        $response->assertSessionHasErrors(['kode_gejala', 'nama_gejala', 'kerusakan_id']);
    }

    public function test_validasi_gagal_jika_kode_gejala_sudah_ada(): void
    {
        // === ARRANGE ===
        $kerusakan = Kerusakan::create(['nama_kerusakan' => 'Mesin Berisik']);
        
        Gejala::create([
            'kode_gejala'  => 'G99',
            'nama_gejala'  => 'Suara kasar dari blok mesin',
            'kerusakan_id' => $kerusakan->id
        ]);

   
        $dataDuplikat = [
            'kode_gejala'  => 'G99', 
            'nama_gejala'  => 'Suara kletek-kletek',
            'kerusakan_id' => $kerusakan->id
        ];

        // === ACT ===
        $response = $this->post('/admin/gejala', $dataDuplikat);

        // === ASSERT ===
        $response->assertSessionHasErrors(['kode_gejala']);
    }
}