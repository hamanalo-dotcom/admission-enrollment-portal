<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSectionAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'subject_id',
        'grade_level',
        'section',
        'strand',
        'tvl_branch',
        'school_year',
    ];

    /**
     * Get the teacher (user) assigned to this section
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Get the subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get the section details
     */
    public function sectionDetails()
    {
        return $this->belongsTo(Section::class, 'section', 'name')
            ->where('grade_level', $this->grade_level)
            ->where(function($q) {
                if ($this->strand) {
                    $q->where('strand', $this->strand)->orWhereNull('strand');
                } else {
                    $q->whereNull('strand');
                }
            });
    }
}

