<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\User;

class TeacherGeneralNoteNotification extends Notification
{
    use Queueable;
    protected $note;
    protected $teacher;

    public function __construct(string $note, User $teacher)
    {
        $this->note = $note;
        $this->teacher = $teacher;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return new DatabaseMessage([
            'type' => 'general_note',
            'message' => 'Catatan umum baru dari Guru ' . $this->teacher->name . '.',
            'note' => $this->note,
            'teacher_name' => $this->teacher->name,
            'teacher_id' => $this->teacher->id,
        ]);
    }
}