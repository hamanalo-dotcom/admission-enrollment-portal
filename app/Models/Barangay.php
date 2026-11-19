<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $table = 'refbrgy';
    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo(City::class, 'citymunCode', 'citymunCode');
    }
}
