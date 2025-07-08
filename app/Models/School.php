<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ["name","status","city_id"];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function advisors()
    {
        return $this->belongsToMany(Advisor::class, 'advisor_school');
    }

     protected $hidden = [
        'created_at',
        'updated_at',
        'status'
    ];

}
