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
            'full_name' => 'required|string|max:255',
            'date_of_bith' => 'required|string|max:255',
            'city' => 'required',
            'class_level' => 'required',
            'school' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => Helpers::error_processor($validator)
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
                'error' => Helpers::error_processor($validator)
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
            'user' => $user,
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
                'error' => Helpers::error_processor($validator)
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
                'error' => [
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

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}