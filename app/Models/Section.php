<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'grade_level',
        'name',
        'strand',
        'capacity',
    ];
}


