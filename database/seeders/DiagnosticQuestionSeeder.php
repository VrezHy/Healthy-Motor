<?php

namespace Database\Seeders;

// database/seeders/DiagnosticQuestionSeeder.php

use App\Models\DiagnosticQuestion;
use App\Models\DiagnosticOption;

class DiagnosticQuestionSeeder extends DatabaseSeeder
{
    public function run(): void
    {
        $questions = [
            [
                'question_key' => 'merek',
                'title'        => 'Pilih Merek\nKendaraan',
                'layout'       => 'grid',
                'scrollable'   => false,
                'order'        => 1,
                'options'      => [
                    ['value' => 'yamaha',   'label' => 'Yamaha'],
                    ['value' => 'honda',    'label' => 'Honda'],
                    ['value' => 'suzuki',   'label' => 'Suzuki'],
                    ['value' => 'kawasaki', 'label' => 'Kawasaki'],
                    ['value' => 'vespa',    'label' => 'Vespa'],
                    ['value' => 'tvs',      'label' => 'TVS'],
                    ['value' => 'bajaj',    'label' => 'Bajaj'],
                    ['value' => 'lainnya',  'label' => 'Lainnya'],
                ],
            ],
            [
                'question_key' => 'jenis',
                'title'        => 'Jenis Motor',
                'layout'       => 'list',
                'scrollable'   => false,
                'order'        => 2,
                'options'      => [
                    ['value' => 'bebek', 'label' => 'Motor Bebek (Supra, Vega, dll)'],
                    ['value' => 'matic', 'label' => 'Motor Matic (Vario, Mio, dll)'],
                    ['value' => 'sport', 'label' => 'Motor Sport (CBR, R15, dll)'],
                    ['value' => 'trail', 'label' => 'Motor Trail / Off-road'],
                ],
            ],
            [
                'question_key' => 'keluhan',
                'title'        => 'Keluhan Utama',
                'subtitle'     => 'Pilih keluhan yang paling dominan',
                'layout'       => 'list',
                'scrollable'   => true,
                'order'        => 3,
                'options'      => [
                    ['value' => 'mesin_mati',    'label' => 'Mesin tidak mau hidup'],
                    ['value' => 'mati_mendadak', 'label' => 'Mesin mati tiba-tiba saat jalan'],
                    ['value' => 'brebet',        'label' => 'Motor brebet / tersendat'],
                    ['value' => 'suara_aneh',    'label' => 'Suara mesin tidak normal'],
                    ['value' => 'boros_bbm',     'label' => 'Boros bahan bakar'],
                    ['value' => 'asap_knalpot',  'label' => 'Asap knalpot berlebih'],
                    ['value' => 'rem_blong',     'label' => 'Rem tidak pakem / blong'],
                    ['value' => 'kelistrikan',   'label' => 'Masalah kelistrikan (lampu, aki)'],
                    ['value' => 'getaran',       'label' => 'Getaran berlebih'],
                    ['value' => 'overheat',      'label' => 'Mesin cepat panas'],
                ],
            ],
            [
                'question_key' => 'kapan',
                'title'        => 'Kapan Keluhan\nMuncul?',
                'layout'       => 'list',
                'scrollable'   => false,
                'order'        => 4,
                'options'      => [
                    ['value' => 'start',    'label' => 'Saat pertama dinyalakan'],
                    ['value' => 'jalan',    'label' => 'Saat sedang berkendara'],
                    ['value' => 'berhenti', 'label' => 'Saat berhenti / idle'],
                    ['value' => 'selalu',   'label' => 'Sepanjang waktu'],
                ],
            ],
            [
                'question_key' => 'durasi',
                'title'        => 'Sudah Berapa\nLama Keluhan Ini?',
                'layout'       => 'list',
                'scrollable'   => false,
                'order'        => 5,
                'options'      => [
                    ['value' => 'kurang1hari',  'label' => 'Kurang dari 1 hari'],
                    ['value' => '1_7hari',      'label' => '1 – 7 hari'],
                    ['value' => '1_4minggu',    'label' => '1 – 4 minggu'],
                    ['value' => 'lebih1bulan',  'label' => 'Lebih dari 1 bulan'],
                ],
            ],
            [
                'question_key' => 'suara',
                'title'        => 'Apakah Ada\nSuara Aneh?',
                'layout'       => 'list',
                'scrollable'   => false,
                'order'        => 6,
                'options'      => [
                    ['value' => 'ketukan',   'label' => 'Suara ketukan (tok-tok)'],
                    ['value' => 'decit',     'label' => 'Suara berdecit / gesekan'],
                    ['value' => 'gemuruh',   'label' => 'Suara gemuruh / dengung'],
                    ['value' => 'letupan',   'label' => 'Suara letupan dari knalpot'],
                    ['value' => 'tidak_ada', 'label' => 'Tidak ada suara aneh'],
                ],
            ],
            [
                'question_key' => 'oli',
                'title'        => 'Kondisi Oli\nMesin?',
                'layout'       => 'list',
                'scrollable'   => false,
                'order'        => 7,
                'options'      => [
                    ['value' => 'baru',       'label' => 'Baru diganti (< 1 bulan)'],
                    ['value' => 'waktunya',   'label' => 'Sudah waktunya ganti'],
                    ['value' => 'berkurang',  'label' => 'Oli berkurang drastis'],
                    ['value' => 'hitam',      'label' => 'Oli sangat hitam & kental'],
                    ['value' => 'belum_cek',  'label' => 'Belum pernah dicek'],
                ],
            ],
            [
                'question_key' => 'servis',
                'title'        => 'Kapan Terakhir\nServis?',
                'layout'       => 'list',
                'scrollable'   => false,
                'order'        => 8,
                'options'      => [
                    ['value' => 'lt1bulan',      'label' => 'Kurang dari 1 bulan lalu'],
                    ['value' => '1_3bulan',      'label' => '1 – 3 bulan lalu'],
                    ['value' => '3_6bulan',      'label' => '3 – 6 bulan lalu'],
                    ['value' => 'gt6bulan',      'label' => 'Lebih dari 6 bulan'],
                    ['value' => 'belum_pernah',  'label' => 'Belum pernah servis'],
                ],
            ],
            [
                'question_key' => 'bbm',
                'title'        => 'Kondisi Bahan\nBakar?',
                'layout'       => 'list',
                'scrollable'   => false,
                'order'        => 9,
                'options'      => [
                    ['value' => 'penuh',       'label' => 'Tangki penuh / baru isi'],
                    ['value' => 'hampir_habis','label' => 'Hampir habis'],
                    ['value' => 'pertamax',    'label' => 'Menggunakan Pertamax / Ron 92+'],
                    ['value' => 'pertalite',   'label' => 'Menggunakan Pertalite / Premium'],
                    ['value' => 'campur',      'label' => 'Kadang campur jenis BBM'],
                ],
            ],
        ];

        foreach ($questions as $index => $qData) {
            $options = $qData['options'];
            unset($qData['options']);

            $question = DiagnosticQuestion::create($qData);

            foreach ($options as $i => $opt) {
                DiagnosticOption::create([
                    'question_id' => $question->id,
                    'value'       => $opt['value'],
                    'label'       => $opt['label'],
                    'order'       => $i + 1,
                ]);
            }
        }
    }
}