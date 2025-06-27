<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCity extends Model
{
     protected $fillable =[
        'user_id',
        'city_id'
    ];
}
