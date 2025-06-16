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
        Schema::create('parent_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->comment('ID dari orang tua yang terkait');
            $table->foreignId('student_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->onUpdate('cascade')
                ->comment('ID dari siswa yang terkait');
            $table->enum('relationship', ['father', 'mother', 'guardian'])
                ->default('guardian')
                ->comment('Hubungan orang tua dengan siswa, bisa berupa ayah, ibu, atau wali');
            $table->string('contact_number')->nullable()->comment('Nomor kontak orang tua');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_student');
    }
};
