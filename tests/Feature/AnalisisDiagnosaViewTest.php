<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gejala;
use App\Models\Kerusakan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnalisisDiagnosaViewTest extends TestCase
{
    use RefreshDatabase;

    private string $viewName = 'mekanik.analisis_diagnosa';

    private function loginSebagaiMekanik(): User
    {
        $mekanik = User::factory()->create([
            'name' => 'Mekanik Test',
            'username' => 'mekanik.test',
            'role' => 'mekanik',
        ]);

        $this->actingAs($mekanik);

        return $mekanik;
    }

    public function test_radio_ya_checked_jika_jawaban_bernilai_ya()
    {
        $this->loginSebagaiMekanik();

        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Cepat Panas',
        ]);

        $gejala = Gejala::create([
            'kode_gejala' => 'G01',
            'nama_gejala' => 'Knalpot Berasap',
            'kerusakan_id' => $kerusakan->id,
        ]);

        $view = $this->view($this->viewName, [
            'gejalas' => collect([$gejala]),
            'jawaban' => [
                $gejala->id => 'ya',
            ],
        ]);

        $view->assertSee('value="ya" checked', false);
    }

    public function test_radio_tidak_checked_jika_jawaban_bernilai_tidak()
    {
        $this->loginSebagaiMekanik();

        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Cepat Panas',
        ]);

        $gejala = Gejala::create([
            'kode_gejala' => 'G01',
            'nama_gejala' => 'Knalpot Berasap',
            'kerusakan_id' => $kerusakan->id,
        ]);

        $view = $this->view($this->viewName, [
            'gejalas' => collect([$gejala]),
            'jawaban' => [
                $gejala->id => 'tidak',
            ],
        ]);

        $view->assertSee('value="tidak" checked', false);
    }

    public function test_menampilkan_pesan_jika_tidak_ada_gejala_yang_dijawab_ya()
    {
        $this->loginSebagaiMekanik();

        $view = $this->view($this->viewName, [
            'gejalas' => collect(),
            'hasil' => collect(),
            'selectedGejalas' => collect(),
        ]);

        $view->assertSeeText('Belum ada gejala yang dijawab "Ya", jadi hasil diagnosa belum bisa ditentukan.', false);
    }

    public function test_menampilkan_pesan_jika_gejala_tidak_cocok_dengan_data_kerusakan()
    {
        $this->loginSebagaiMekanik();

        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Cepat Panas',
        ]);

        $gejala = Gejala::create([
            'kode_gejala' => 'G01',
            'nama_gejala' => 'Knalpot Berasap',
            'kerusakan_id' => $kerusakan->id,
        ]);

        $view = $this->view($this->viewName, [
            'gejalas' => collect([$gejala]),
            'selectedGejalas' => collect([$gejala]),
            'hasil' => collect(),
        ]);

        $view->assertSee('Gejala yang dipilih belum cocok dengan data kerusakan.');
    }

    public function test_menampilkan_hasil_diagnosa_jika_hasil_tersedia()
    {
        $this->loginSebagaiMekanik();

        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Cepat Panas',
        ]);

        $kerusakan->setRelation('solusies', collect());

        $gejala1 = Gejala::create([
            'kode_gejala' => 'G01',
            'nama_gejala' => 'Knalpot Berasap',
            'kerusakan_id' => $kerusakan->id,
        ]);

        $gejala2 = Gejala::create([
            'kode_gejala' => 'G02',
            'nama_gejala' => 'Suara Mesin Kasar',
            'kerusakan_id' => $kerusakan->id,
        ]);

        $hasilItem = (object) [
            'kerusakan' => $kerusakan,
            'persentase' => 100,
            'jumlahCocok' => 2,
            'totalGejala' => 2,
        ];

        $view = $this->view($this->viewName, [
            'gejalas' => collect([$gejala1, $gejala2]),
            'selectedGejalas' => collect([$gejala1, $gejala2]),
            'hasil' => collect([$hasilItem]),
        ]);

        $view->assertSee('Mesin Cepat Panas');
        $view->assertSee('100%');
        $view->assertSee('2 dari 2 gejala cocok.');
        $view->assertSee('Simpan');
    }

    public function test_menampilkan_solusi_jika_data_solusi_tersedia()
    {
        $this->loginSebagaiMekanik();

        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Cepat Panas',
        ]);

        $solusi = (object) [
            'nama_solusi' => 'Ganti Oli',
            'deskripsi' => 'Lakukan penggantian oli secara berkala',
        ];

        $kerusakan->setRelation('solusies', collect([$solusi]));

        $gejala = Gejala::create([
            'kode_gejala' => 'G01',
            'nama_gejala' => 'Knalpot Berasap',
            'kerusakan_id' => $kerusakan->id,
        ]);

        $hasilItem = (object) [
            'kerusakan' => $kerusakan,
            'persentase' => 100,
            'jumlahCocok' => 1,
            'totalGejala' => 1,
        ];

        $view = $this->view($this->viewName, [
            'gejalas' => collect([$gejala]),
            'selectedGejalas' => collect([$gejala]),
            'hasil' => collect([$hasilItem]),
        ]);

        $view->assertSee('Solusi');
        $view->assertSee('Ganti Oli');
        $view->assertSee('Lakukan penggantian oli secara berkala');
    }

    public function test_tidak_menampilkan_kotak_solusi_jika_data_solusi_kosong()
    {
        $this->loginSebagaiMekanik();

        $kerusakan = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Cepat Panas',
        ]);

        $kerusakan->setRelation('solusies', collect());

        $gejala = Gejala::create([
            'kode_gejala' => 'G01',
            'nama_gejala' => 'Knalpot Berasap',
            'kerusakan_id' => $kerusakan->id,
        ]);

        $hasilItem = (object) [
            'kerusakan' => $kerusakan,
            'persentase' => 100,
            'jumlahCocok' => 1,
            'totalGejala' => 1,
        ];

        $view = $this->view($this->viewName, [
            'gejalas' => collect([$gejala]),
            'selectedGejalas' => collect([$gejala]),
            'hasil' => collect([$hasilItem]),
        ]);

        $view->assertSee('Mesin Cepat Panas');
        $view->assertDontSee('Solusi');
    }
}
