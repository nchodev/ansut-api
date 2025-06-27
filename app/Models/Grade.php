<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ["name","school_id","status"];
     public function school()
    {
        return $this->belongsTo(School::class);
    }
      public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
