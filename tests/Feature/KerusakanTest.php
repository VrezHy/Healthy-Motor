<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Kerusakan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KerusakanTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Buat User Admin tiruan agar lolos dari proteksi middleware auth & role:admin
        $this->admin = User::create([
            'name' => 'Admin Testing',
            'username' => 'admin.test',
            'password' => bcrypt('Password123.'),
            'role' => 'admin'
        ]);
    }

    public function test_halaman_kerusakan_bisa_dibuka(): void
    {
        // === ACT ===
        // Menggunakan actingAs agar disangka sudah login sebagai admin
        $response = $this->actingAs($this->admin)
            ->get(route('admin.kerusakan'));

        // === ASSERT ===
        $response->assertStatus(200);
        $response->assertViewIs('admin.kerusakan');
    }

    public function test_bisa_menyimpan_data_kerusakan_baru(): void
    {
        // === ARRANGE ===
        $dataInput = [
            'nama_kerusakan' => 'Mesin Mati Total',
        ];

        // === ACT ===
        $response = $this->actingAs($this->admin)
            ->post(route('admin.kerusakan.store'), $dataInput);

        // === ASSERT ===
        $response->assertRedirect(route('admin.kerusakan'));
        
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
        $response = $this->actingAs($this->admin)
            ->post(route('admin.kerusakan.store'), $dataKosong);

        // === ASSERT ===
        $response->assertSessionHasErrors(['nama_kerusakan']);
        
        $this->assertDatabaseMissing('kerusakans', [
            'nama_kerusakan' => '',
        ]);
    }
}