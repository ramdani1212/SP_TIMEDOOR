<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Schedule;
use App\Models\Teacher;

class ScheduleRevisionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $schedule;
    protected $teacher;

    public function __construct(Schedule $schedule, Teacher $teacher)
    {
        $this->schedule = $schedule;
        $this->teacher = $teacher;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'revision',
            'teacher_name' => $this->teacher->name,
            'schedule' => [
                'id' => $this->schedule->id,
                'jenis_kelas' => $this->schedule->jenis_kelas,
                'schedule_date' => $this->schedule->schedule_date,
            ],
            'revision_note' => $this->schedule->revision_note,
        ];
    }
}