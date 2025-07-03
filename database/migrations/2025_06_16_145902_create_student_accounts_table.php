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
        Schema::create('student_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->comment('ID dari siswa yang memiliki akun');
            $table->string('nisn', 20)->unique()->comment('Nomor Induk Siswa Nasional');
            $table->string('kelas', 50)->comment('Kelas siswa');
            $table->decimal('balance', 10, 2)->default(0.00)->comment('Saldo akun siswa');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('Status akun siswa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_accounts');
    }
};
