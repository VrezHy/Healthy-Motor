<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Kerusakan;

class KerusakanTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_kerusakan_bisa_dibuka(): void
    {
        // === ARRANGE ===
        $url = '/admin/kerusakan';

        // === ACT ===
        $response = $this->get($url);

        // === ASSERT ===
        $response->assertStatus(200);
    }

    public function test_bisa_menyimpan_data_kerusakan_baru(): void
    {
        // === ARRANGE ===
        $dataInput = [
            'nama_kerusakan' => 'Mesin Mati Total',
        ];

        // === ACT ===
        $response = $this->post('/admin/kerusakan', $dataInput);

        // === ASSERT ===
        $response->assertRedirect(route('admin.kerusakan'));
        $response->assertSessionHas('success', 'Data kerusakan berhasil ditambahkan.');

        $this->assertDatabaseHas('kerusakans', [
            'nama_kerusakan' => 'Mesin Mati Total',
        ]);
    }

    public function test_validasi_gagal_jika_nama_kerusakan_kosong(): void
    {
        // === ARRANGE ===
        $dataKosong = [
            'nama_kerusakan' => '',
        ];

        // === ACT ===
        $response = $this->post('/admin/kerusakan', $dataKosong);

        // === ASSERT ===
        $response->assertSessionHasErrors(['nama_kerusakan']);
        
        $this->assertDatabaseMissing('kerusakans', [
            'nama_kerusakan' => '',
        ]);
    }
}