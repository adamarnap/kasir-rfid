<?php

namespace App\Support;

class SimpleRSA
{
    /** @var int|string bigints ok (gmp) */
    protected $n;
    protected $d;
    protected $e;

    public function __construct(int|string $n, int|string $d, int|string $e)
    {
        $this->n = $n;
        $this->d = $d;
        $this->e = $e;
    }

    /** Terima format "n:d:e" */
    public static function fromString(string $triple): self
    {
        [$n, $d, $e] = array_map('trim', explode(':', $triple));
        return new self($n, $d, $e);
    }

    /** Fast modular exponentiation; gunakan GMP bila ada. */
    protected function powmod(int|string $base, int|string $exp, int|string $mod): int|string
    {
        if (function_exists('gmp_powm')) {
            return gmp_strval(gmp_powm($base, $exp, $mod));
        }

        // fallback integer/BCMath manual
        if (function_exists('bcmod')) {
            $result = '1';
            $base   = bcmod((string)$base, (string)$mod);
            $e      = (string)$exp;
            while (bccomp($e, '0') === 1) {
                if ((int)bcmod($e, '2') === 1) {
                    $result = bcmod(bcmul($result, $base), (string)$mod);
                }
                $e    = bcdiv($e, '2', 0);
                $base = bcmod(bcmul($base, $base), (string)$mod);
            }
            return (string)$result;
        }

        // simple int fast exp (cukup utk n kecil, mis. < PHP_INT_MAX)
        $result = 1;
        $base   = (int)$base % (int)$mod;
        $exp    = (int)$exp;
        $mod    = (int)$mod;

        while ($exp > 0) {
            if ($exp & 1) {
                $result = ($result * $base) % $mod;
            }
            $base = ($base * $base) % $mod;
            $exp >>= 1;
        }
        return $result;
    }

    /** Enkripsi per-byte; output "c0:c1:...". */
    public function encrypt(string $plaintext): string
    {
        // pakai bytes UTF-8 apa adanya
        $bytes = unpack('C*', $plaintext);
        $out   = [];
        foreach ($bytes as $m) {
            $c = $this->powmod($m, $this->e, $this->n);
            $out[] = (string)$c;
        }
        return implode(':', $out);
    }

    /** Dekripsi dari "c0:c1:..." → string. */
    public function decrypt(string $cipherColon): string
    {
        $parts = array_filter(explode(':', trim($cipherColon)), fn($v) => $v !== '');
        $chars = [];
        foreach ($parts as $c) {
            $m = (int)$this->powmod($c, $this->d, $this->n);
            // pastikan 0<=m<=255 agar valid byte
            if ($m < 0 || $m > 255) {
                throw new \RuntimeException("Nilai m=$m di luar rentang byte (cek kunci atau data).");
            }
            $chars[] = chr($m);
        }
        return implode('', $chars);
    }
}
