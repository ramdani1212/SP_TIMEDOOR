<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ScheduleCreatedNotification extends Notification
{
    use Queueable;

    public $schedule;
    public function __construct($schedule){ $this->schedule = $schedule; }

    public function via($notifiable){ return ['database']; }

    public function toDatabase($notifiable){
        return [
            'title'   => 'Jadwal Baru',
            'message' => 'Jadwal baru pada '.$this->schedule->schedule_date.' ('.$this->schedule->start_time.' - '.$this->schedule->end_time.') — '.$this->schedule->jenis_kelas,
            'schedule'=> [
                'id'         => $this->schedule->id,
                'date'       => $this->schedule->schedule_date,
                'start_time' => $this->schedule->start_time,
                'end_time'   => $this->schedule->end_time,
                'jenis'      => $this->schedule->jenis_kelas,
                'status'     => $this->schedule->status,
            ],
            // arahkan ke halaman notif teacher atau halaman jadwal teacher
            'url' => route('teacher.notifications.index'),
        ];
    }
}
