<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable =["name",'status'];

      public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
// la liste des users appartenant à une ville
  public function users()
    {
        return $this->hasMany(User::class);
    }

    protected $hidden = [
        'created_at',
        'updated_at',
        'status'
    ];
    

}
