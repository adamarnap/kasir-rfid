<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Support\SimpleRSA;

class RsaSimple extends Command
{
    protected $signature = 'rsa:simple
        {mode : encrypt|decrypt}
        {--n= : modulus n}
        {--d= : private exponent d}
        {--e= : public exponent e}
        {--key= : alternatif, format "n:d:e"}
        {--text= : plaintext utk encrypt}
        {--cipher= : ciphertext colon-bytes utk decrypt}';

    protected $description = 'Uji RSA textbook per-byte (tanpa padding) dengan kunci n:d:e.';

    public function handle(): int
    {
        $mode = strtolower($this->argument('mode'));

        $keyStr = $this->option('key');
        if ($keyStr) {
            $rsa = SimpleRSA::fromString($keyStr);
        } else {
            $n = $this->option('n');
            $d = $this->option('d');
            $e = $this->option('e');
            if ($n === null || $d === null || $e === null) {
                $this->error('Isi --key="n:d:e" ATAU lengkap --n --d --e.');
                return 1;
            }
            $rsa = new SimpleRSA($n, $d, $e);
        }

        try {
            if ($mode === 'encrypt') {
                $text = $this->option('text') ?? $this->ask('Masukkan plaintext');
                $cipher = $rsa->encrypt($text);
                $this->line('Cipher (colon bytes):');
                $this->line($cipher);
                return 0;
            }

            if ($mode === 'decrypt') {
                $cipher = $this->option('cipher') ?? $this->ask('Masukkan ciphertext (colon bytes)');
                $plain  = $rsa->decrypt($cipher);
                $this->line('Plaintext:');
                $this->line($plain);
                return 0;
            }

            $this->error('Mode tidak dikenal (pakai encrypt|decrypt).');
            return 1;

        } catch (\Throwable $e) {
            $this->error('ERROR: '.$e->getMessage());
            return 1;
        }
    }
}
