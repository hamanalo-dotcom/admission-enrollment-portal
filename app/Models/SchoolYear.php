<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolYear extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_year',
        'is_active',
        'is_hidden',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_hidden' => 'boolean',
    ];

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'school_year', 'school_year');
    }
}
