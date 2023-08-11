<?php

namespace App\Http\Controllers;

use App\Actions\CreateUserAction;
use App\Actions\UpdateUserAction;
use App\Http\Requests\CreateAdminUserRequest;
use App\Http\Requests\UpdateAdminUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{

    public function __construct(private CreateUserAction $createUserAction, private UpdateUserAction $updateUserAction)
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
        $adminUser = $this->createUserAction->execute($request->email, $request->password, Auth::id(), role_id: 1);
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
        $this->updateUserAction->execute($adminUser, $request->email, Auth::id());
        return UserResource::make($adminUser->refresh());
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
