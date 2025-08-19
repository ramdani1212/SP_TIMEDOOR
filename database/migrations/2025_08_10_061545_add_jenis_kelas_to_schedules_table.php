<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Tambahkan kolom jenis_kelas
            $table->string('jenis_kelas')->after('revision_note'); 
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Hapus kolom jenis_kelas jika migrasi di-rollback
            $table->dropColumn('jenis_kelas');
        });
    }
};