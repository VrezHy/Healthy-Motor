<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gejala;
use App\Models\Kerusakan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GejalaTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Kerusakan $kerusakan;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Buat User Admin agar lolos dari satpam middleware rute auth & role:admin
        $this->admin = User::create([
            'name' => 'Admin Testing',
            'username' => 'admin.test',
            'password' => bcrypt('Password123.'),
            'role' => 'admin'
        ]);

        // 2. Buat data kerusakan tiruan karena form gejala membutuhkan kerusakan_id sebagai relasi
        $this->kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Overheat'
        ]);
    }

    public function test_halaman_gejala_bisa_dibuka(): void
    {
        // === ACT ===
        $response = $this->actingAs($this->admin)
            ->get(route('admin.gejala'));

        // === ASSERT ===
        $response->assertStatus(200);
        $response->assertViewIs('admin.gejala');
    }

    public function test_bisa_menyimpan_data_gejala_motor_baru(): void
    {
        // === ARRANGE ===
        $dataInput = [
            'kode_gejala'  => 'G01',
            'nama_gejala'  => 'Suhu indikator naik di atas normal',
            'kerusakan_id' => $this->kerusakan->id
        ];

        // === ACT ===
        $response = $this->actingAs($this->admin)
            ->post(route('admin.gejala.store'), $dataInput);

        // === ASSERT ===
        $response->assertRedirect(route('admin.gejala'));
        $this->assertDatabaseHas('gejalas', [
            'kode_gejala' => 'G01',
            'nama_gejala' => 'Suhu indikator naik di atas normal'
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
        $response = $this->actingAs($this->admin)
            ->post(route('admin.gejala.store'), $dataKosong);

        // === ASSERT ===
        $response->assertSessionHasErrors(['kode_gejala', 'nama_gejala']);
    }

    public function test_validasi_gagal_jika_kode_gejala_sudah_ada(): void
    {
        // === ARRANGE ===
        // Buat satu gejala master terlebih dahulu
        Gejala::create([
            'kode_gejala'  => 'G01',
            'nama_gejala'  => 'Gejala Pertama',
            'kerusakan_id' => $this->kerusakan->id
        ]);

        // Input data baru dengan kode_gejala yang sama (G01)
        $dataDuplikat = [
            'kode_gejala'  => 'G01',
            'nama_gejala'  => 'Gejala Baru Yang Berbeda',
            'kerusakan_id' => $this->kerusakan->id
        ];

        // === ACT ===
        $response = $this->actingAs($this->admin)
            ->post(route('admin.gejala.store'), $dataDuplikat);

        // === ASSERT ===
        $response->assertSessionHasErrors(['kode_gejala']);
    }
}