<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMotherTongue extends Model
{
     protected $fillable =[
        'user_id',
        'mother_tongue_id'
    ];
}
