# Implementasi SimpleRSA untuk Products dan Categories

## Overview
Implementasi enkripsi dan dekripsi menggunakan `SimpleRSA` untuk mengamankan data sensitif pada tabel `products` dan `categories`.

## Files yang Dibuat/Dimodifikasi

### 1. Trait Baru
- **File**: `app/Traits/HasSimpleRsaEncryption.php`
- **Fungsi**: Menyediakan method helper untuk enkripsi dan dekripsi menggunakan SimpleRSA
- **Method**:
  - `getSimpleRSA()`: Mengambil instance SimpleRSA dari database (preferences table)
  - `simpleRsaEncrypt()`: Enkripsi plaintext
  - `simpleRsaDecrypt()`: Dekripsi ciphertext

### 2. Model Products
- **File**: `app/Models/Products.php`
- **Perubahan**:
  - Menambahkan trait `HasSimpleRsaEncryption`
  - Menambahkan accessor dan mutator untuk kolom yang dienkripsi:
    - `name` (encrypted)
    - `description` (encrypted)
    - `price` (encrypted)

### 3. Model Categories
- **File**: `app/Models/Categories.php`
- **Perubahan**:
  - Menambahkan trait `HasSimpleRsaEncryption`
  - Menambahkan accessor dan mutator untuk kolom yang dienkripsi:
    - `name` (encrypted)
    - `description` (encrypted)

## Kolom yang Dienkripsi

### Table: products
- `name` - Nama produk
- `description` - Deskripsi produk
- `price` - Harga produk

### Table: categories
- `name` - Nama kategori
- `description` - Deskripsi kategori

## Cara Kerja

### Enkripsi (Otomatis saat Create/Update)
Ketika data disimpan melalui:
- `Products::create([...])` 
- `Categories::create([...])`
- `$product->update([...])`
- `$category->update([...])`

Mutator akan otomatis mengenkripsi data sebelum disimpan ke database.

**Contoh**:
```php
// Di ProductsService
Products::create([
    'name' => 'Nasi Goreng',  // akan otomatis ter-enkripsi
    'description' => 'Nasi goreng spesial',  // akan otomatis ter-enkripsi
    'price' => '15000',  // akan otomatis ter-enkripsi
]);
```

### Dekripsi (Otomatis saat Read)
Ketika data dibaca melalui:
- `Products::all()`
- `Categories::all()` 
- `$product->name`
- `$category->description`

Accessor akan otomatis mendekripsi data dari database.

**Contoh**:
```php
// Di ProductsService
$products = Products::all();
foreach ($products as $product) {
    echo $product->name;  // sudah ter-dekripsi otomatis
    echo $product->price;  // sudah ter-dekripsi otomatis
}
```

## Konfigurasi RSA Keys

RSA keys disimpan di tabel `preferences` dengan nama:
- `rsa_n` - Modulus (n)
- `rsa_d` - Private exponent (d)
- `rsa_e` - Public exponent (e)

Keys ini digunakan oleh SimpleRSA untuk enkripsi/dekripsi.

## Testing

### 1. Test Create Product
```php
// Buat product baru
Products::create([
    'category_id' => 1,
    'name' => 'Test Product',
    'description' => 'Test Description',
    'price' => '10000',
    'stock' => 100
]);

// Cek di database - data harus terenkripsi (format: "c0:c1:c2:...")
// Cek di aplikasi - data harus terdekripsi dengan benar
```

### 2. Test Read Product
```php
$product = Products::find(1);
echo $product->name;  // Harus menampilkan plaintext
echo $product->price;  // Harus menampilkan plaintext
```

### 3. Test Update Product
```php
$product = Products::find(1);
$product->update([
    'name' => 'Updated Name',
    'price' => '20000'
]);
// Data harus ter-enkripsi ulang di database
```

## Integrasi dengan Fitur Lain

### TransactionsService
TransactionsService sudah menggunakan Products model, sehingga:
- Saat membaca `$product->price` untuk transaksi, akan otomatis terdekripsi
- Saat menampilkan nama produk, akan otomatis terdekripsi
- Tidak perlu modifikasi code di TransactionsService

### Views
Views yang menampilkan products/categories tidak perlu diubah karena:
- Data sudah otomatis terdekripsi saat diakses
- `{{ $product->name }}` akan menampilkan plaintext
- `{{ $category->description }}` akan menampilkan plaintext

## Error Handling

Jika RSA keys tidak ditemukan di database, akan muncul exception:
```
RuntimeException: RSA keys not found in database. Please configure encryption settings.
```

Pastikan RSA keys sudah di-seed dengan menjalankan:
```bash
php artisan db:seed --class=RsaKeysSeeder
```

## Catatan Penting

1. **Backup Data**: Sebelum implementasi di production, pastikan backup semua data products dan categories
2. **Migration**: Jika sudah ada data lama yang belum terenkripsi, perlu dibuat command untuk encrypt existing data
3. **Performance**: Enkripsi per-byte dapat mempengaruhi performa untuk data yang besar
4. **Key Management**: Jaga keamanan RSA keys di database dengan baik

## Maintenance

### Encrypt Existing Data (Jika diperlukan)
Buat command untuk encrypt data yang sudah ada:
```bash
php artisan make:command EncryptProductsCategoriesData
```

### Decrypt Existing Data (Untuk rollback)
Buat command untuk decrypt data jika perlu rollback:
```bash
php artisan make:command DecryptProductsCategoriesData
```
