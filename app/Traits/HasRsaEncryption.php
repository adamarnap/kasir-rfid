<?php

namespace App\Traits;

trait HasRsaEncryption
{
    protected function ensureRSAKeysExist()
    {
        $privatePath = storage_path('app/private.pem');
        $publicPath = storage_path('app/public.pem');

        if (!file_exists($privatePath) || !file_exists($publicPath)) {
            $res = openssl_pkey_new([
                "private_key_bits" => 2048,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            ]);

            // Simpan private key
            openssl_pkey_export($res, $privateKey);
            file_put_contents($privatePath, $privateKey);

            // Ambil public key
            $keyDetails = openssl_pkey_get_details($res);
            file_put_contents($publicPath, $keyDetails["key"]);
        }
    }

    /**
     * Melakukan enkripsi RSA terhadap teks biasa (plaintext).
     * Output akan dikonversi menjadi angka desimal byte dan dipisahkan dengan tanda ":".
     * Contoh output: 231:33:1:89:...
     *
     * @param string $plaintext
     * @return string
     */
    // public function rsaEncrypt($plaintext)
    // {
    //     // Pastikan kunci tersedia
    //     $this->ensureRSAKeysExist();

    //     // Ambil public key
    //     $publicKey = file_get_contents(storage_path('app/public.pem'));

    //     // Enkripsi plaintext menggunakan public key
    //     openssl_public_encrypt($plaintext, $encrypted, $publicKey);

    //     // Konversi hasil enkripsi biner ke bentuk angka-angka byte, dipisah ":"
    //     $byteArray = unpack('C*', $encrypted); // Ubah ke array byte
    //     return implode(':', $byteArray);       // Gabungkan byte menjadi string angka
    // }

    public function rsaEncrypt($plaintext)
    {
        // Pastikan key RSA sudah dibuat
        $this->ensureRSAKeysExist();

        // Ambil public key dari file
        $publicKey = file_get_contents(storage_path('app/public.pem'));

        // Enkripsi teks menggunakan public key
        openssl_public_encrypt($plaintext, $encrypted, $publicKey);

        // Ubah data biner hasil enkripsi menjadi array byte
        $byteArray = unpack('C*', $encrypted);

        // Gabungkan tiap byte menjadi string angka dengan delimiter ':'
        return implode(':', $byteArray);
    }

    /**
     * Melakukan dekripsi RSA terhadap string terenkripsi dalam bentuk angka (231:33:1:...)
     *
     * @param string $encryptedText
     * @return string|null
     */
    public function rsaDecrypt($encryptedText)
    {
        // Pastikan kunci tersedia
        $this->ensureRSAKeysExist();

        // Ambil private key
        $privateKey = file_get_contents(storage_path('app/private.pem'));

        // Konversi string angka (byte) ke format biner untuk dekripsi
        $byteArray = explode(':', $encryptedText);     // Pisah menjadi array angka
        $binaryData = pack('C*', ...$byteArray);       // Ubah angka ke format biner

        // Dekripsi menggunakan private key
        $success = openssl_private_decrypt($binaryData, $decrypted, $privateKey);

        // Kembalikan hasil jika berhasil, atau null jika gagal
        return $success ? $decrypted : null;
    }
}
