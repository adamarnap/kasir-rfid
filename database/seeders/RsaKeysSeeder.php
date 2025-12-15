<?php

namespace Database\Seeders;

use App\Models\Preference;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RsaKeysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nilai default RSA keys (contoh kunci kecil untuk demo)
        // Untuk produksi, gunakan kunci yang lebih besar
        $rsaKeys = [
            [
                'name' => 'rsa_n',
                'group' => 'encryption',
                'value' => '403', // n = p * q (contoh: 61 * 53)
            ],
            [
                'name' => 'rsa_d',
                'group' => 'encryption',
                'value' => '103', // private exponent
            ],
            [
                'name' => 'rsa_e',
                'group' => 'encryption',
                'value' => '7', // public exponent
            ],
        ];

        foreach ($rsaKeys as $key) {
            Preference::updateOrCreate(
                ['name' => $key['name']],
                $key
            );
        }
    }
}
