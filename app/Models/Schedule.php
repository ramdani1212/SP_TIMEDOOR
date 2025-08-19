<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'schedule_date',
        'start_time',
        'end_time',
        'status',
        'revision_note',
        'jenis_kelas',
    ];

    /**
     * Relasi: Sebuah jadwal dimiliki oleh satu guru.
     */
    // File: app/Models/Schedule.php

    
   public function teacher()
    {
    return $this->belongsTo(\App\Models\User::class, 'teacher_id');
    }
    /**
     * Relasi: Sebuah jadwal bisa memiliki banyak siswa.
     */
    public function students()
    {
        return $this->belongsToMany(Student::class, 'schedule_student', 'schedule_id', 'student_id')
                    ->withTimestamps();
    }
}
