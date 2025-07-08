<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Period extends Model
{
    protected $fillable = ['user_id', 'start_date', 'duration', 'next_prediction'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class);
    }
    protected $casts = [
        'next_prediction'=>'date'
    ];
    protected $hidden = [
        "created_at",
        "updated_at"
    ];
}

