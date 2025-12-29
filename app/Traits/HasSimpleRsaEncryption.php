<?php

namespace App\Traits;

use App\Models\Preference;
use App\Support\SimpleRSA;

trait HasSimpleRsaEncryption
{
    /**
     * Get SimpleRSA instance from database keys
     */
    protected function getSimpleRSA(): SimpleRSA
    {
        $n = Preference::where('name', 'rsa_n')->value('value');
        $d = Preference::where('name', 'rsa_d')->value('value');
        $e = Preference::where('name', 'rsa_e')->value('value');

        if (!$n || !$d || !$e) {
            throw new \RuntimeException('RSA keys not found in database. Please configure encryption settings.');
        }

        return new SimpleRSA($n, $d, $e);
    }

    /**
     * Encrypt using SimpleRSA
     */
    protected function simpleRsaEncrypt(?string $plaintext): ?string
    {
        if ($plaintext === null || $plaintext === '') {
            return $plaintext;
        }
        return $this->getSimpleRSA()->encrypt($plaintext);
    }

    /**
     * Decrypt using SimpleRSA
     */
    protected function simpleRsaDecrypt(?string $ciphertext): ?string
    {
        if ($ciphertext === null || $ciphertext === '') {
            return $ciphertext;
        }
        
        try {
            // Check if the ciphertext looks like encrypted data (should contain colons and numbers)
            if (!str_contains($ciphertext, ':') || !preg_match('/^\d+(:\d+)*$/', trim($ciphertext))) {
                // If it doesn't look like encrypted data, return it as-is
                \Log::warning("Data tidak terenkripsi ditemukan", [
                    'model' => get_class($this),
                    'value' => substr($ciphertext, 0, 50) . '...'
                ]);
                return $ciphertext;
            }
            
            return $this->getSimpleRSA()->decrypt($ciphertext);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error("RSA Decryption Error", [
                'error' => $e->getMessage(),
                'model' => get_class($this),
                'ciphertext' => substr($ciphertext, 0, 100) . '...'
            ]);
            // If decryption fails, return original value
            return $ciphertext;
        }
    }
}
