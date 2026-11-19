<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'grade_level',
        'section',
        'strand',
        'school_year',
        'grading_period',
        'grade_value',
        'remarks',
    ];

    protected $casts = [
        'grade_value' => 'decimal:2',
    ];

    /**
     * Get the student user
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the teacher user
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
}
