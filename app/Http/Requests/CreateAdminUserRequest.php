<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateAdminUserRequest extends FormRequest
{

    public function authorization(): bool
    {
        return Auth::user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string|unique:users,email|email|max:255',
            'password' => 'required|string|max:255',
        ];
    }
}
