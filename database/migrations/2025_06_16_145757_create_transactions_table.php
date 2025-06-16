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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade')->comment('ID dari kasir yang melakukan transaksi');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade')->comment('ID dari siswa yang melakukan transaksi');
            $table->decimal('total_amount', 10, 2)->comment('Jumlah total transaksi');
            $table->enum('payment_method', ['cash', 'rfid'])->default('cash')->comment('Metode pembayaran yang digunakan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
