<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "full_name" => $this->full_name,
            "username" => $this->username,
            "email" => $this->email,
            "phone_number" => $this->phone_number,
            "fcm_token" => $this->fcm_token,
            "email_verified_at" => $this->email_verified_at,
            "date_of_birth" => $this->date_of_birth,
            "matricule" => $this->matricule,
            "badge" => $this->badge,
            "login_provider" => $this->login_provider,
            "provider_id" => $this->provider_id,
            "status" => $this->status,
            "profile_picture" => $this->profile_picture,
            "lang" => $this->lang,
            "last_login_at" => $this->last_login_at,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "role" => $this->role,
            "city_id" => $this->city_id,
            "school_id" => $this->school_id,
            "grade_id" => $this->grade_id,
            "mother_tongue_id" => $this->mother_tongue_id,
            "social_statut_id" => $this->social_statut_id
        ];
    }
 }
