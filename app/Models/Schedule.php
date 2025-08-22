<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Student;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'student_id',
        'schedule_date',
        'start_time',
        'end_time',
        'jenis_kelas',
        'status',
        'revision_note',
    ];

    /**
     * Get the teacher that owns the schedule.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the student that owns the schedule.
     */
    public function student() // Perhatikan, nama metode ini 'student' (tunggal)
    {
        return $this->belongsTo(Student::class);
    }
}