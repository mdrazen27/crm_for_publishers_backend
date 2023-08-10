<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateAdminUserRequest extends FormRequest
{

    public function authorization(): bool
    {
        return Auth::user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                Rule::unique('users', 'email')->ignore($this->admin_user->id),
                'email',
                'max:255'
            ],
        ];
    }
}
