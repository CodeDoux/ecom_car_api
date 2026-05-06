<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;




class AuthController extends Controller
{
    protected $userService;
    public function __construct()
    {
        $this->userService=new UserService();
    }
    public function index()
    {
        $users=$this->userService->index();
        return response()->json($users,200);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomComplet' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:5',
            'role' => 'in:ADMIN,CLIENT',
            'phone' => ['required','string','max:20','regex:/^(\+221|00221)?[0-9\s-]{9,}$/']
        ]);
        $user = User::create([
            'nomComplet' => $validated['nomComplet'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => strtoupper($request->role ?? 'CLIENT'),
            'phone' => $validated['phone'],
        ]);
     return response()->json($user,201,[], JSON_UNESCAPED_UNICODE);
    }
    // Enregistrement
    public function register(Request $request)
    {
        $validated = $request->validate([
            'nomComplet' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:5',
            'role' => 'in:ADMIN,CLIENT',
            'phone' => ['required','string','max:20','regex:/^(\+221|00221)?[0-9\s-]{9,}$/']
        ]);
        $user = User::create([
            'nomComplet' => $validated['nomComplet'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => strtoupper($request->role ?? 'CLIENT'),
            'phone' => $validated['phone'],
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
// Connexion
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations sont incorrectes.']
            ]);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user'  => [
                'id'    => $user->id,
                'email' => $user->email,
                'role'  => $user->role, 
            ]
        ]);
    }
// Déconnexion
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'message' => 'Déconnecté avec succès'
        ]);
    }
    public function show(string $id)
    {
        $user = $this->userService->show($id);
        return response()->json($user,200,[], JSON_UNESCAPED_UNICODE);
    }
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nomComplet' => 'required|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$id,
            'password' => 'required|string|min:5',
            'role' => 'in:ADMIN,CLIENT',
            'phone' => ['required','string','max:20','regex:/^(\+221|00221)?[0-9\s-]{9,}$/']
        ]);
        $user= $this->userService->update($validated, $id);

        return response()->json([
            "message" => "user mise à jour",
            "user" => $user
        ],status: 201);
    }
     public function destroy(string $id)
    {
        $this->userService->destroy($id);
        return response()->json("",204);
    }

    /**
     * Get current user
     */
    public function getUser(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Utilisateur non authentifié.'
            ], 401);
        }

        return response()->json([
            'id'         => $user->id,
            'nomComplet'  => $user->nomComplet,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'role'       => $user->role,      
            'created_at' => $user->created_at,
        ]);
    }
}
