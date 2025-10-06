<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Exception;

class AuthController extends ApiController
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login($request->validated());

            if (!$data['success']) {
                return $this->errorResponse('Auth-404', $data['message'], 404);
            }
        
            return $this->successResponse($data, 'Login successful');
        } catch (Exception $e) {
            Log::error('Auth-002: Error in user login', ['error' => $e->getMessage()]);
            return $this->errorResponse('Auth-400', 'Error in user login');
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());
            return $this->successResponse(null, 'Logout successful');
        } catch (Exception $e) {
            Log::error('Auth-003: user Logout Error', ['error' => $e->getMessage()]);
            return $this->errorResponse('Auth-400','user Logout Error');
        }
    }
}