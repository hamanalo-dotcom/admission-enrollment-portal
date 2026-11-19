<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'refregion';
    protected $fillable = ['psgcCode', 'regDesc', 'regCode'];

    public function provinces()
    {
        return $this->hasMany(Province::class, 'regCode', 'regCode');
    }
}


// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Region extends Model
// {
//     protected $table = 'refregion'; // pangalan ng table
//     protected $primaryKey = 'regCode'; // kung primary key mo ay regCode
//     public $incrementing = false; // kasi hindi auto increment si regCode
//     protected $keyType = 'string';

//     public function provinces()
//     {
//         return $this->hasMany(Province::class, 'regCode', 'regCode');
//     }
// }
