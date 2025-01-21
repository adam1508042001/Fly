<?php

namespace App\Http\Controllers\Auth;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Ces identifiants ne correspondent à aucun utilisateur'
            ], 401);
        }

        $client = Auth::user(); 
        $token = $client->createToken('auth_token')->plainTextToken;

        return response()->json([
            'client' => [
                'id' => $client->id,
                'first_name' => $client->first_name,
                'last_name' => $client->last_name,
                'date_of_birth' => $client->date_of_birth,
                'email' => $client->email,
                'status' => $client->status,
            ],
            'access_token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Token supprimé'
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        Log::info('Validated data:', $validated);

        try {
            $client = Client::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => 'client',
            ]);

            Log::info('Client created:', $client->toArray());

            return response()->json([
                'message' => 'Client successfully registered',
                'client' => $client,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating client:', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Internal Server Error',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}