<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(AuthRequest $request): JsonResponse
    {
        $user = User::where('email', 'like', $request->email)->first();
        if (!Hash::check($request->password, $user->password)) {
            return new JsonResponse([
                'message' => 'Wrong password!',
                'success' => false
            ], 422);
        }
        if ($user->role_id === 2) {
            if (!$user->publisher->isActive()) {
                return new JsonResponse([
                    'message' => 'Your account has been suspended!',
                    'success' => false
                ], 422);
            }
        }

        return new JsonResponse(
            [
                'message' => 'Logged in!',
                'accessToken' => $user->createJwtToken(),
                'user' => UserResource::make($user),
            ]
        );
    }

    public function unauthenticated():JsonResponse
    {
        return new JsonResponse(
            [
                'message' => 'Unauthenticated.',
            ]
        );
    }
}
