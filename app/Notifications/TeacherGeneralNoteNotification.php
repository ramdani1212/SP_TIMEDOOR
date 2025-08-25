<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
// use Illuminate\Contracts\Queue\ShouldQueue; // aktifkan kalau mau dijalankan via queue

class TeacherGeneralNoteNotification extends Notification /* implements ShouldQueue */
{
    use Queueable;

    protected string $note;
    protected $teacher;   // instance guard teacher (User model versi teacher)
    protected $schedule;  // bisa null

    /**
     * @param string $note        Pesan yang diketik guru
     * @param mixed  $teacher     Model user teacher (punya ->id dan ->name)
     * @param mixed  $schedule    (opsional) Model Schedule
     */
    public function __construct(string $note, $teacher, $schedule = null)
    {
        $this->note     = $note;
        $this->teacher  = $teacher;
        $this->schedule = $schedule;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $data = [
            'title'   => 'Pesan dari Guru',
            'message' => $this->note, // ← ini yang tampil di notifikasi admin
            'teacher' => [
                'id'   => $this->teacher?->id,
                'name' => $this->teacher?->name,
            ],
            'url'     => route('admin.notifications.index'),
        ];

        if ($this->schedule) {
            $data['schedule'] = [
                'id'         => $this->schedule->id,
                'date'       => $this->schedule->schedule_date,
                'start_time' => $this->schedule->start_time,
                'end_time'   => $this->schedule->end_time,
                'jenis'      => $this->schedule->jenis_kelas,
                'status'     => $this->schedule->status,
            ];
        }

        return $data;
    }
}
