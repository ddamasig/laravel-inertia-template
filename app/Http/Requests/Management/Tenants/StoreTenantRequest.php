<?php

namespace App\Http\Requests\Management\Tenants;

use App\Services\Admin\TenantService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;

class StoreTenantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(Request $request): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'domain' => ['required', 'string', 'max:16'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:tenants'],
            'mobile_number' => ['required', 'string', 'max:11'],
            'province_id' => ['required'],
            'municipality_id' => ['required'],
            'additional_address_information' => ['nullable', 'string', 'max:512'],
            'max_sub_accounts' => ['required', 'int', 'max:512'],
            'market_plan' => [
                'required',
                "in:" . TenantService::getValidMarketPlansString(),
            ],
//            'password' => [
//                Rule::excludeIf($request->password_mode !== 'manual'),
//                'required',
//                'min:8',
//                'confirmed'
//            ],
            'logo' => [
                'nullable',
                File::types(['png', 'jpg', 'bmp'])
                    ->max(3 * 1024)
            ],
        ];
    }
}
