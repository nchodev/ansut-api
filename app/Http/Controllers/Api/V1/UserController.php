<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SocialStatut;
use App\Models\UserCity;
use App\Models\UserGrade;
use App\Models\UserMotherTongue;
use App\Models\UserSchool;
use App\Models\UserSocialStatut;
use Illuminate\Support\Facades\DB;
use App\Utils\Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    

    public function register(Request $request)  
    {
        DB::beginTransaction();

        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'social' => 'required',
                'phone' => 'required',
                'matricule' => 'required',
                'birthdate' => 'required',
                'city' => 'required',
                'school' => 'required',
                'grade' => 'required',
                'fullName' => 'nullable',
                'tongue' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => Helpers::error_processor($validator)
                ], 403);
            }

            $validated = $validator->validated();

            $user = $request->user();
            $user->update([
                'full_name' => $validated['fullName'] ?? $user->full_name,
                'phone_number' => $validated['phone'],
                'matricule' => $validated['matricule'],
                'date_of_birth' => $validated['birthdate'],
               'phone_verified_at'=>now(),
                'status' => 1,
            ]);

            UserSocialStatut::updateOrCreate(
                ['user_id' => $user->id],
                ['social_statut_id' => $request->social]
            );

            UserCity::updateOrCreate(
                ['user_id' => $user->id],
                ['city_id' => $request->city]
            );

            UserSchool::updateOrCreate(
                ['user_id' => $user->id],
                ['school_id' => $request->school]
            );

            UserGrade::updateOrCreate(
                ['user_id' => $user->id],
                ['grade_id' => $request->grade]
            );
            UserMotherTongue::updateOrCreate(
                ['user_id' => $user->id],
                ['mother_tongue_id' => $request->tongue]
            );

            DB::commit();

            return response()->json([
                'user' => $user->fresh()
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur est survenue.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
