<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function execute(string $email, string $password, int $createUserId, int $role_id): User
    {
        $user = new User;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->create_user_id = $createUserId;
        $user->role_id = $role_id;
        $user->save();
        return $user;
    }
}
