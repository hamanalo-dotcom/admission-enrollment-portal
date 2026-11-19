<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enrollment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'admission_id',
        'user_id',
        'grade_level',
        'strand',
        'tvl_branch',
        'section',
        'school_year',
        'semester',
        'guardian_name',
        'guardian_contact',
        'status',
        'cancellation_remarks',
        'selected_subjects',
        'dropped_subjects',
    ];

    protected $casts = [
        'selected_subjects' => 'array',
        'dropped_subjects' => 'array',
    ];

    // Relationships
    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolYearRecord()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year', 'school_year');
    }
}
