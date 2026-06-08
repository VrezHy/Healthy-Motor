<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Solusi;
use App\Models\Kerusakan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Attributes\Test;

class SolusiTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $kerusakanOverheat;
    private $kerusakanAki;
    private $kerusakanKarburator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Admin',
            'username' => 'admin1',
            'password' => Hash::make('A123456.'),
            'role' => 'admin',
        ]);

        $this->kerusakanOverheat = Kerusakan::create([
            'nama_kerusakan' => 'Mesin Overheat',
        ]);

        $this->kerusakanAki = Kerusakan::create([
            'nama_kerusakan' => 'Aki Lemah',
        ]);

        $this->kerusakanKarburator = Kerusakan::create([
            'nama_kerusakan' => 'Karburator Kotor',
        ]);
    }

    #[Test]
    public function halaman_solusi_bisa_dibuka()
    {
        $this->actingAs($this->admin);

        Solusi::create([
            'kerusakan_id' => $this->kerusakanOverheat->id,
            'nama_solusi' => 'Periksa radiator',
            'deskripsi' => 'Cek air radiator dan pastikan tidak bocor.',
        ]);

        $response = $this->get(route('admin.solusi'));

        $response->assertStatus(200);
        $response->assertSee('Tabel Data Solusi');
        $response->assertSee('Periksa radiator');
        $response->assertSee('Mesin Overheat');
    }

    #[Test]
    public function admin_bisa_menambahkan_tiga_solusi()
    {
        $this->actingAs($this->admin);

        $dataSolusi = [
            [
                'kerusakan_id' => $this->kerusakanOverheat->id,
                'nama_solusi' => 'Isi air radiator',
                'deskripsi' => 'Tambahkan air radiator sampai batas normal.',
            ],
            [
                'kerusakan_id' => $this->kerusakanAki->id,
                'nama_solusi' => 'Charge aki',
                'deskripsi' => 'Lakukan pengisian daya aki.',
            ],
            [
                'kerusakan_id' => $this->kerusakanKarburator->id,
                'nama_solusi' => 'Bersihkan karburator',
                'deskripsi' => 'Bongkar dan bersihkan karburator dari kotoran.',
            ],
        ];

        foreach ($dataSolusi as $solusi) {
            $response = $this->post(route('admin.solusi.store'), $solusi);

            $response->assertRedirect(route('admin.solusi'));

            $this->assertDatabaseHas((new Solusi)->getTable(), [
                'kerusakan_id' => $solusi['kerusakan_id'],
                'nama_solusi' => $solusi['nama_solusi'],
                'deskripsi' => $solusi['deskripsi'],
            ]);
        }

        $this->assertEquals(3, Solusi::count());
    }

    #[Test]
    public function admin_bisa_mengubah_solusi()
    {
        $this->actingAs($this->admin);

        $solusi = Solusi::create([
            'kerusakan_id' => $this->kerusakanOverheat->id,
            'nama_solusi' => 'Periksa radiator',
            'deskripsi' => 'Deskripsi lama.',
        ]);

        $response = $this->put(route('admin.solusi.update', $solusi->id), [
            'kerusakan_id' => $this->kerusakanAki->id,
            'nama_solusi' => 'Ganti aki baru',
            'deskripsi' => 'Jika aki sudah rusak, ganti dengan aki baru.',
        ]);

        $response->assertRedirect(route('admin.solusi'));

        $this->assertDatabaseHas((new Solusi)->getTable(), [
            'id' => $solusi->id,
            'kerusakan_id' => $this->kerusakanAki->id,
            'nama_solusi' => 'Ganti aki baru',
            'deskripsi' => 'Jika aki sudah rusak, ganti dengan aki baru.',
        ]);
    }

    #[Test]
    public function admin_bisa_menghapus_solusi()
    {
        $this->actingAs($this->admin);

        $solusi = Solusi::create([
            'kerusakan_id' => $this->kerusakanOverheat->id,
            'nama_solusi' => 'Periksa radiator',
            'deskripsi' => 'Cek air radiator.',
        ]);

        $response = $this->delete(route('admin.solusi.destroy', $solusi->id));

        $response->assertRedirect(route('admin.solusi'));

        $this->assertDatabaseMissing((new Solusi)->getTable(), [
            'id' => $solusi->id,
            'nama_solusi' => 'Periksa radiator',
        ]);
    }
}
