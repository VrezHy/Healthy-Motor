<?php

namespace Tests\Feature;

use App\Models\RiwayatDiagnosa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiwayatDiagnosaTest extends TestCase
{
    use RefreshDatabase;

    private User $userFormalitas;
    private RiwayatDiagnosa $riwayat;

    /**
     * Setup awal: Membuat satu user formalitas dan data riwayat diagnosa tiruan
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Ditambahkan role mekanik murni agar lolos dari satpam middleware rute (web.php)
        $this->userFormalitas = User::create([
            'name' => 'User Testing',
            'username' => 'user.test',
            'password' => bcrypt('password'),
            'role' => 'mekanik' 
        ]);

        // Membuat data riwayat diagnosa tiruan untuk diuji
        $this->riwayat = RiwayatDiagnosa::create([
            'user_id' => $this->userFormalitas->id,
            'nama_kerusakan' => 'Mesin Overheat',
            'persentase' => 80,
            'jumlah_gejala_cocok' => 4,
            'total_gejala' => 5,
            'gejala_terpilih' => [['kode_gejala' => 'G01', 'nama_gejala' => 'Mesin Panas']],
            'solusi' => [['nama_solusi' => 'Ganti Air Radiator', 'deskripsi' => 'Ganti segera']],
            'status' => 'Aktif',
            'nama_pelanggan' => 'Agus',
            'alamat_pelanggan' => 'Ketintang, Surabaya',
            'nomor_polisi' => 'L 1234 AB',
            'nomor_telepon' => '081234567890',
        ]);
    }

    /**
     * 1. Test Alur: Soft Delete Riwayat (TC-089)
     */
    public function test_sistem_bisa_memindahkan_riwayat_ke_tempat_sampah_soft_delete(): void
    {
        // === ARRANGE ===
        $user = $this->userFormalitas;
        $idData = $this->riwayat->id;

        // === ACT ===
        $response = $this->actingAs($user)
            ->delete(action([\App\Http\Controllers\DiagnosaMekanikController::class, 'hapusRiwayat'], $idData));

        // === ASSERT ===
        $response->assertRedirect(route('mekanik.riwayat'));
        $response->assertSessionHas('success', 'Log riwayat berhasil dipindahkan ke tempat sampah.');

        $this->assertDatabaseHas('riwayat_diagnosas', ['id' => $idData]);
        $this->assertSoftDeleted($this->riwayat);
    }

    /**
     * 2. Test Alur: Menampilkan Tempat Sampah (TC-090)
     */
    public function test_sistem_bisa_menampilkan_halaman_tempat_sampah_riwayat(): void
    {
        // === ARRANGE ===
        $user = $this->userFormalitas;
        $this->riwayat->delete();

        // === ACT ===
        $response = $this->actingAs($user)
            ->get(action([\App\Http\Controllers\DiagnosaMekanikController::class, 'sampahRiwayat']));

        // === ASSERT ===
        $response->assertStatus(200);
        $response->assertViewIs('mekanik.riwayat_sampah');
        
        $response->assertViewHas('riwayatsSampah', function ($riwayats) {
            return $riwayats->contains($this->riwayat);
        });
    }

    /**
     * 3. Test Alur: Restore Riwayat (TC-091)
     */
    public function test_sistem_bisa_mengembalikan_riwayat_dari_tempat_sampah_restore(): void
    {
        // === ARRANGE ===
        $user = $this->userFormalitas;
        $idData = $this->riwayat->id;
        $this->riwayat->delete();
        $this->assertSoftDeleted($this->riwayat);

        // === ACT ===
        $response = $this->actingAs($user)
            ->post(action([\App\Http\Controllers\DiagnosaMekanikController::class, 'restoreRiwayat'], $idData));

        // === ASSERT ===
        $response->assertRedirect(route('mekanik.riwayat.sampah'));
        $response->assertSessionHas('success', 'Log riwayat berhasil dikembalikan.');

        $this->assertFalse($this->riwayat->fresh()->trashed());
    }

    /**
     * 4. Test Alur: Force Delete / Hapus Permanen (TC-092)
     */
    public function test_sistem_bisa_menghapus_riwayat_secara_permanen(): void
    {
        // === ARRANGE ===
        $user = $this->userFormalitas;
        $idData = $this->riwayat->id;
        $this->riwayat->delete();

        // === ACT ===
        $response = $this->actingAs($user)
            ->delete(action([\App\Http\Controllers\DiagnosaMekanikController::class, 'permanenHapusRiwayat'], $idData));

        // === ASSERT ===
        $response->assertRedirect(route('mekanik.riwayat.sampah'));
        $response->assertSessionHas('success', 'Log riwayat berhasil dihapus permanen.');

        $this->assertDatabaseMissing('riwayat_diagnosas', ['id' => $idData]);
    }
}