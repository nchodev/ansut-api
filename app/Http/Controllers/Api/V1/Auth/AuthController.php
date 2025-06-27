<?php

namespace App\Http\Controllers\api\v1\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Utils\Helpers;
use App\Http\Resources\LiveTypeResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\LiveStreamResource;
use App\Http\Resources\UserResource;
use App\Models\City;
use App\Models\Grade;
use App\Models\MotherTongue;
use App\Models\School;
use App\Models\SocialStatut;
use Illuminate\Validation\ValidationException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'social' => 'required|string|max:255',
            'birthdate' => 'required|string|max:255',
            'city' => 'required',
            'grade' => 'required',
            'school' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => Helpers::error_processor($validator)
            ], 403);
        }

        $validated = $validator->validated();

        $user = User::create([
            'full_name' => $validated['full_name'],
            'nick_name' => $validated['nick_name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 201);
    }
    public function registerWithOAuth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required_if:login_provider,google,facebook',
            'login_provider' => 'required|in:google,facebook,apple',
            'provider_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => Helpers::error_processor($validator)
            ], 403);
        }
        
        $token = $request['token'];
        $email = $request->email;
        $provider = $request->login_provider;
        $unique_id = $request['provider_id'];
        $lang = $request->header('x-localization')?? 'fr'; // Utilise la locale de l'en-tête ou la locale par défaut
        
       // Recherche d'un utilisateur existant via provider_id
            $user = User::where('login_provider', $provider)
                        ->where('provider_id', $unique_id)
                        ->first();
            if (!$user) {
                // Recherche via email si user déjà enregistré
                $user = User::where('email', $email)->first();

                if (!$user) {
                    // Récupère l'utilisateur via le token d'accès (id_token ou access_token)
                    if ($provider === 'google') {
                            try {
                                $res = $this->client->request('GET', 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $token);
                                $data = json_decode($res->getBody()->getContents(), true);
                                } catch (\Exception $e) {
                                    // Log::error('Erreur lors de la récupération des informations utilisateur via OAuth', ['exception' => $e->getMessage()]);
                                    return response()->json(['error' => 'wrong credential', 'message' => $e->getMessage()], 403);
                                }
                        } else if ($request['medium'] == 'facebook') {
                            try {
                                $res = $this->client->request('GET', 'https://graph.facebook.com/' . $unique_id . '?access_token=' . $token . '&&fields=name,email');
                                $data = json_decode($res->getBody()->getContents(), true);
                                $data['given_name'] = $data['name'];
                                } catch (\Exception $e) {
                                    // Log::error('Erreur lors de la récupération des informations utilisateur via OAuth', ['exception' => $e->getMessage()]);
                                    return response()->json(['error' => 'wrong credential', 'message' => $e->getMessage()], 403);
                                }
                        }

                    // Création d'un nouvel utilisateur
                    $user = User::create([
                        'full_name'     => $data['name'],
                        'email'         => $email,
                        'email_verified_at' => now(),
                        'password'      => null, // pas de mot de passe pour OAuth
                        'login_provider'      => $provider,
                        'lang' => $lang, // Utilise la locale de l'en-tête ou la locale par défaut
                        'provider_id'   => $unique_id,
                        'status'=>0
                    ]);
                } else {
                    // Met à jour les infos d'OAuth si le user existe par email
                    $user->update([
                        'provider' => $provider,
                        'provider_id' => $unique_id,
                    ]);
                }
            }
        // Met à jour le dernier login
        $user->last_login_at = now();
        $user->lang = $lang; // Utilise la locale de l'en-tête ou la locale par défaut
        $user->save();
            // Création d’un token Laravel Sanctum ou Passport
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }   

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|string|email',
            'username' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => Helpers::error_processor($validator)
            ], 403);
        }
        $lang = $request->header('x-localization');
        $validated = $validator->validated();

      if(isset($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
            if($user && $user->status == 0) // Vérifie si l'utilisateur est actif
            {
                // l'utilisateur doit terminer son inscription
                return response()->json([
                    'status' => $user->status,
                    'message' => 'Vous devez terminer votre inscription avant de vous connecter.',
                ], 200);
            } else if($user && $user->status == 2) // l'utilisateur est banni
            {    
                return response()->json([
                    'status' => $user->status,
                    'message' => 'Votre compte a été banni. Veuillez contacter le support pour plus d\'informations.',
                ], 200);
            }
            
            if($user && $user->password==null) // Si utilisateur trouvé mais il utilise password pour la connexion     
            {
                //envoyer un otp pour creation de password via email
                $otp = rand(100000, 999999); // Génère un OTP aléatoire
                // Enregistre l'OTP dans la base de données ou dans un service de cache
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $user->email],
                    ['token' => $otp, 'created_at' => now()]
                );
                // Envoie l'OTP par e-mail
                // Vous pouvez utiliser un service de notification ou envoyer un e-mail directement
                // Mail::to($user->email)->send(new OtpMail($otp));
                // Pour l'exemple, on va juste retourner l'OTP dans la réponse
                return response()->json([
                    'status' => 3,
                    'otp' => $otp, // Retourne l'OTP pour le test
                    'message' => 'un Otp a été envoyé à votre adresse e-mail pour créer un mot de passe.',
                ], 200);

            }


      } elseif (isset($validated['username'])) {
            $user = User::where('username', $validated['username'])->first();

            // Vérifie si l'utilisateur est actif
            
      } elseif (isset($validated['phone'])) {
            $user = User::where('phone_number', $validated['phone'])->first();
           

            // Vérifie si l'utilisateur est actif
            if($user && $user->status == 0) 
            {
                // l'utilisateur doit terminer son inscription
                return response()->json([
                    'status' => $user->status,
                    'message' => 'Vous devez terminer votre inscription avant de vous connecter.',
                ], 200);
            } else if($user && $user->status == 2) // l'utilisateur est banni
            {    
                return response()->json([
                    'status' => $user->status,
                    'message' => 'Votre compte a été banni. Veuillez contacter le support pour plus d\'informations.',
                ], 200);
            }
            
            if($user && $user->password==null) // Si utilisateur trouvé mais il utilise password pour la connexion     
            {
                //envoyer un otp pour creation de password via email
                $otp = rand(100000, 999999); // Génère un OTP aléatoire
                // Enregistre l'OTP dans la base de données ou dans un service de cache
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['phone' => $user->phone_number],
                    ['token' => $otp, 'created_at' => now()]
                );
                // Envoie l'OTP par e-mail
                // Vous pouvez utiliser un service de notification ou envoyer un e-mail directement
                // Mail::to($user->email)->send(new OtpMail($otp));
                // Pour l'exemple, on va juste retourner l'OTP dans la réponse
                return response()->json([
                    'status' => 3,
                    'otp' => $otp, // Retourne l'OTP pour le test
                    'message' => 'un Otp a été envoyé à votre adresse e-mail pour créer un mot de passe.',
                ], 200);

            }
      }

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'auth',
                        'message' => 'invalid credentials',
                    ]
                ]
            ], 401);
        }
        // Met à jour le dernier login
        $user->last_login_at = now();
        $user->lang = $lang; // Utilise la locale de l'en-tête ou la locale par défaut
        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ], 200);
    }
    public function sendSocialRegisterOTP(Request $request)
    {

        if ( empty($request['phone'])) {
            return response()->json([ 'message' => 'Le numéro de téléphone est réquis.',
            ], 403);
        }

        if (!empty($request['phone'])) {
           $user =User::where('phone_number',$request['phone'])->first();
            if($user)
            {
              return response()->json([ 'message' => 'Le numéro de téléphone est déjà utilisé.'], 403); 
            }

            $otp = rand(100000, 999999);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['phone' => $request['phone']],
                ['token' => $otp, 'created_at' => now()]
            );
        }
            // Send OTP via SMS here
            return response()->json([
                'status' => 1,
                'otp' => $otp,
                'message' => 'OTP sent to your phone number.',
            ], 200);
        
    }
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|numeric',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => Helpers::error_processor($validator)
            ], 403);
        }

        $validated = $validator->validated();

        if (isset($validated['email'])) {
            $record = DB::table('password_reset_tokens')
                ->where('email', $validated['email'])
                ->where('token', $validated['otp'])
                ->first();
        } elseif (isset($validated['phone'])) {
            $record = DB::table('password_reset_tokens')
                ->where('phone', $validated['phone'])
                ->where('token', $validated['otp'])
                ->first();
        } else {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'otp',
                        'message' => 'Email or phone is required.'
                    ]
                ]
            ], 403);
        }

        if (!$record) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'otp',
                        'message' => 'Invalid OTP.'
                    ]
                ]
            ], 401);
        }
       
        // Optionally, check OTP expiration here

        // OTP is valid, you can delete it if you want
        if (isset($validated['email'])) {
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();
        } else {
            DB::table('password_reset_tokens')->where('phone', $validated['phone'])->delete();
        }

        return response()->json([
            'status' => 1,
            'message' => 'OTP verified successfully.'
        ], 200);
    }
   public function update_cm_firebase_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => Helpers::error_processor($validator)], 403);
        }

        DB::table('users')->where('id',$request->user()->id)->update([
            'fcm_token'=>$request['cm_firebase_token']
        ]);

        return response()->json(['message' => 'mise à jour réussie!'], 200);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // get city
    public function getCities()
    {
        $cities = City::Active()->get();
        return response()->json([
            'cities' => $cities
        ], 200);
    }
    // get school by cityId
    public function getSchoolById($id)
    {

        $schools = School::Active()->where('city_id', $id)->get();

        return response()->json([
            'schools' => $schools
        ], 200);
    }
    // get grade by school id
    public function getSchoolGrades($id)
    {
    
        $grades = Grade::Active()->where('school_id', $id)->get();
        if (!$grades) {
            return response()->json([
                'errors' => [
                    [
                        'code' => 'school',
                        'message' => 'School not found.'
                    ]
                ]
            ], 404);
        }

        return response()->json([
            'grades' => $grades
        ], 200);
    }
    // get social statut
    public function getSocialStatut(){
        $social = SocialStatut::Active()->get();
        return response()->json([
            'socials'=>$social,
        ], 200);

    }
     // get mother tongue
    public function getMotherTongues(){
        $social = MotherTongue::Active()->get();
        return response()->json([
            'tongues'=>$social,
        ], 200);

    }
}