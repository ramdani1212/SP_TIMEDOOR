<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TeacherMessageNotification extends Notification
{
    use Queueable;

    public $fromUser;
    public $text;

    public function __construct($fromUser, $text)
    {
        $this->fromUser = $fromUser;
        $this->text     = $text;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title'   => 'Pesan dari Teacher',
            'message' => $this->text,
            'from'    => [
                'id'   => $this->fromUser->id,
                'name' => $this->fromUser->name,
                'role' => $this->fromUser->role,
            ],
            'url'     => route('admin.notifications.index'),
        ];
    }
}
