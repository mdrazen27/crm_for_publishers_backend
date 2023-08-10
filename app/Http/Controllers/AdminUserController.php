<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(User::class, 'admin_user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $users = User::where('role_id', 1)->get();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAdminUserRequest $request): UserResource
    {
        $adminUser = new User;
        $adminUser->email = $request->email;
        $adminUser->password = Hash::make($request->password);
        $adminUser->create_user_id = Auth::user()->id;
        $adminUser->role_id = 1;
        $adminUser->save();

        return UserResource::make($adminUser);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $adminUser): UserResource
    {
        return UserResource::make($adminUser);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminUserRequest $request, User $adminUser): UserResource
    {
        $adminUser->email = $request->email;
        $adminUser->update_user_id = Auth::user()->id;
        $adminUser->save();
        return UserResource::make($adminUser);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $adminUser): JsonResponse
    {
        $adminUser->delete();
        return new JsonResponse(['message' => 'User successfully deleted!', 'success' => true]);
    }
}
