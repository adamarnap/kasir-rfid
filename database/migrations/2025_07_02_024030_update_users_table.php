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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['l', 'p'])->after('email')->comment('Jenis kelamin siswa');
            $table->text('alamat')->nullable()->after('jenis_kelamin')->comment('Alamat siswa');
            $table->string('telepon', 15)->nullable()->after('alamat')->comment('Nomor telepon siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'alamat', 'telepon']);
        });
    }
};
