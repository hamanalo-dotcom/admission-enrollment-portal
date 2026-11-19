<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TeacherAssignmentNotification extends Notification
{
    use Queueable;

    protected $action; // 'assigned', 'updated', 'removed'
    protected $details; // Assignment details (grade_level, section, strand, school_year)

    public function __construct($action, $details)
    {
        $this->action = $action;
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toArray($notifiable)
    {
        $gradeLevel = $this->details['grade_level'] ?? '';
        $section = $this->details['section'] ?? '';
        $strand = $this->details['strand'] ?? null;
        $schoolYear = $this->details['school_year'] ?? '';
        $sectionsCount = $this->details['sections_count'] ?? 1;

        // Format section information
        $sectionInfo = $section;
        if ($sectionsCount > 1) {
            $sectionInfo = "{$sectionsCount} sections";
        }
        
        if ($strand) {
            $sectionInfo .= " ({$strand})";
        }

        // Create detailed message based on action
        switch ($this->action) {
            case 'assigned':
                if ($sectionsCount > 1) {
                    $message = "✅ You have been assigned to {$sectionsCount} sections in {$gradeLevel}" . ($strand ? " - {$strand}" : "") . " for school year {$schoolYear}.";
                } else {
                    $message = "✅ You have been assigned to {$gradeLevel} - Section {$sectionInfo}" . ($strand ? " ({$strand})" : "") . " for school year {$schoolYear}.";
                }
                break;

            case 'updated':
                $message = "🔄 Your assignment has been updated: {$gradeLevel} - Section {$sectionInfo}" . ($strand ? " ({$strand})" : "") . " for school year {$schoolYear}.";
                break;

            case 'removed':
                $message = "❌ Your assignment has been removed: {$gradeLevel} - Section {$sectionInfo}" . ($strand ? " ({$strand})" : "") . " for school year {$schoolYear}.";
                break;

            default:
                $message = "📋 Your assignment information has been changed.";
                break;
        }

        return [
            'type' => 'teacher_assignment',
            'message' => $message,
            'action' => $this->action,
            'grade_level' => $gradeLevel,
            'section' => $section,
            'sections_count' => $sectionsCount,
            'strand' => $strand,
            'school_year' => $schoolYear,
        ];
    }

    public function toMail($notifiable)
    {
        $subject = 'Teacher Assignment ' . ucfirst($this->action) . ': ' . ($this->details['grade_level'] ?? 'Update');
        
        $intro = match ($this->action) {
            'assigned' => 'You have been assigned to a new section.',
            'updated' => 'Your section assignment has been updated.',
            'removed' => 'Your section assignment has been removed.',
            default => 'Your section assignment has been changed.',
        };

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello ' . ($notifiable->firstname ?? 'Teacher'))
            ->line($intro)
            ->line('Grade Level: ' . ($this->details['grade_level'] ?? 'N/A'))
            ->line('Section: ' . ($this->details['section'] ?? 'N/A'))
            ->when($this->details['strand'] ?? null, function ($mail) {
                return $mail->line('Strand: ' . $this->details['strand']);
            })
            ->line('School Year: ' . ($this->details['school_year'] ?? 'N/A'))
            ->action('View Dashboard', url('/teacher-dashboard'))
            ->line('This is an automated message — please do not reply.');
    }
}

