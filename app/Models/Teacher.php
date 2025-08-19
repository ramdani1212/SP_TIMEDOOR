<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // PENTING: Gunakan Authenticatable
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable // PENTING: Extend class ini
{
    use HasFactory, Notifiable;

    protected $table = 'teachers'; // PENTING: Nama tabel harus benar

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}