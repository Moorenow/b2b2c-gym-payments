<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user and assign the default role.
     */
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $role = $request->input('role', User::ROLE_GYM_ADMIN);

        $user = User::registerWithRole($data, $role);
        $token = $user->issueToken();

        return response()->json([
            'user' => $user->load('roles'),
            'token' => $token,
        ], 201);
    }

    /**
     * Generate a Sanctum token for a valid user.
     */
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::attemptLogin($credentials['email'], $credentials['password']);

        if ($user === null) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        return response()->json([
            'user' => $user->load('roles'),
            'token' => $user->issueToken(),
        ]);
    }

    /**
     * Revoke the current access token.
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $user->revokeCurrentAccessToken();

        return response()->json(['message' => 'Sesión cerrada.']);
    }
}
