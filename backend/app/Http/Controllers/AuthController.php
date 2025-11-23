<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\Verify2FARequest;
use App\Http\Requests\RefreshTokenRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'user' => $user,
            'access_token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } catch (JWTException) {
            return response()->json(['message' => 'Could not create token'], 500);
        }

        return response()->json([
            'access_token' => $token,
            'user' => auth()->user(),
        ]);
    }

    public function refresh(RefreshTokenRequest $request)
    {
        try {
            $token = JWTAuth::parseToken()->refresh();
            return response()->json(['access_token' => $token]);
        } catch (JWTException) {
            return response()->json(['message' => 'Token refresh failed'], 401);
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->validated());

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json(['message' => 'Reset link sent']);
        }

        return response()->json(['message' => 'Unable to send reset link'], 500);
    }

    public function verify2fa(Verify2FARequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!$user->two_factor_enabled) {
            return response()->json(['message' => '2FA not enabled'], 400);
        }

        if (!hash_equals($user->two_factor_code ?? '', $request->input('token'))) {
            return response()->json(['message' => 'Invalid 2FA token'], 401);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json(['access_token' => $token, 'user' => $user]);
    }
}
