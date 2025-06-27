<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotherTongue extends Model
{
     protected $fillable = ['name','status'];

      public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
