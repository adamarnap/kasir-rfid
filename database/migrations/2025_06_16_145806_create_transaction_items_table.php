<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                ->constrained('transactions')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->comment('ID dari transaksi yang terkait dengan item ini');
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->comment('ID dari produk yang terkait dengan item ini');
            $table->integer('quantity')->default(1)->comment('Jumlah produk yang dibeli dalam transaksi ini');
            $table->longText('product_price')->comment('Harga produk pada saat transaksi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
    }
};
