<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\Solusi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class DiagnosaTest extends TestCase
{
    use RefreshDatabase;

    private $mekanik;
    private $admin;
    private $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'pemilik bengkel',
            'password' => Hash::make('DM5SPM'),
            'role' => 'superadmin'
        ]);

        $this->admin = User::create([
            'name' => 'Admin',
            'username' => 'admin1',
            'password' => Hash::make('A123456.'),
            'role' => 'admin'
        ]);

        $this->mekanik = User::create([
            'name' => 'Test Mekanik',
            'username' => 'testmekanik.mekanik',
            'password' => Hash::make('Testmekanik1.'),
            'role' => 'mekanik'
        ]);



        $kerusakanOverheat = Kerusakan::create(['nama_kerusakan' => 'Mesin Overheat']);
        $kerusakanAki = Kerusakan::create(['nama_kerusakan' => 'Aki Lemah']);
        $kerusakanKarburator = Kerusakan::create(['nama_kerusakan' => 'Karburator Kotor']);


        Gejala::create([
            'kode_gejala' => 'G001',
            'nama_gejala' => 'Temperatur mesin naik drastis',
            'kerusakan_id' => $kerusakanOverheat->id
        ]);
        Gejala::create([
            'kode_gejala' => 'G002',
            'nama_gejala' => 'Keluar asap dari mesin',
            'kerusakan_id' => $kerusakanOverheat->id
        ]);
        Gejala::create([
            'kode_gejala' => 'G003',
            'nama_gejala' => 'Air radiator berkurang cepat',
            'kerusakan_id' => $kerusakanOverheat->id
        ]);


        Gejala::create([
            'kode_gejala' => 'G004',
            'nama_gejala' => 'Motor sulit dihidupkan',
            'kerusakan_id' => $kerusakanAki->id
        ]);
        Gejala::create([
            'kode_gejala' => 'G005',
            'nama_gejala' => 'Lampu redup saat mesin mati',
            'kerusakan_id' => $kerusakanAki->id
        ]);
        Gejala::create([
            'kode_gejala' => 'G006',
            'nama_gejala' => 'Klakson lemah',
            'kerusakan_id' => $kerusakanAki->id
        ]);


        Gejala::create([
            'kode_gejala' => 'G007',
            'nama_gejala' => 'Mesin tersendat-sendat',
            'kerusakan_id' => $kerusakanKarburator->id
        ]);
        Gejala::create([
            'kode_gejala' => 'G008',
            'nama_gejala' => 'Boros bahan bakar',
            'kerusakan_id' => $kerusakanKarburator->id
        ]);
        Gejala::create([
            'kode_gejala' => 'G009',
            'nama_gejala' => 'Asap knalpot hitam',
            'kerusakan_id' => $kerusakanKarburator->id
        ]);



        Solusi::create([
            'kerusakan_id' => $kerusakanOverheat->id,
            'nama_solusi' => 'Periksa dan isi air radiator',
            'deskripsi' => 'Pastikan air radiator selalu dalam kondisi penuh dan tidak bocor.'
        ]);
        Solusi::create([
            'kerusakan_id' => $kerusakanOverheat->id,
            'nama_solusi' => 'Ganti thermostat',
            'deskripsi' => 'Thermostat yang rusak dapat menyebabkan sirkulasi air terhambat.'
        ]);
        Solusi::create([
            'kerusakan_id' => $kerusakanAki->id,
            'nama_solusi' => 'Charge aki',
            'deskripsi' => 'Lakukan pengisian daya aki menggunakan charger.'
        ]);
        Solusi::create([
            'kerusakan_id' => $kerusakanAki->id,
            'nama_solusi' => 'Ganti aki baru',
            'deskripsi' => 'Jika aki sudah tidak bisa menyimpan daya, segera ganti aki baru.'
        ]);
        Solusi::create([
            'kerusakan_id' => $kerusakanKarburator->id,
            'nama_solusi' => 'Bersihkan karburator',
            'deskripsi' => 'Bongkar dan bersihkan karburator dari kotoran dan endapan bahan bakar.'
        ]);
        Solusi::create([
            'kerusakan_id' => $kerusakanKarburator->id,
            'nama_solusi' => 'Setel ulang karburator',
            'deskripsi' => 'Lakukan penyetelan ulang karburator sesuai spesifikasi motor.'
        ]);
    }

    #[Test]
    public function semua_gejala_overheat_dipilih()
    {
        $this->actingAs($this->mekanik);


        $gejalaIds = Gejala::whereIn('kode_gejala', ['G001', 'G002', 'G003'])->pluck('id')->toArray();

        $jawaban = [];
        foreach ($gejalaIds as $id) {
            $jawaban[$id] = 'ya';
        }

        $response = $this->post('/mekanik/diagnosa', ['jawaban' => $jawaban]);

        $response->assertStatus(200);

        $hasil = $response->viewData('hasil');
        $this->assertNotEmpty($hasil);
        $this->assertEquals('Mesin Overheat', $hasil[0]->kerusakan->nama_kerusakan);
        $this->assertEquals(100, $hasil[0]->persentase);
    }

    #[Test]
    public function dua_gejala_overheat_tanpa_g003()
    {
        $this->actingAs($this->mekanik);


        $gejalaIds = Gejala::whereIn('kode_gejala', ['G001', 'G002'])->pluck('id')->toArray();

        $jawaban = [];
        foreach ($gejalaIds as $id) {
            $jawaban[$id] = 'ya';
        }

        $response = $this->post('/mekanik/diagnosa', ['jawaban' => $jawaban]);

        $response->assertStatus(200);

        $hasil = $response->viewData('hasil');
        $this->assertNotEmpty($hasil);
        $this->assertEquals('Mesin Overheat', $hasil[0]->kerusakan->nama_kerusakan);
        $this->assertEquals(67, $hasil[0]->persentase);
    }

    #[Test]
    public function dua_gejala_overheat_tanpa_g002()
    {
        $this->actingAs($this->mekanik);


        $gejalaIds = Gejala::whereIn('kode_gejala', ['G001', 'G003'])->pluck('id')->toArray();

        $jawaban = [];
        foreach ($gejalaIds as $id) {
            $jawaban[$id] = 'ya';
        }

        $response = $this->post('/mekanik/diagnosa', ['jawaban' => $jawaban]);

        $response->assertStatus(200);

        $hasil = $response->viewData('hasil');
        $this->assertNotEmpty($hasil);
        $this->assertEquals('Mesin Overheat', $hasil[0]->kerusakan->nama_kerusakan);
        $this->assertEquals(67, $hasil[0]->persentase);
    }

    #[Test]
    public function satu_gejala_overheat_hanya_g001()
    {
        $this->actingAs($this->mekanik);


        $gejalaId = Gejala::where('kode_gejala', 'G001')->first()->id;

        $jawaban = [$gejalaId => 'ya'];

        $response = $this->post('/mekanik/diagnosa', ['jawaban' => $jawaban]);

        $response->assertStatus(200);

        $hasil = $response->viewData('hasil');


        $this->assertNotEmpty($hasil);
        $this->assertEquals('Mesin Overheat', $hasil[0]->kerusakan->nama_kerusakan);
        $this->assertEquals(33, $hasil[0]->persentase);
    }

    #[Test]
    public function tidak_ada_gejala_overheat()
    {
        $this->actingAs($this->mekanik);

        $response = $this->post('/mekanik/diagnosa', ['jawaban' => []]);

        $response->assertStatus(200);

        $hasil = $response->viewData('hasil');
        $this->assertEmpty($hasil);
    }
}
