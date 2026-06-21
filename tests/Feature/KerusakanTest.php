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
        $response = $this->get('/admin/kerusakan');
        $response->assertStatus(200);
    }

    public function test_bisa_menyimpan_data_kerusakan_baru(): void
    {
        $response = $this->post('/admin/kerusakan', [
            'nama_kerusakan' => 'Mesin Mati Total',
        ]);

        $response->assertRedirect(route('admin.kerusakan'));
        $response->assertSessionHas('success', 'Data kerusakan berhasil ditambahkan.');

        $this->assertDatabaseHas('kerusakans', [
            'nama_kerusakan' => 'Mesin Mati Total',
        ]);
    }

    public function test_validasi_gagal_jika_nama_kerusakan_kosong(): void
    {
        $response = $this->post('/admin/kerusakan', [
            'nama_kerusakan' => '',
        ]);

        $response->assertSessionHasErrors(['nama_kerusakan']);
        
        $this->assertDatabaseMissing('kerusakans', [
            'nama_kerusakan' => '',
        ]);
    }
}