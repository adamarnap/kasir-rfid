# Testing RSA Encryption - Products & Categories

## Quick Test Commands

### 1. Test via Tinker (Recommended)

```bash
php artisan tinker
```

#### Test 1: Create & Read Product
```php
// Create product baru
$product = \App\Models\Products::create([
    'category_id' => 1,
    'name' => 'Test Product RSA',
    'description' => 'Testing enkripsi RSA',
    'price' => '25000',
    'stock' => 50
]);

// Cek di database (raw query - harus terenkripsi)
\DB::table('products')->where('id', $product->id)->first();
// Expected: name, description, price dalam format "123:456:789:..."

// Cek via model (harus terdekripsi)
$product->name;  // Expected: "Test Product RSA" (plaintext)
$product->description;  // Expected: "Testing enkripsi RSA" (plaintext)
$product->price;  // Expected: "25000" (plaintext)
```

#### Test 2: Create & Read Category
```php
// Create category baru
$category = \App\Models\Categories::create([
    'name' => 'Test Category RSA',
    'description' => 'Testing enkripsi RSA category'
]);

// Cek di database (raw query - harus terenkripsi)
\DB::table('categories')->where('id', $category->id)->first();
// Expected: name, description dalam format "123:456:789:..."

// Cek via model (harus terdekripsi)
$category->name;  // Expected: "Test Category RSA" (plaintext)
$category->description;  // Expected: "Testing enkripsi RSA category" (plaintext)
```

#### Test 3: Update Product
```php
$product = \App\Models\Products::first();
$oldName = $product->name;

$product->update([
    'name' => 'Updated Name - ' . now()->format('H:i:s'),
    'price' => '30000'
]);

// Cek perubahan
$product->fresh()->name;  // Should show new name
$product->fresh()->price;  // Should show "30000"

// Cek di database (harus terenkripsi)
\DB::table('products')->where('id', $product->id)->value('name');
// Should be encrypted format
```

#### Test 4: Integration with Transaction
```php
// Ambil product
$product = \App\Models\Products::first();

// Cek apakah price terdekripsi dengan benar untuk transaksi
echo "Product Name: " . $product->name . "\n";
echo "Product Price: " . $product->price . "\n";
echo "Calculated Total: " . ($product->price * 2) . "\n";

// Semua harus plaintext dan bisa dihitung
```

#### Test 5: Verify RSA Keys
```php
// Cek RSA keys ada atau tidak
$keys = \App\Models\Preference::whereIn('name', ['rsa_n', 'rsa_d', 'rsa_e'])
    ->pluck('value', 'name');

if ($keys->count() === 3) {
    echo "✅ RSA Keys found!\n";
    echo "N length: " . strlen($keys['rsa_n']) . " chars\n";
    echo "D length: " . strlen($keys['rsa_d']) . " chars\n";
    echo "E length: " . strlen($keys['rsa_e']) . " chars\n";
} else {
    echo "❌ RSA Keys not found! Run: php artisan db:seed --class=RsaKeysSeeder\n";
}
```

### 2. Test Encrypt Existing Data

```bash
# Lihat data sebelum enkripsi
php artisan tinker
>>> \DB::table('products')->select('id', 'name')->get();
>>> exit

# Encrypt data
php artisan encrypt:products-categories --force

# Cek hasil
php artisan tinker
>>> \DB::table('products')->select('id', 'name')->first();  // Harus encrypted
>>> \App\Models\Products::first()->name;  // Harus plaintext
```

### 3. Test via Browser/UI

1. **Create Product:**
   - Login ke aplikasi
   - Buka menu Master > Products
   - Tambah product baru dengan:
     - Name: "Nasi Goreng Spesial"
     - Description: "Nasi goreng dengan telur dan ayam"
     - Price: 15000
     - Stock: 100
   - Save
   - ✅ Product harus muncul dengan data plaintext di list

2. **Edit Product:**
   - Edit product yang baru dibuat
   - Ubah name menjadi "Nasi Goreng Super Spesial"
   - Ubah price menjadi 18000
   - Save
   - ✅ Perubahan harus terlihat di list

3. **Check in Database:**
   ```sql
   SELECT id, name, price FROM products ORDER BY id DESC LIMIT 1;
   ```
   - ✅ Kolom name dan price harus dalam format encrypted: "123:456:789:..."

4. **Create Transaction:**
   - Buka menu Transactions
   - Pilih product yang baru dibuat
   - ✅ Harga harus muncul dengan benar (18000)
   - ✅ Nama product harus muncul dengan benar

## Expected Results

### Database (Raw Query)
```
products.name: "78:97:115:105:32:71:111:114:101:110:103"
products.price: "49:53:48:48:48"
```

### Application (via Model)
```
$product->name: "Nasi Goreng"
$product->price: "15000"
```

## Troubleshooting Tests

### Test Fails: "RSA keys not found"
```bash
php artisan db:seed --class=RsaKeysSeeder
```

### Test Fails: Data tidak terdekripsi
Check trait sudah ditambahkan:
```php
// app/Models/Products.php
use App\Traits\HasSimpleRsaEncryption;

class Products extends Model
{
    use HasFactory, HasSimpleRsaEncryption;  // ✅ Pastikan trait ada
```

### Test Fails: Error saat decrypt
Kemungkinan data corrupt atau keys salah. Rollback:
```bash
# Restore dari backup
mysql -u username -p database_name < backup.sql

# Atau decrypt dulu
php artisan decrypt:products-categories --force
```

## Performance Test

```php
// Test 1000 products
$start = microtime(true);

for ($i = 0; $i < 1000; $i++) {
    \App\Models\Products::create([
        'category_id' => 1,
        'name' => 'Product ' . $i,
        'description' => 'Description for product ' . $i,
        'price' => rand(10000, 100000),
        'stock' => rand(10, 100)
    ]);
}

$time = microtime(true) - $start;
echo "Time to create 1000 products: " . round($time, 2) . " seconds\n";
echo "Average per product: " . round($time / 1000, 4) . " seconds\n";

// Read test
$start = microtime(true);
$products = \App\Models\Products::take(1000)->get();
foreach ($products as $product) {
    $name = $product->name;  // Force decryption
}
$time = microtime(true) - $start;
echo "Time to read 1000 products: " . round($time, 2) . " seconds\n";
```

## Test Checklist

- [ ] RSA keys ada di database
- [ ] Create product baru → terenkripsi di DB, terdekripsi di app
- [ ] Update product → terenkripsi ulang dengan benar
- [ ] Read product → data plaintext
- [ ] Create category → terenkripsi di DB, terdekripsi di app
- [ ] Update category → terenkripsi ulang dengan benar
- [ ] Transaction menggunakan product → price correct
- [ ] View products list di UI → semua data tampil dengan benar
- [ ] Encrypt existing data command → works
- [ ] Decrypt data command → works dan data kembali plaintext

## Cleanup Test Data

```bash
php artisan tinker
```

```php
// Hapus test products
\App\Models\Products::where('name', 'LIKE', 'Test%')->delete();
\App\Models\Products::where('name', 'LIKE', 'Product %')->delete();

// Hapus test categories
\App\Models\Categories::where('name', 'LIKE', 'Test%')->delete();
```
