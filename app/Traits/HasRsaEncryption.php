<?php

namespace App\Traits;

trait HasRSAEncryption
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

    public function rsaEncrypt($plaintext)
    {
        $this->ensureRSAKeysExist();

        $publicKey = file_get_contents(storage_path('app/public.pem'));
        openssl_public_encrypt($plaintext, $encrypted, $publicKey);
        return base64_encode($encrypted);
    }

    public function rsaDecrypt($encryptedText)
    {
        $this->ensureRSAKeysExist();

        $privateKey = file_get_contents(storage_path('app/private.pem'));
        openssl_private_decrypt(base64_decode($encryptedText), $decrypted, $privateKey);
        return $decrypted;
    }
}
