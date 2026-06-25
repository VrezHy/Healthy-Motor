<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kerusakan;
use App\Models\Gejala;
use App\Models\Solusi;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'username' => 'halo.admin',
            'password' => 'Rby39Au<3i^<',
            'role' => 'admin',
        ]);

        $this->call(UserSeeder::class);


        $data = [
            [
                'nama_kerusakan' => 'Mesin Overheat',
                'gejalas' => [
                    ['kode' => 'G001', 'nama' => 'Temperatur mesin naik drastis'],
                    ['kode' => 'G002', 'nama' => 'Keluar asap dari mesin'],
                    ['kode' => 'G003', 'nama' => 'Air radiator berkurang cepat'],
                ],
                'solusies' => [
                    ['nama' => 'Periksa dan isi air radiator', 'deskripsi' => 'Pastikan air radiator selalu dalam kondisi penuh dan tidak bocor.'],
                    ['nama' => 'Ganti thermostat', 'deskripsi' => 'Thermostat yang rusak dapat menyebabkan sirkulasi air terhambat.'],
                ],
            ],
            [
                'nama_kerusakan' => 'Aki Lemah',
                'gejalas' => [
                    ['kode' => 'G004', 'nama' => 'Motor sulit dihidupkan'],
                    ['kode' => 'G005', 'nama' => 'Lampu redup saat mesin mati'],
                    ['kode' => 'G006', 'nama' => 'Klakson lemah'],
                ],
                'solusies' => [
                    ['nama' => 'Charge aki', 'deskripsi' => 'Lakukan pengisian daya aki menggunakan charger.'],
                    ['nama' => 'Ganti aki baru', 'deskripsi' => 'Jika aki sudah tidak bisa menyimpan daya, segera ganti aki baru.'],
                ],
            ],
            [
                'nama_kerusakan' => 'Karburator Kotor',
                'gejalas' => [
                    ['kode' => 'G007', 'nama' => 'Mesin tersendat-sendat'],
                    ['kode' => 'G008', 'nama' => 'Boros bahan bakar'],
                    ['kode' => 'G009', 'nama' => 'Asap knalpot hitam'],
                ],
                'solusies' => [
                    ['nama' => 'Bersihkan karburator', 'deskripsi' => 'Bongkar dan bersihkan karburator dari kotoran dan endapan bahan bakar.'],
                    ['nama' => 'Setel ulang karburator', 'deskripsi' => 'Lakukan penyetelan ulang karburator sesuai spesifikasi motor.'],
                ],
            ],
        ];

        foreach ($data as $item) {
            $kerusakan = Kerusakan::create(['nama_kerusakan' => $item['nama_kerusakan']]);

            foreach ($item['gejalas'] as $g) {
                Gejala::create([
                    'kode_gejala'  => $g['kode'],
                    'nama_gejala'  => $g['nama'],
                    'kerusakan_id' => $kerusakan->id,
                ]);
            }

            foreach ($item['solusies'] as $s) {
                Solusi::create([
                    'nama_solusi'  => $s['nama'],
                    'deskripsi'    => $s['deskripsi'],
                    'kerusakan_id' => $kerusakan->id,
                ]);
            }
        }
    }
}
