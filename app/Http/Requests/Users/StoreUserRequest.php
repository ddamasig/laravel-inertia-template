<?php

namespace App\Http\Requests\Users;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile_number' => ['required', 'string', 'max:11'],
            'province_id' => ['required'],
            'municipality_id' => ['required'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password_mode' => ['required', 'in:temporary,manual'],
            'password' => ['required', 'min:8', 'confirmed'],
            'avatar' => [
                'required',
                File::types(['png', 'jpg', 'bmp'])
                    ->max(3 * 1024)
            ],
        ];
    }
}
