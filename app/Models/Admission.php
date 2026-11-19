<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'reference_number',
        'student_type',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'nationality',
        'religion',
        'region_code',
        // 'address',
        'province_code',
        'city_code',
        'barangay_code',
        'street',
        'contact_no',
        'email',
        'is_draft',
        'father_name',
        'father_occupation',
        'father_contact',
        'mother_name',
        'mother_occupation',
        'mother_contact',
        'guardian_name',
        'guardian_contact',
        'year_level',
        'gwa',
        'strand',
        'tvl_branch',
        'previous_school',
        'previous_school_address',
        'lrn',
        'report_card',
        'form_137',
        'birth_certificate',
        'good_moral',
        'id_picture',
        'medical_certificate',
        'status',
        'rejection_remarks',
        'file_review_status',
    ];

    protected $casts = [
        'file_review_status' => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(AdmissionStatusHistory::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_code', 'regCode');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'provCode');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'citymunCode');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_code', 'brgyCode');
    }
}


// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Admission extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         'user_id',
//         'student_id',
//         'first_name',
//         'middle_name',
//         'last_name',
//         'suffix',
//         'address',
//         'email',
//         'contact',
//         'guardian_name',
//         'guardian_contact',
//         'year',
//         'strand',
//         'last_school_attended',
//         'status',
//     ];
//     public function user()
//     {
//         return $this->belongsTo(User::class, 'user_id');
//     }
// }
