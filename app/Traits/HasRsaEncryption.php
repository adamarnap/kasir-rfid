<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

trait HasRsaEncryption
{
    public function rsaEncrypt($plaintext)
    {
        $publicKey = file_get_contents(storage_path('app/public.pem'));
        openssl_public_encrypt($plaintext, $encrypted, $publicKey);
        return base64_encode($encrypted);
    }

    public function rsaDecrypt($encryptedText)
    {
        $privateKey = file_get_contents(storage_path('app/private.pem'));
        openssl_private_decrypt(base64_decode($encryptedText), $decrypted, $privateKey);
        return $decrypted;
    }
}
