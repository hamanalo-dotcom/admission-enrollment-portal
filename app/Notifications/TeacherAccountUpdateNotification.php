<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TeacherAccountUpdateNotification extends Notification
{
    use Queueable;

    protected $changes;
    protected $changedBy;

    public function __construct($changes, $changedBy = null)
    {
        $this->changes = $changes;
        $this->changedBy = $changedBy;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toArray($notifiable)
    {
        $adminName = $this->changedBy ? $this->changedBy->firstname . ' ' . $this->changedBy->lastname : 'Admin';
        
        $changeDescriptions = [];
        foreach ($this->changes as $field => $values) {
            $changeDescriptions[] = ucfirst(str_replace('_', ' ', $field)) . ': Changed';
        }

        $message = 'Your teacher account information has been updated by ' . $adminName . '. ';
        if (count($changeDescriptions) > 0) {
            $message .= 'Updated fields: ' . implode(', ', array_slice($changeDescriptions, 0, 3));
            if (count($changeDescriptions) > 3) {
                $message .= ' and ' . (count($changeDescriptions) - 3) . ' more.';
            }
        }

        return [
            'message' => $message,
            'type' => 'account_update',
            'changes' => $this->changes,
            'changed_by' => $adminName,
        ];
    }

    public function toMail($notifiable)
    {
        $adminName = $this->changedBy ? $this->changedBy->firstname . ' ' . $this->changedBy->lastname : 'Admin';

        return (new MailMessage)
            ->subject('Teacher Account Information Updated')
            ->greeting('Hello ' . ($notifiable->firstname ?? 'Teacher'))
            ->line('Your teacher account information has been updated by ' . $adminName . '.')
            ->line('If you did not request these changes, please contact the administrator immediately.')
            ->line('This is an automated message — please do not reply.');
    }
}

