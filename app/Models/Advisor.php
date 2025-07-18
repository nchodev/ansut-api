<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advisor extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'specialty',
        'bio'
    ];

    public function schools()
    {
        return $this->belongsToMany(School::class, 'advisor_school');
    }

}
