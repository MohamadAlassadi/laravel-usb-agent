<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Http\Resources\UserResource;
use Exception;

class AuthService
{
    public function login(array $data)
    {  
        try {
            $email = $data['email'];
            $password = $data['password'];

            $user = User::where('email', $email)->first();

            if (!$user) {
                Log::error('Auth-004: user not found');
                return ['success' => false, 'message' => 'Invalid credentials'];
            } 

            if (!Hash::check($password, $user->password)) {
                Log::error('Auth-005: Incorrect password');
                return ['success' => false, 'message' => 'Invalid credentials'];
            } 
            $user->tokens()->delete();
            $token = $user->createToken('access_token')->plainTextToken;
            $user->remember_token = $token;
            $user->save();

            return [
                'success' => true,
                'message' => 'Login successful',
                'access_token' => $token,
                'user' => new UserResource($user),
            ];
        } catch (Exception $e) {
            Log::error('Auth-006: Error during user login', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ];
        }
    }

    public function logout($user)
    {
        try {
            $user->tokens()->delete();
            $user->currentAccessToken()->delete();
            $user->remember_token = null;
            $user->save();
        } catch (Exception $e) {
            Log::error('Auth-007: Error during user logout', ['error' => $e->getMessage()]);
            return [
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ];
        }
    }
}