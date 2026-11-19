<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Admission;

class AdmissionStatusNotification extends Notification
{
    use Queueable;

    protected $status;
    protected $admission;

    public function __construct($status, $admission = null)
    {
        $this->status = $status;
        $this->admission = $admission;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toArray($notifiable)
    {
        switch ($this->status) {
            case 'approved':
                $message = '✅ Your admission application has been approved. ';
                break;

            case 'rejected':
                $message = '❌ We regret to inform you that your admission application has been rejected. Kindly proceed to the Registrar’s Office for clarification or further instructions.';
                break;

            default:
                $message = '⏳ Your admission application is still pending. Please wait for further updates.';
                break;
        }

        return [
            'message' => $message,
            'status' => $this->status,
        ];
    }

    public function toMail($notifiable)
    {
        $subject = 'Admission Status Update: ' . ucfirst($this->status);
        $intro = match ($this->status) {
            'approved' => 'Congratulations! Your admission application has been approved. You can now create your account to access the student portal.',
            'rejected' => 'Your admission application has been rejected. Please contact the Registrar\'s Office for details.',
            default => 'Your admission application is pending. We will notify you of any updates.',
        };

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . ($notifiable->firstname ?? $this->admission->first_name ?? 'Student'))
            ->line($intro);

        // For approved admissions, add registration link
        if ($this->status === 'approved' && $this->admission) {
            $email = $this->admission->email ?? $notifiable->email;
            $registrationUrl = route('register.form');
            
            $mailMessage->line('To create your account, please click the button below to register:')
                ->action('Create Account', $registrationUrl)
                ->line('Important: Please use the same email address you provided in your admission application: ' . $email)
                ->line('After registration, you will be able to access the student portal.');
        } else {
            $mailMessage->action('View Admission', url('/admissions'));
        }

        $mailMessage->line('This is an automated message — please do not reply.');

        return $mailMessage;
    }
}
