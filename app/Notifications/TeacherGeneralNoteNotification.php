<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Teacher;

class TeacherGeneralNoteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $teacher;
    protected $note;

    public function __construct(Teacher $teacher, string $note)
    {
        $this->teacher = $teacher;
        $this->note = $note;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'general_note',
            'teacher_name' => $this->teacher->name,
            'note' => $this->note,
        ];
    }
}