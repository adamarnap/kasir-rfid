<?php

namespace App\Console\Commands;

use App\Models\Products;
use App\Models\Categories;
use App\Models\Preference;
use App\Support\SimpleRSA;
use Illuminate\Console\Command;

class EncryptProductsCategoriesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'encrypt:products-categories {--force : Force encryption without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Encrypt existing products and categories data using SimpleRSA';

    /**
     * Get SimpleRSA instance
     */
    protected function getSimpleRSA(): SimpleRSA
    {
        $n = Preference::where('name', 'rsa_n')->value('value');
        $d = Preference::where('name', 'rsa_d')->value('value');
        $e = Preference::where('name', 'rsa_e')->value('value');

        if (!$n || !$d || !$e) {
            throw new \RuntimeException('RSA keys not found in database. Please run: php artisan db:seed --class=RsaKeysSeeder');
        }

        return new SimpleRSA($n, $d, $e);
    }

    /**
     * Check if data is already encrypted (format: "number:number:...")
     */
    protected function isEncrypted(?string $value): bool
    {
        if (empty($value)) {
            return false;
        }
        
        // Check if value matches encrypted format (numbers separated by colons)
        return (bool) preg_match('/^\d+(?::\d+)*$/', $value);
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $rsa = $this->getSimpleRSA();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            return 1;
        }

        if (!$this->option('force')) {
            if (!$this->confirm('This will encrypt all existing products and categories data. Continue?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Starting encryption process...');
        $this->newLine();

        // Encrypt Products
        $this->info('Encrypting Products...');
        $products = \DB::table('products')->get();
        $productsEncrypted = 0;
        $productsSkipped = 0;

        foreach ($products as $product) {
            $needsUpdate = false;
            $updateData = [];

            // Check and encrypt name
            if (!$this->isEncrypted($product->name)) {
                $updateData['name'] = $rsa->encrypt($product->name);
                $needsUpdate = true;
            }

            // Check and encrypt description
            if (!$this->isEncrypted($product->description)) {
                $updateData['description'] = $rsa->encrypt($product->description);
                $needsUpdate = true;
            }

            // Check and encrypt price
            if (!$this->isEncrypted($product->price)) {
                $updateData['price'] = $rsa->encrypt($product->price);
                $needsUpdate = true;
            }

            if ($needsUpdate) {
                \DB::table('products')->where('id', $product->id)->update($updateData);
                $productsEncrypted++;
                $this->line("  - Product ID {$product->id}: encrypted");
            } else {
                $productsSkipped++;
                $this->line("  - Product ID {$product->id}: already encrypted, skipped");
            }
        }

        $this->info("Products: {$productsEncrypted} encrypted, {$productsSkipped} skipped");
        $this->newLine();

        // Encrypt Categories
        $this->info('Encrypting Categories...');
        $categories = \DB::table('categories')->get();
        $categoriesEncrypted = 0;
        $categoriesSkipped = 0;

        foreach ($categories as $category) {
            $needsUpdate = false;
            $updateData = [];

            // Check and encrypt name
            if (!$this->isEncrypted($category->name)) {
                $updateData['name'] = $rsa->encrypt($category->name);
                $needsUpdate = true;
            }

            // Check and encrypt description
            if (!empty($category->description) && !$this->isEncrypted($category->description)) {
                $updateData['description'] = $rsa->encrypt($category->description);
                $needsUpdate = true;
            }

            if ($needsUpdate) {
                \DB::table('categories')->where('id', $category->id)->update($updateData);
                $categoriesEncrypted++;
                $this->line("  - Category ID {$category->id}: encrypted");
            } else {
                $categoriesSkipped++;
                $this->line("  - Category ID {$category->id}: already encrypted, skipped");
            }
        }

        $this->info("Categories: {$categoriesEncrypted} encrypted, {$categoriesSkipped} skipped");
        $this->newLine();

        $this->info('Encryption completed successfully!');
        $this->newLine();
        $this->info('Summary:');
        $this->table(
            ['Type', 'Encrypted', 'Skipped', 'Total'],
            [
                ['Products', $productsEncrypted, $productsSkipped, $productsEncrypted + $productsSkipped],
                ['Categories', $categoriesEncrypted, $categoriesSkipped, $categoriesEncrypted + $categoriesSkipped],
            ]
        );

        return 0;
    }
}
