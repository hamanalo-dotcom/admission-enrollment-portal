<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'refcitymun';
    public $timestamps = false;

    public function province()
    {
        return $this->belongsTo(Province::class, 'provCode', 'provCode');
    }

    public function barangays()
    {
        return $this->hasMany(Barangay::class, 'citymunCode', 'citymunCode');
    }
}
