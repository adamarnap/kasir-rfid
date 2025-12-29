#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Testing RSA Configuration ===\n\n";

// Check if RSA keys exist
$n = \App\Models\Preference::where('name', 'rsa_n')->value('value');
$d = \App\Models\Preference::where('name', 'rsa_d')->value('value');
$e = \App\Models\Preference::where('name', 'rsa_e')->value('value');

echo "RSA Keys:\n";
echo "N: " . ($n ? substr($n, 0, 20) . '...' : 'NOT SET') . "\n";
echo "D: " . ($d ? substr($d, 0, 20) . '...' : 'NOT SET') . "\n";
echo "E: " . ($e ? substr($e, 0, 20) . '...' : 'NOT SET') . "\n\n";

if (!$n || !$d || !$e) {
    echo "❌ ERROR: RSA keys are not configured!\n";
    echo "Please configure RSA keys in the preferences table.\n";
    exit(1);
}

// Check products
echo "=== Checking Products ===\n\n";
$products = \DB::table('products')->limit(3)->get();

if ($products->isEmpty()) {
    echo "No products found in database.\n";
} else {
    foreach ($products as $product) {
        echo "Product ID: {$product->id}\n";
        echo "Raw Name: " . substr($product->name, 0, 50) . "...\n";
        echo "Raw Price: {$product->price}\n";
        
        // Check if name looks encrypted
        $isEncrypted = preg_match('/^\d+(:\d+)*$/', trim($product->name));
        echo "Name looks encrypted: " . ($isEncrypted ? 'YES' : 'NO') . "\n";
        echo "\n";
    }
}

echo "=== Test Complete ===\n";
