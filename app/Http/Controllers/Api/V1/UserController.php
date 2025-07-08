<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
                'social_statut_id' => $request->social,
                'city_id' => $request->city,
                'school_id' => $request->school,
                'grade_id' => $request->grade,
                'mother_tongue_id' => $request->tongue
            ]);


            DB::commit();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'user' => new UserResource($user->fresh()),
                'token'=>$token,
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Une erreur est survenue.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserinfo(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié.'
            ], 401);
        }

        $user->load([
            'socialStatut',
            'city',
            'school',
            'grade',
            'motherTongue'
        ]);

        return response()->json([
            'user' => new UserResource($user)
        ], 200);
    }

    public function updateAvatar(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié.'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'avatar' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => Helpers::error_processor($validator)
            ], 422);
        }

        $user->profile_picture= $request->avatar;
        $user->save();
         $user->load([
            'socialStatut',
            'city',
            'school',
            'grade',
            'motherTongue'
        ]);

        return response()->json([
            'message' => 'Avatar mis à jour avec succès.',
            'user' => new UserResource($user)
        ], 200);
    }

}
