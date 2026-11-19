<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionStatusHistory extends Model
{
    protected $fillable = [
        'admission_id',
        'user_id',
        'from_status',
        'to_status',
        'comment',
    ];

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


