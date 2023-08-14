<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'oldPassword' => 'required|min:8|string',
            'newPassword' => 'required|min:8|string',
            'repeatPassword' => 'required|min:8|string|same:newPassword',
        ];
    }

    public function messages(){
        return [
          "repeatPassword.same" => "New and confirmation passwords don't match",
        ];
    }
}
