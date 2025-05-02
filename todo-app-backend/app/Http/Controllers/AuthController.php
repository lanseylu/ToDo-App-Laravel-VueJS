<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{

    public function register(RegisterRequest $request): JsonResponse
    {
        try
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            return response()->json([
                'message' => 'Registration successful!', 
                'user' => $user->only(['id', 'name', 'email'])
            ], 201);
        } 
        catch (\Illuminate\Validation\ValidationException $e) 
        {
            return response()->json(['errors' => $e->errors()], 422);
        }
        catch (\Exception $e)
        {   
            Log::error('Registration failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unexpected error. Please try again later.'
            ], 500);
        }
            
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try
        {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'message' => 'Invalid credentials. Please try again.'
                ], 401);
            }

            $token = $user->createToken('ToDoAppToken')->plainTextToken;

            return response()->json([
                'user' => $user->only(['id', 'name', 'email']), 
                'token' => $token
            ]);
            
            
        }
        catch (\Exception $e)
        {
            Log::error('Login failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Login failed. Please try again later.'
            ], 500);
        }
            
    }

    public function logout(Request $request): JsonResponse
    {
        try
        {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logged out successfully!'
            ]);
        }
        catch(\Exception $e)
        {
            Log::error('Logout failed: ' . $e->getMessage());

            return response()->json([
                'message' => 'Logout failed. Please try again later.'
            ], 500);
        }
    }
}

