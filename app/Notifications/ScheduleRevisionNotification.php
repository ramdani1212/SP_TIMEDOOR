<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleRevisionNotification extends Notification
{
    use Queueable;

    protected $teacherName;

    public function __construct($teacherName)
    {
        $this->teacherName = $teacherName;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Menyimpan notifikasi ke dalam tabel 'notifications'
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Guru ' . $this->teacherName . ' telah meminta revisi untuk jadwal.',
            'teacher_name' => $this->teacherName,
        ];
    }
}