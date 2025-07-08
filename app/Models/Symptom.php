<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = ['label', 'emoji'];

    public function periods()
    {
        return $this->belongsToMany(Period::class);
    }

    protected $hidden = [
        "created_at",
        "updated_at"
    ];
}

