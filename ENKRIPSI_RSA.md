# Dokumentasi Fitur Enkripsi RSA untuk Transaksi

## Deskripsi
Fitur ini memungkinkan pengguna (kasir/admin) untuk mengatur kunci enkripsi RSA yang digunakan untuk mengenkripsi data transaksi. Kunci RSA terdiri dari tiga komponen:
- **n (Modulus)**: Hasil perkalian dua bilangan prima (p × q)
- **d (Private Exponent)**: Kunci privat untuk dekripsi
- **e (Public Exponent)**: Kunci publik untuk enkripsi

**⚠️ PENTING**: Sistem ini menggunakan **SimpleRSA** (custom implementation) untuk enkripsi data transaksi, bukan OpenSSL RSA. Semua data di tabel `transactions` dan `transaction_items` dienkripsi menggunakan SimpleRSA dengan kunci yang dapat dikonfigurasi melalui database.

## Data yang Dienkripsi

### Tabel `transactions`
- `total_amount`: Total harga transaksi (dienkripsi dengan SimpleRSA)

### Tabel `transaction_items`
- `product_price`: Harga produk per item (dienkripsi dengan SimpleRSA)

## Implementasi

### 1. Database
Kunci RSA disimpan di tabel `preferences` dengan struktur:
- `name`: nama kunci (rsa_n, rsa_d, rsa_e)
- `group`: grup 'encryption'
- `value`: nilai kunci

### 2. Seeder
File: `database/seeders/RsaKeysSeeder.php`

Untuk menjalankan seeder:
```bash
php artisan db:seed --class=RsaKeysSeeder
```

Nilai default yang digunakan (contoh untuk demo):
- n = 403 (61 × 53)
- d = 103
- e = 17

**Catatan**: Untuk produksi, gunakan nilai kunci yang lebih besar untuk keamanan yang lebih baik.

### 3. Class SimpleRSA
File: `app/Support/SimpleRSA.php`

Class ini menyediakan method:
- `encrypt(string $plaintext)`: Enkripsi teks biasa
- `decrypt(string $ciphertext)`: Dekripsi teks terenkripsi
- `fromString(string $triple)`: Membuat instance dari format "n:d:e"

### 4. TransactionsService
File: `app/Http/Services/TransactionsService.php`

Method yang ditambahkan:
- `getSimpleRSA()`: Mengambil instance SimpleRSA dari database
- `simpleRsaEncrypt($plaintext)`: Enkripsi menggunakan SimpleRSA
- `simpleRsaDecrypt($ciphertext)`: Dekripsi menggunakan SimpleRSA
- `getRsaSettings()`: Mengambil pengaturan RSA
- `updateRsaSettings($data)`: Update pengaturan RSA

### 5. Controller
File: `app/Http/Controllers/TransactionsController.php`

Endpoint yang ditambahkan:
- `GET /transactions-rsa-settings`: Mengambil pengaturan RSA (JSON)
- `POST /transactions-rsa-settings`: Update pengaturan RSA (JSON)

### 6. View & Modal
File: `resources/views/transactions/index.blade.php`

Tombol "Pengaturan Enkripsi RSA" ditambahkan di bagian atas halaman transaksi.

File: `resources/views/transactions/partials/modal-rsa-settings.blade.php`

Modal untuk mengatur kunci RSA dengan form:
- Input untuk nilai n
- Input untuk nilai d
- Input untuk nilai e
- Peringatan tentang perubahan kunci

### 7. Helper Functions
File: `app/Helpers/Helpers.php`

Function yang ditambahkan:
- `simple_rsa_encrypt($plaintext)`: Helper untuk enkripsi dengan SimpleRSA
- `simple_rsa_decrypt($ciphertext)`: Helper untuk dekripsi dengan SimpleRSA

Dapat digunakan di Blade view:
```blade
{{ simple_rsa_encrypt('100000') }}
{{ simple_rsa_decrypt($encrypted_data) }}
```

**Catatan**: Function `rsa_decrypt()` yang lama masih tersedia (menggunakan OpenSSL), namun untuk data transaksi baru, gunakan `simple_rsa_decrypt()`.

### 8. Routes
File: `routes/web.php`

```php
Route::get('transactions-rsa-settings', [TransactionsController::class, 'getRsaSettings'])
    ->name('transactions.rsa-settings.get');
    
Route::post('transactions-rsa-settings', [TransactionsController::class, 'updateRsaSettings'])
    ->name('transactions.rsa-settings.update');
```

## Cara Penggunaan

### Akses Pengaturan RSA
1. Login sebagai kasir/admin
2. Buka halaman Transaksi (`/transactions`)
3. Klik tombol **"Pengaturan Enkripsi RSA"** di bagian atas
4. Modal akan terbuka menampilkan nilai n, d, e saat ini
5. Edit nilai yang diinginkan
6. Klik **"Simpan Pengaturan"**

### Mengenkripsi Data Transaksi
Dalam `TransactionsService`, gunakan method:
```php
$encrypted = $this->simpleRsaEncrypt($price);
```

### Mendekripsi Data Transaksi
Dalam `TransactionsService`, gunakan method:
```php
$decrypted = $this->simpleRsaDecrypt($encrypted_price);
```

Atau dalam Blade view:
```blade
{{ simple_rsa_decrypt($transaction->total_amount) }}
{{ simple_rsa_decrypt($item->product_price) }}
```

### Operasi yang Menggunakan SimpleRSA
Semua operasi berikut otomatis mengenkripsi/dekripsi data dengan SimpleRSA:

1. **Create Transaction** (`storeNewTransaction`):
   - Enkripsi: `total_amount`, `product_price`

2. **Add Item to Transaction** (`storeNewItemInSameTransaction`):
   - Enkripsi: `product_price`
   - Dekripsi: `total_amount` (untuk kalkulasi)
   - Enkripsi ulang: `total_amount` baru

3. **Update Transaction Item** (`updateTransactionItem`):
   - Enkripsi: `product_price`
   - Dekripsi: semua `product_price` items (untuk kalkulasi total)
   - Enkripsi ulang: `total_amount` baru

4. **Delete Transaction Item** (`deleteTransactionItem`):
   - Dekripsi: semua `product_price` items (untuk kalkulasi total)
   - Enkripsi ulang: `total_amount` baru

5. **Pay Transaction** (`payTransaction`):
   - Dekripsi: `total_amount` (untuk deduct balance)

## Keamanan

### Peringatan
⚠️ **Penting**: Mengubah kunci RSA akan mempengaruhi kemampuan untuk mendekripsi data transaksi yang sudah ada. Lakukan dengan hati-hati!

### Best Practices
1. Gunakan kunci RSA yang lebih besar untuk produksi (minimal 2048 bit)
2. Backup kunci RSA sebelum mengubahnya
3. Test perubahan kunci di environment development terlebih dahulu
4. Hanya berikan akses edit ke user yang berwenang

## Generate RSA Keys untuk Produksi

Untuk generate kunci RSA yang lebih aman, Anda bisa menggunakan tools online atau script Python:

```python
from Crypto.PublicKey import RSA
from Crypto.Util.number import inverse

# Generate key pair
key = RSA.generate(2048)

# Extract components
n = key.n  # modulus
e = key.e  # public exponent (biasanya 65537)
d = key.d  # private exponent

print(f"n = {n}")
print(f"e = {e}")
print(f"d = {d}")
```

## Testing

Untuk menguji fungsi enkripsi/dekripsi:

```php
// Di tinker
php artisan tinker

use App\Support\SimpleRSA;
use App\Models\Preference;

$n = Preference::where('name', 'rsa_n')->value('value');
$d = Preference::where('name', 'rsa_d')->value('value');
$e = Preference::where('name', 'rsa_e')->value('value');

$rsa = new SimpleRSA($n, $d, $e);

$plaintext = "100000";
$encrypted = $rsa->encrypt($plaintext);
echo "Encrypted: " . $encrypted . "\n";

$decrypted = $rsa->decrypt($encrypted);
echo "Decrypted: " . $decrypted . "\n";
```

## Permission
Fitur ini memerlukan permission:
- `transactions.read`: Untuk melihat pengaturan
- `transactions.update`: Untuk mengubah pengaturan

## Troubleshooting

### Error: "RSA keys not found in database"
**Solusi**: Jalankan seeder untuk inisialisasi kunci:
```bash
php artisan db:seed --class=RsaKeysSeeder
```

### Error: "Nilai m=... di luar rentang byte"
**Solusi**: Nilai n terlalu kecil untuk enkripsi data. Gunakan nilai n yang lebih besar atau pastikan data yang dienkripsi tidak terlalu besar (gunakan per-byte encryption).

### Tidak bisa dekripsi data lama setelah ganti kunci
**Solusi**: Restore kunci lama terlebih dahulu, decrypt semua data, lalu encrypt ulang dengan kunci baru.
