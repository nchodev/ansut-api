<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGrade extends Model
{
   protected $fillable =[
        'user_id',
        'grade_id'
    ];
}
