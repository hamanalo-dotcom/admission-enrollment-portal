<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'refprovince';
    protected $fillable = ['psgcCode', 'provDesc', 'provCode', 'regCode'];

    public function region()
    {
        return $this->belongsTo(Region::class, 'regCode', 'regCode');
    }
}

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Province extends Model
// {
//     protected $table = 'refprovince';
//     protected $primaryKey = 'provCode';
//     public $incrementing = false;
//     protected $keyType = 'string';

//     public function region()
//     {
//         return $this->belongsTo(Region::class, 'regCode', 'regCode');
//     }

//     public function cities()
//     {
//         return $this->hasMany(City::class, 'provCode', 'provCode');
//     }
// }
