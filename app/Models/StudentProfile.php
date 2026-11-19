<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name','middle_name','last_name','suffix','date_of_birth','place_of_birth','gender','nationality','religion',
        'region_code','province_code','city_code','barangay_code','street','contact_no','email',
        'father_name','father_occupation','father_contact','mother_name','mother_occupation','mother_contact','guardian_name','guardian_contact',
        'year_level','strand','previous_school','previous_school_address','lrn',
    ];
}


