<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\RiwayatDiagnosa;

class DiagnosaMekanikTest extends TestCase
{
  use RefreshDatabase;

  public function test_analisis_diagnosa()
  {
    $mekanik = User::factory()->create([
      'role' => 'mekanik',
    ]);
    $kerusakanMesin = Kerusakan::create([
      'nama_kerusakan' => 'Mesin Cepat Panas',
    ]);

    $gejala1 = Gejala::create([
      'kode_gejala' => 'G01',
      'nama_gejala' => 'Kenalpot Berasap',
      'kerusakan_id' => $kerusakanMesin->id
    ]);

    $gejala2 = Gejala::create([
      'kode_gejala' => 'G02',
      'nama_gejala' => 'Suara Mesin Kasar',
      'kerusakan_id' => $kerusakanMesin->id
    ]);

    $responseHalaman = $this->actingAs($mekanik)->get(route('mekanik.diagnosa'));
    $responseHalaman->assertStatus(200);

    $response = $this->actingAs($mekanik)->post(route('mekanik.diagnosa.proses'), [
      'jawaban' => [
        $gejala1->id => 'ya',
        $gejala2->id => 'ya',
      ],
    ]);
    $response->assertStatus(200);
    $response->assertViewHas('hasil', function ($hasil) use ($kerusakanMesin) {
      return $hasil->isNotEmpty()
        && $hasil->first()->kerusakan->is($kerusakanMesin)
        && (int) $hasil->first()->persentase === 100;
    });

    $responseSimpan = $this->actingAs($mekanik)->post(route('mekanik.diagnosa.simpan'), [
      'gejala_ids' => [$gejala1->id, $gejala2->id],
    ]);
    $responseSimpan->assertRedirect(route('mekanik.riwayat'));

    $this->assertDatabaseHas('riwayat_diagnosas', [
      'user_id' => $mekanik->id,
      'kerusakan_id' => $kerusakanMesin->id,
      'nama_kerusakan' => $kerusakanMesin->nama_kerusakan,
      'persentase' => 100,
      'jumlah_gejala_cocok' => 2,
      'total_gejala' => 2,
      'status' => 'Aktif',
    ]);

    $this->assertEquals(1, RiwayatDiagnosa::count());
  }

  public function test_diagnosa_tanpa_dikembalikan_kosong()
  {
    $mekanik = User::factory()->create([
      'role' => 'mekanik',
    ]);

    $response = $this->actingAs($mekanik)->post(route('mekanik.diagnosa.proses'), [
      'jawaban' => [],
    ]);
    $response->assertStatus(200);
    $response->assertViewHas('hasil', function ($hasil) {
      return $hasil->isEmpty();
    });
  }

  public function test_validasi_error_saat_menyimpan_tanpa_gejala_terpilih()
  {
    $mekanik = User::factory()->create([
      'role' => 'mekanik',
    ]);

    $response = $this->actingAs($mekanik)->post(route('mekanik.diagnosa.simpan'), [
      'gejala_ids' => [],
    ]);

    $response->assertSessionHasErrors('gejala_ids');
    $this->assertEquals(0, RiwayatDiagnosa::count());
  }

  public function test_pengguna_belum_login_dilarang_akses_diagnosa()
  {
    $response = $this->get(route('mekanik.diagnosa'));
    $response->assertRedirect(route('login'));
  }
}
