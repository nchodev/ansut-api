<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
  /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone_number',
        'date_of_birth',
        'login_provider',
        'provider_id',
        'status',
        'email_verified_at',
        'phone_verified_at',
        'profile_picture',
        'password',
        'lang', // URL CDN ou locale
        'last_login_at',
        'username', // Pseudo pour login via pseudo
        'role',
         'matricule',
         'badge',
         'fcm_token',
         'social_statut_id',
         'mother_tongue_id',
         'grade_id',
         'city_id',
         'school_id',
         'is_period_active'
    ];
    

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }


    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
     public function socialStatut()
    {
        return $this->belongsTo(SocialStatut::class);
    }
    public function motherTongue()
    {
        return $this->belongsTo(MotherTongue::class);
    }
}
