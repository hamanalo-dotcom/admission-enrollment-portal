<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class EnrollmentStatusNotification extends Notification
{
    use Queueable;

    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toArray($notifiable)
    {
        switch ($this->status) {
            case 'enrolled':
                $message = '✅ You are now successfully enrolled. Welcome to the new school year!';
                break;

            case 'cancelled':
                $message = '❌ We regret to inform you that your enrollment has been cancelled. Kindly proceed to the Registrar’s Office for clarification or assistance.';
                break;

            case 'completed':
                $message = '🎓 Congratulations! Your enrollment is now marked as completed.';
                break;

            default: // pending or others
                $message = '⏳ Your enrollment is still pending. Please wait for updates from the registrar.';
                break;
        }

        return [
            'message' => $message,
            'status' => $this->status,
        ];
    }

    public function toMail($notifiable)
    {
        $subject = 'Enrollment Status Update: ' . ucfirst($this->status);
        $intro = match ($this->status) {
            'enrolled' => 'You are now successfully enrolled. Welcome to the new school year!',
            'cancelled' => 'Your enrollment has been cancelled. Please contact the Registrar’s Office if you believe this is an error.',
            'completed' => 'Your enrollment is now marked as completed. Congratulations!',
            default => 'Your enrollment is pending. We will notify you of any updates.',
        };

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . ($notifiable->firstname ?? 'Student'))
            ->line($intro)
            ->action('View Enrollment', url('/enrollment'))
            ->line('This is an automated message — please do not reply.');
    }
}
