<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    /**
     * Tampilkan form aktivasi.
     */
    public function showActivateForm(Request $request)
    {
        $currentKey = env('APP_LICENSE_KEY');
        $license = $currentKey ? License::where('license_key', $currentKey)->first() : null;

        return view('license.activate', [
            'currentKey' => $currentKey,
            'license'    => $license,
        ]);
    }

    /**
     * Proses aktivasi / ganti license key. 
     */
    public function activate(Request $request)
    {
        $request->validate([
            'license_key' => 'required|string|max:100',
        ]);

        $key = trim($request->license_key);
        $license = License::where('license_key', $key)->first();

        if (!$license) {
            return back()->withErrors(['license_key' => 'License key tidak ditemukan.'])->withInput();
        }
        if (!$license->is_active) {
            return back()->withErrors(['license_key' => 'License key dinonaktifkan.'])->withInput();
        }
        if ($license->expires_at && $license->expires_at->isPast()) {
            return back()->withErrors(['license_key' => 'License key sudah kedaluwarsa.'])->withInput();
        }

        // Tulis ke .env
        $this->putEnvValue('APP_LICENSE_KEY', $key);

        // (Opsional) bersihkan cache config jika memakai config:cache
        try {
            Artisan::call('config:clear');
        } catch (Throwable $e) {
            Log::warning('Gagal clear config cache: ' . $e->getMessage());
        }

        return redirect('/')->with('success', 'Lisensi berhasil diaktifkan.');
    }

    /**
     * Menonaktifkan lisensi (opsional). Menghapus dari .env tapi tidak menghapus di DB.
     */
    public function deactivate(Request $request)
    {
        $currentKey = env('APP_LICENSE_KEY');
        if (!$currentKey) {
            return back()->with('info', 'Tidak ada license yang sedang aktif.');
        }
        $this->putEnvValue('APP_LICENSE_KEY', '');
        try {
            Artisan::call('config:clear');
        } catch (Throwable $e) {}
        return redirect()->route('license.activate.form')->with('success', 'Lisensi dihapus dari konfigurasi.');
    }

    /**
     * Debug status lisensi (hanya untuk internal; bisa dihapus saat produksi).
     */
    public function status()
    {
        $key = env('APP_LICENSE_KEY');
        $license = $key ? License::where('license_key', $key)->first() : null;

        return response()->json([
            'env_key'   => $key,
            'found_in_db' => (bool)$license,
            'is_active' => $license?->is_active,
            'expires_at'=> $license?->expires_at?->toDateTimeString(),
            'expired'   => $license?->expires_at ? $license->expires_at->isPast() : null,
        ]);
    }

    /**
     * Utility menulis / update value di file .env
     */
    protected function putEnvValue(string $key, string $value): void
    {
        $path = base_path('.env');
        if (!file_exists($path)) {
            return; // atau lempar exception jika perlu
        }

        $escaped = preg_quote($key, '/');
        $env = file_get_contents($path);

        if (preg_match("/^{$escaped}=.*$/m", $env)) {
            if ($value === '') {
                // Hapus baris atau set kosong
                $env = preg_replace("/^{$escaped}=.*$/m", "{$key}=", $env);
            } else {
                $env = preg_replace("/^{$escaped}=.*$/m", "{$key}={$value}", $env);
            }
        } else {
            $env .= "\n{$key}={$value}";
        }

        file_put_contents($path, $env);
    }
}

