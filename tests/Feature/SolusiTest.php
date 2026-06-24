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
        // === ARRANGE ===
        $url = '/admin/solusi';

        // === ACT ===
        $response = $this->get($url);

        // === ASSERT ===
        $response->assertStatus(200);
    }

    public function test_bisa_menyimpan_data_solusi_baru(): void
    {
        // === ARRANGE ===
        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Mati Total'
        ]);

        $dataInput = [
            'nama_solusi'  => 'Ganti Busi',
            'deskripsi'    => 'Ganti busi lama dengan busi standar pabrikan baru.',
            'kerusakan_id' => $kerusakan->id,
        ];

        // === ACT ===
        $response = $this->post('/admin/solusi', $dataInput);

        // === ASSERT ===
        $response->assertRedirect(route('admin.solusi'));

        $this->assertDatabaseHas('solusies', [
            'nama_solusi'  => 'Ganti Busi',
            'deskripsi'    => 'Ganti busi lama dengan busi standar pabrikan baru.',
            'kerusakan_id' => $kerusakan->id,
        ]);
    }

    public function test_validasi_gagal_jika_form_solusi_kosong(): void
    {
        // === ARRANGE ===
        $dataKosong = [
            'nama_solusi'  => '',
            'deskripsi'    => '',
            'kerusakan_id' => ''
        ];

        // === ACT ===
        $response = $this->post('/admin/solusi', $dataKosong);

        // === ASSERT ===
        $response->assertSessionHasErrors(['nama_solusi', 'kerusakan_id']);
    }
}