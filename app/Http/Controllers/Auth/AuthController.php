<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json(['token' => $token]);
            }
            return response()->json(['error' => 'Invalid credentials'], 401);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Login failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required|string|min:6',
            ]);
            if ($data['password'] !== $data['password_confirmation']) {
                return response()->json(['error' => 'Password confirmation does not match'], 400);
            }
            unset($data['password_confirmation']);
            $user = \App\Models\User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Registration failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function user()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            return UserResource::make($user);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch user', 'message' => $e->getMessage()], 500);
        }
    }


    public function logout()
    {
        try {
            $user = Auth::user();
            if ($user) {
                $user->tokens()->delete();
                return response()->json(['message' => 'Logged out successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Logout failed', 'message' => $e->getMessage()], 500);
        }
    }
}
