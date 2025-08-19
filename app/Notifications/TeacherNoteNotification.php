<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\User;
use App\Models\Schedule;

class TeacherNoteNotification extends Notification
{
    use Queueable;
    protected $note;
    protected $teacher;
    protected $schedule;

    public function __construct(string $note, User $teacher, Schedule $schedule)
    {
        $this->note = $note;
        $this->teacher = $teacher;
        $this->schedule = $schedule;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'type' => 'revision',
            'message' => 'Guru ' . $this->teacher->name . ' merevisi jadwal ' . $this->schedule->jenis_kelas . '.',
            'note' => $this->note,
            'teacher_name' => $this->teacher->name,
            'teacher_id' => $this->teacher->id,
            'schedule_id' => $this->schedule->id,
            'class' => $this->schedule->jenis_kelas,
        ]);
    }
}