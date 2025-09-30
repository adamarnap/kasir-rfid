<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Support\SimpleRSA;

class RSATest extends TestCase
{
    public function test_encrypt_matches_expected(): void
    {
        $plaintext = [
            'Hallo nama saya adalah Budi',
            'Transaksi beng-beng',
            'Rp. 2000',
            'Total transaksi = Rp. 10.0000',
            'Aplikasi kasir RFID',
            'Implementasi RSA dengan manual key',
            // dst... (silahkan tambah sendiri plaintext yang akan dienkripsi dan enkripsi sendiri menggunakan laravel Command rsa:simple encrypt)
        ];

        $encrypted = [
            // Hasil dari proses enkripsi dengan laravel Command rsa:simple encrypt
            // dengan kunci menentukan sendiri (n,d,e)
            "71:33:368:368:175:280:189:33:190:33:280:106:33:69:33:280:33:360:33:368:33:52:280:157:260:360:79",
            "137:270:33:189:106:33:276:106:79:280:253:140:189:350:59:253:140:189:350",
            "134:200:58:280:262:282:282:282",
            "137:175:77:33:368:280:77:270:33:189:106:33:276:106:79:280:61:280:134:200:58:280:361:282:58:282:282:282:282",
            "117:200:368:79:276:33:106:79:280:276:33:106:79:270:280:134:47:83:68",
            "83:190:200:368:140:190:140:189:77:33:106:79:280:134:177:117:280:360:140:189:350:33:189:280:190:33:189:260:33:368:280:276:140:69",
            // dst ... (silahkan tambah sendiri ciphertext dari proses enkripsi menggunakan laravel Command rsa:simple encrypt)
        ];

        $keys = [
            // [n, d, e] 
            [403, 7, 103],
            [403, 7, 103],
            [403, 7, 103],
            [403, 7, 103],
            [403, 7, 103],
            [403, 7, 103],
            // dst: boleh variasi n,d,e untuk setiap kasus (SESUAIKAN dengan key yang ditentukan saat proses enkripsi dengan Command rsa:simple encrypt)
        ];

        $benar = 0;
        for ($i = 0; $i < count($keys); $i++) {
            $n = $keys[$i][0];
            $d = $keys[$i][1];
            $e = $keys[$i][2];
            $rsa = new SimpleRSA($n, $d, $e);

            if ($rsa->encrypt($plaintext[$i]) === $encrypted[$i]) {
                $benar++;
            }
        }

        echo "Jumlah Testing data: " . count($keys) . "\n";
        echo "Akurasi: " . (($benar / count($keys)) * 100) . "%\n";

        $this->assertTrue($benar === count($keys), "RSA Benar Semua.");
    }

    public function test_decrypt_roundtrip(): void
    {
        $rsa = new SimpleRSA(403, 7, 103);
        $plain = "Uji per byte";
        $cipher = $rsa->encrypt($plain);
        $this->assertSame($plain, $rsa->decrypt($cipher));
    }
}
