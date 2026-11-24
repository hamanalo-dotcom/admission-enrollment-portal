<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'grade_level',
        'strand',
        'description',
    ];

    /**
     * Get grades for this subject
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Get teacher assignments for this subject
     */
    public function teacherAssignments()
    {
        return $this->hasMany(TeacherSectionAssignment::class);
    }
}
