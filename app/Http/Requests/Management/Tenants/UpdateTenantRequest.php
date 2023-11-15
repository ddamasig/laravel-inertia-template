<?php

namespace App\Http\Requests\Management\Users;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;

class UpdateTenantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        $user = $this->route('user');
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'mobile_number' => ['required', 'string', 'max:11', 'unique:users,mobile_number,' . $user->id],
            'province_id' => ['required'],
            'municipality_id' => ['required'],
            'role_id' => ['required'],
            'status' => ['required'],
            'username' => ['required', 'string', 'max:255', 'unique:users,email,' . $user->id],
            'password_mode' => [
                'required',
                'in:temporary,manual,unchanged',
            ],
            'password' => [
                Rule::excludeIf($request->password_mode !== 'manual'),
                'required',
                'min:8',
                'confirmed'
            ],
            'avatar' => [
                'nullable',
                File::types(['png', 'jpg', 'bmp'])
                    ->max(3 * 1024)
            ],
        ];
    }
}
