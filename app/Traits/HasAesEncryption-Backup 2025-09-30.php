<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

trait HasAesEncryption
{
    /**
     * Enkripsi nilai menggunakan AES-256-CBC
     *
     * @param string $value
     * @return string
     */
    public function aesEncrypt(string $value): string
    {
        return Crypt::encryptString($value);
    }

    /**
     * Dekripsi nilai terenkripsi
     *
     * @param string $encryptedValue
     * @return string|null
     */
    public function aesDecrypt(string $encryptedValue): ?string
    {
        try {
            return Crypt::decryptString($encryptedValue);
        } catch (DecryptException $e) {
            report($e); // Log error
            return null;
        }
    }
}
