<?php

namespace App\Console\Commands;

use App\Models\Products;
use App\Models\Categories;
use App\Models\Preference;
use App\Support\SimpleRSA;
use Illuminate\Console\Command;

class DecryptProductsCategoriesData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'decrypt:products-categories {--force : Force decryption without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Decrypt existing products and categories data (rollback encryption)';

    /**
     * Get SimpleRSA instance
     */
    protected function getSimpleRSA(): SimpleRSA
    {
        $n = Preference::where('name', 'rsa_n')->value('value');
        $d = Preference::where('name', 'rsa_d')->value('value');
        $e = Preference::where('name', 'rsa_e')->value('value');

        if (!$n || !$d || !$e) {
            throw new \RuntimeException('RSA keys not found in database.');
        }

        return new SimpleRSA($n, $d, $e);
    }

    /**
     * Check if data is encrypted (format: "number:number:...")
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
            $this->warn('WARNING: This will decrypt all encrypted products and categories data.');
            $this->warn('Make sure you have a backup before proceeding!');
            $this->newLine();
            
            if (!$this->confirm('Continue with decryption?')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('Starting decryption process...');
        $this->newLine();

        // Decrypt Products
        $this->info('Decrypting Products...');
        $products = \DB::table('products')->get();
        $productsDecrypted = 0;
        $productsSkipped = 0;
        $productsErrors = 0;

        foreach ($products as $product) {
            $needsUpdate = false;
            $updateData = [];

            try {
                // Check and decrypt name
                if ($this->isEncrypted($product->name)) {
                    $updateData['name'] = $rsa->decrypt($product->name);
                    $needsUpdate = true;
                }

                // Check and decrypt description
                if ($this->isEncrypted($product->description)) {
                    $updateData['description'] = $rsa->decrypt($product->description);
                    $needsUpdate = true;
                }

                // Check and decrypt price
                if ($this->isEncrypted($product->price)) {
                    $updateData['price'] = $rsa->decrypt($product->price);
                    $needsUpdate = true;
                }

                if ($needsUpdate) {
                    \DB::table('products')->where('id', $product->id)->update($updateData);
                    $productsDecrypted++;
                    $this->line("  - Product ID {$product->id}: decrypted");
                } else {
                    $productsSkipped++;
                    $this->line("  - Product ID {$product->id}: not encrypted, skipped");
                }
            } catch (\Exception $e) {
                $productsErrors++;
                $this->error("  - Product ID {$product->id}: ERROR - {$e->getMessage()}");
            }
        }

        $this->info("Products: {$productsDecrypted} decrypted, {$productsSkipped} skipped, {$productsErrors} errors");
        $this->newLine();

        // Decrypt Categories
        $this->info('Decrypting Categories...');
        $categories = \DB::table('categories')->get();
        $categoriesDecrypted = 0;
        $categoriesSkipped = 0;
        $categoriesErrors = 0;

        foreach ($categories as $category) {
            $needsUpdate = false;
            $updateData = [];

            try {
                // Check and decrypt name
                if ($this->isEncrypted($category->name)) {
                    $updateData['name'] = $rsa->decrypt($category->name);
                    $needsUpdate = true;
                }

                // Check and decrypt description
                if (!empty($category->description) && $this->isEncrypted($category->description)) {
                    $updateData['description'] = $rsa->decrypt($category->description);
                    $needsUpdate = true;
                }

                if ($needsUpdate) {
                    \DB::table('categories')->where('id', $category->id)->update($updateData);
                    $categoriesDecrypted++;
                    $this->line("  - Category ID {$category->id}: decrypted");
                } else {
                    $categoriesSkipped++;
                    $this->line("  - Category ID {$category->id}: not encrypted, skipped");
                }
            } catch (\Exception $e) {
                $categoriesErrors++;
                $this->error("  - Category ID {$category->id}: ERROR - {$e->getMessage()}");
            }
        }

        $this->info("Categories: {$categoriesDecrypted} decrypted, {$categoriesSkipped} skipped, {$categoriesErrors} errors");
        $this->newLine();

        if ($productsErrors > 0 || $categoriesErrors > 0) {
            $this->warn('Decryption completed with errors. Please check the errors above.');
        } else {
            $this->info('Decryption completed successfully!');
        }
        
        $this->newLine();
        $this->info('Summary:');
        $this->table(
            ['Type', 'Decrypted', 'Skipped', 'Errors', 'Total'],
            [
                ['Products', $productsDecrypted, $productsSkipped, $productsErrors, $productsDecrypted + $productsSkipped + $productsErrors],
                ['Categories', $categoriesDecrypted, $categoriesSkipped, $categoriesErrors, $categoriesDecrypted + $categoriesSkipped + $categoriesErrors],
            ]
        );

        return $productsErrors > 0 || $categoriesErrors > 0 ? 1 : 0;
    }
}
