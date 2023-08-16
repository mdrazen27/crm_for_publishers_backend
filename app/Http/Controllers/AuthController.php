<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
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
            ], 403);
        }
        if ($user->role_id === 2) {
            if (!$user->publisher) {
                return new JsonResponse([
                    'message' => 'Your account has been terminated!',
                    'success' => false
                ], 403);
            }
            if (!$user->publisher->isActive()) {
                return new JsonResponse([
                    'message' => 'Your account has been suspended!',
                    'success' => false
                ], 403);
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

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = Auth::user();
        if (!Hash::check($request->oldPassword, $user->password)) {
            return new JsonResponse([
                'message' => 'Wrong password!',
                'success' => false
            ], 403);
        }
        $user->password = $request->newPassword;
        $user->save();

        return new JsonResponse(
            [
                'message' => 'Successfully changed password!',
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
