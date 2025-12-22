# Panduan Penggunaan Enkripsi RSA untuk Products & Categories

## Prasyarat

Pastikan RSA keys sudah tersedia di database (table `preferences`):
```bash
php artisan db:seed --class=RsaKeysSeeder
```

Verifikasi RSA keys:
```bash
php artisan tinker
>>> \App\Models\Preference::whereIn('name', ['rsa_n', 'rsa_d', 'rsa_e'])->get(['name', 'value']);
```

## Instalasi untuk Data Baru

Jika Anda memulai dengan database kosong, enkripsi akan otomatis bekerja saat membuat data baru:

### 1. Create Product
```php
// Melalui form/controller - otomatis terenkripsi
Products::create([
    'category_id' => 1,
    'name' => 'Nasi Goreng',
    'description' => 'Nasi goreng spesial',
    'price' => '15000',
    'stock' => 100,
]);
```

### 2. Create Category
```php
// Melalui form/controller - otomatis terenkripsi
Categories::create([
    'name' => 'Makanan',
    'description' => 'Kategori makanan dan minuman',
]);
```

## Migrasi Data yang Sudah Ada

Jika Anda sudah memiliki data products dan categories yang belum terenkripsi:

### 1. Backup Database
**WAJIB! Backup database sebelum melakukan enkripsi:**
```bash
# MySQL
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# atau melalui Laravel
php artisan db:backup  # (jika menggunakan package backup)
```

### 2. Encrypt Existing Data
```bash
# Dengan konfirmasi
php artisan encrypt:products-categories

# Tanpa konfirmasi (untuk automation)
php artisan encrypt:products-categories --force
```

Output yang diharapkan:
```
Starting encryption process...

Encrypting Products...
  - Product ID 1: encrypted
  - Product ID 2: already encrypted, skipped
  - Product ID 3: encrypted
Products: 2 encrypted, 1 skipped

Encrypting Categories...
  - Category ID 1: encrypted
  - Category ID 2: encrypted
Categories: 2 encrypted, 0 skipped

Encryption completed successfully!

Summary:
+------------+-----------+---------+-------+
| Type       | Encrypted | Skipped | Total |
+------------+-----------+---------+-------+
| Products   | 2         | 1       | 3     |
| Categories | 2         | 0       | 2     |
+------------+-----------+---------+-------+
```

### 3. Verifikasi Hasil Enkripsi

#### Cek di Database (harus terenkripsi):
```sql
-- Data di database harus dalam format: "123:456:789:..."
SELECT id, name, price FROM products LIMIT 1;
SELECT id, name, description FROM categories LIMIT 1;
```

#### Cek di Aplikasi (harus terdekripsi):
```bash
php artisan tinker
>>> $product = \App\Models\Products::first();
>>> echo $product->name;  // Harus tampil plaintext (e.g., "Nasi Goreng")
>>> echo $product->price; // Harus tampil plaintext (e.g., "15000")
```

## Penggunaan di Kode

### Read Data (Otomatis Terdekripsi)
```php
// Service atau Controller
$products = Products::with('category')->get();
foreach ($products as $product) {
    echo $product->name;  // Otomatis terdekripsi
    echo $product->description;  // Otomatis terdekripsi
    echo $product->price;  // Otomatis terdekripsi
}

// Single product
$product = Products::find(1);
echo $product->name;  // Plaintext
```

### Create Data (Otomatis Terenkripsi)
```php
// ProductsService
Products::create([
    'name' => 'Nasi Goreng',  // Otomatis terenkripsi saat save
    'description' => 'Nasi goreng spesial',  // Otomatis terenkripsi
    'price' => '15000',  // Otomatis terenkripsi
]);
```

### Update Data (Otomatis Terenkripsi)
```php
$product = Products::find(1);
$product->update([
    'name' => 'Nasi Goreng Special',  // Otomatis terenkripsi
    'price' => '20000',  // Otomatis terenkripsi
]);

// atau
$product->name = 'New Name';  // Otomatis terenkripsi
$product->save();
```

### Search/Filter (Menggunakan Plaintext)
```php
// TIDAK BISA search langsung karena data terenkripsi di database
// ❌ SALAH:
Products::where('name', 'LIKE', '%Nasi%')->get();  // Tidak akan menemukan

// ✅ BENAR: Filter setelah fetch
$products = Products::all();
$filtered = $products->filter(function($product) {
    return str_contains(strtolower($product->name), 'nasi');
});
```

## Rollback (Dekripsi Data)

Jika perlu menghapus enkripsi (misalnya untuk debugging atau rollback):

### 1. Backup Database Lagi
```bash
mysqldump -u username -p database_name > backup_before_decrypt_$(date +%Y%m%d_%H%M%S).sql
```

### 2. Decrypt Data
```bash
# Dengan konfirmasi
php artisan decrypt:products-categories

# Tanpa konfirmasi
php artisan decrypt:products-categories --force
```

Output:
```
WARNING: This will decrypt all encrypted products and categories data.
Make sure you have a backup before proceeding!

Continue with decryption? (yes/no) [no]:
> yes

Starting decryption process...

Decrypting Products...
  - Product ID 1: decrypted
  - Product ID 2: decrypted
Products: 2 decrypted, 0 skipped, 0 errors

Decrypting Categories...
  - Category ID 1: decrypted
Categories: 1 decrypted, 0 skipped, 0 errors

Decryption completed successfully!
```

### 3. Hapus Trait dari Model (Opsional)
Jika ingin benar-benar rollback:

```php
// app/Models/Products.php
class Products extends Model
{
    use HasFactory; // Hapus: HasSimpleRsaEncryption
    
    // Hapus semua accessor dan mutator untuk name, description, price
}

// app/Models/Categories.php
class Categories extends Model
{
    use HasFactory; // Hapus: HasSimpleRsaEncryption
    
    // Hapus semua accessor dan mutator untuk name, description
}
```

## Troubleshooting

### Error: "RSA keys not found in database"
**Solusi:**
```bash
php artisan db:seed --class=RsaKeysSeeder
```

### Data tidak terdekripsi dengan benar
**Penyebab:** RSA keys mungkin berbeda dengan saat enkripsi
**Solusi:** 
- Pastikan menggunakan RSA keys yang sama
- Restore dari backup dan gunakan keys yang benar

### Performance lambat
**Penyebab:** Enkripsi per-byte bisa lambat untuk data besar
**Solusi:**
- Pertimbangkan menggunakan AES encryption untuk data besar
- Implementasi caching untuk data yang sering diakses

### Search tidak bekerja
**Penyebab:** Data terenkripsi tidak bisa di-search langsung di database
**Solusi:**
- Fetch semua data dan filter di aplikasi (untuk dataset kecil)
- Implementasi full-text search dengan data terdekripsi (untuk dataset besar)
- Pertimbangkan membuat kolom searchable terpisah yang tidak terenkripsi

## Best Practices

1. **Selalu Backup** sebelum enkripsi/dekripsi data production
2. **Test di Development** dulu sebelum apply ke production
3. **Monitor Performance** setelah implementasi
4. **Dokumentasi Keys** - simpan RSA keys di tempat yang aman
5. **Audit Log** - pertimbangkan logging untuk access data sensitif

## Command Reference

```bash
# Encrypt existing data
php artisan encrypt:products-categories
php artisan encrypt:products-categories --force

# Decrypt data (rollback)
php artisan decrypt:products-categories
php artisan decrypt:products-categories --force

# Seed RSA keys
php artisan db:seed --class=RsaKeysSeeder

# Testing via Tinker
php artisan tinker
>>> $product = \App\Models\Products::first();
>>> $product->name;
```

## Support Files

- **Trait**: `app/Traits/HasSimpleRsaEncryption.php`
- **SimpleRSA**: `app/Support/SimpleRSA.php`
- **Command Encrypt**: `app/Console/Commands/EncryptProductsCategoriesData.php`
- **Command Decrypt**: `app/Console/Commands/DecryptProductsCategoriesData.php`
- **Models**: `app/Models/Products.php`, `app/Models/Categories.php`
- **Documentation**: `IMPLEMENTASI_RSA_PRODUCT_CATEGORY.md`
