<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        // HAPUS 'student_id' kalau memang pakai tabel pivot
        'schedule_date',
        'start_time',
        'end_time',
        'jenis_kelas',
        'status',
        'revision_note',
    ];

    // Jika teacher disimpan di tabel users (role=teacher):
    public function teacher()
    {
        return $this->belongsTo(\App\Models\User::class, 'teacher_id');
    }
    // Jika teacher disimpan di tabel teachers, ganti baris di atas dengan:
    // return $this->belongsTo(\App\Models\Teacher::class, 'teacher_id');

    // Banyak siswa per jadwal (pivot: schedule_student)
    public function students()
    {
        return $this->belongsToMany(
            \App\Models\Student::class,   // model terkait
            'schedule_student',           // nama tabel pivot
            'schedule_id',                // FK jadwal di pivot
            'student_id'                  // FK siswa di pivot
        )->withTimestamps();
    }
}
