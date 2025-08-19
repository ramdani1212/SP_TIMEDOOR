<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers')->onDelete('cascade'); // foreign key ke tabel teachers
            $table->date('schedule_date');       // tanggal jadwal
            $table->time('start_time');          // jam mulai
            $table->time('end_time');            // jam selesai
            $table->enum('status', ['pending', 'approved', 'revision'])->default('pending'); // status validasi
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
