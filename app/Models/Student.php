<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'umur',
        'alamat',
        'no_telp',
        'nama_orang_tua',
    ];

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class);
    }
}