<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
// app/Models/Schedule.php
protected $fillable = ['teacher_id','schedule_date','start_time','end_time','jenis_kelas','status','revision_note'];
public function teacher(){ return $this->belongsTo(\App\Models\User::class,'teacher_id'); }
public function students(){ return $this->belongsToMany(\App\Models\Student::class); }

}
