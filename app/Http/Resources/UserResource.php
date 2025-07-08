<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

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
            "date_of_birth" => (String) Carbon::parse($this->date_of_birth)->age,
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
            'is_period'=>$this->is_period_active,
            'city' => $this->whenLoaded('city', function () {
                return [
                    'id'=>$this->city->id,
                    'name'=>$this->city->name,
                ];
            }),
            'school' => $this->whenLoaded('school', function () {
                return [
                    'id'=>$this->school->id,
                    'name'=>$this->school->name,
                ];
            }),
            'grade' => $this->whenLoaded('grade', function () {
                return [
                    'id'=>$this->grade->id,
                    'name'=>$this->grade->name,
                ];
            }),
            'mother_tongue' => $this->whenLoaded('motherTongue', function () {
                return [
                    'id'=>$this->motherTongue->id,
                    'name'=>$this->motherTongue->name,
                ];
            }),
             'social_statut' => $this->whenLoaded('socialStatut', function () {
                return [
                    'id'=>$this->socialStatut->id,
                    'name'=>$this->socialStatut->name,
                ];
            }),
        ];
    }
 }
