<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class StudentAbsentNotification extends Notification
{
    use Queueable;

    protected $schedule;

    public function __construct($schedule)
    {
        $this->schedule = $schedule;
    }

    public function via($notifiable)
    {
        return ['database']; // Menggunakan channel database
    }

    public function toArray($notifiable)
    {
        return [
            'schedule_id' => $this->schedule->id,
            'teacher_name' => $this->schedule->teacher->name,
            'message' => 'Siswa untuk jadwal pada ' . $this->schedule->schedule_date . ' oleh ' . $this->schedule->teacher->name . ' belum datang.',
        ];
    }
}