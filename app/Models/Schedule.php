<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
protected $fillable = [
  'teacher_id','schedule_date','start_time','end_time','jenis_kelas','status','revision_note'
];


    // RELASI
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        // pastikan nama pivot: schedule_student (bukan schedules_students)
        return $this->belongsToMany(Student::class)->withTimestamps();
    }
}
