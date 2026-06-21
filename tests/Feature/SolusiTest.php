<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Kerusakan;

class SolusiTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_solusi_bisa_dibuka(): void
    {
        $response = $this->get('/admin/solusi');
        $response->assertStatus(200);
    }

    public function test_bisa_menyimpan_data_solusi_baru(): void
    {
        // Buat data kerusakan terlebih dahulu untuk memenuhi relasi
        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Mati Total'
        ]);

        $response = $this->post('/admin/solusi', [
            'nama_solusi'  => 'Ganti Busi',
            'deskripsi'    => 'Ganti busi lama dengan busi standar pabrikan baru.',
            'kerusakan_id' => $kerusakan->id,
        ]);

        $response->assertRedirect(route('admin.solusi'));

        $this->assertDatabaseHas('solusies', [
            'nama_solusi'  => 'Ganti Busi',
            'deskripsi'    => 'Ganti busi lama dengan busi standar pabrikan baru.',
            'kerusakan_id' => $kerusakan->id,
        ]);
    }

    public function test_validasi_gagal_jika_form_solusi_kosong(): void
    {
        $response = $this->post('/admin/solusi', [
            'nama_solusi'  => '',
            'deskripsi'    => '',
            'kerusakan_id' => ''
        ]);

        // Asumsi nama_solusi dan kerusakan_id wajib diisi (required)
        $response->assertSessionHasErrors(['nama_solusi', 'kerusakan_id']);
    }
}