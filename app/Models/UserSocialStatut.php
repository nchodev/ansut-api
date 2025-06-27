<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSocialStatut extends Model
{
      protected $fillable =[
        'user_id',
        'social_statut_id'
    ];
}
