<?php

namespace App\Http\Actions;

use App\Models\User;

class UpdateUserAction
{
    public function execute(User $user, string $email, int $updateUserId)
    {
        $user->email = $email;
        $user->update_user_id = $updateUserId;
        $user->save();
        return $user;
    }

}
