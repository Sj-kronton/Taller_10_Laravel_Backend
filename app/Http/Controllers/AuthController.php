<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // RF 1.1: inicio de sesión
    public function login(Request $request): JsonResponse
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $credenciales['email'])->first();

        if (! $user || ! Hash::check($credenciales['password'], $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas.'], 401);
        }

        $token = $user->createToken('eventhub-api')->plainTextToken;

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token,
        ], 200);
    }

    // RF 1.2: consulta de perfil (ruta protegida con auth:sanctum)
    public function perfil(Request $request): JsonResponse
    {
        return response()->json($request->user(), 200);
    }
}