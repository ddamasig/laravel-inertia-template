<?php

namespace App\Http\Requests\Management\Tenants;

use App\Services\Admin\TenantService;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $rules = [
            'name' => ['required', 'string', 'max:256'],
            'domain' => ['required', 'string', 'max:125'],
            'database' => ['required', 'string', 'max:64'],
            'contact_person' => ['required', 'string', 'max:256'],
            'email' => ['required', 'string', 'email', 'max:256', 'unique:custom_tenants'],
            'mobile_number' => ['required', 'string', 'max:11'],
            'province_id' => ['required'],
            'municipality_id' => ['required'],
            'additional_address_information' => ['nullable', 'string', 'max:512'],
            'max_sub_accounts' => ['required', 'int', 'max:512'],
            'market_plan' => [
                'required',
                "in:" . TenantService::getValidMarketPlansString(),
            ],
            'ticketing_enabled' => [
                'required',
                'boolean',
            ],
            'eloading_enabled' => [
                'required',
                'boolean',
            ],
            'insurance_enabled' => [
                'required',
                'boolean',
            ],
            'bills_payment_enabled' => [
                'required',
                'boolean',
            ],
            'logo' => [
                'nullable',
                File::types(['png', 'jpg', 'bmp'])
                    ->max(3 * 1024)
            ],
            'color_primary' => [
                'required',
                'max:7'
            ],
            'color_success' => [
                'required',
                'max:7'
            ],
            'color_error' => [
                'required',
                'max:7'
            ],
            'color_info' => [
                'required',
                'max:7'
            ],
            'color_warning' => [
                'required',
                'max:7'
            ],
        ];

        $marketPlan = $request->market_plan;
        switch ($marketPlan) {
            case 'binary':
                $rules = array_merge($rules, $this->getBinaryRules($request));
                break;
            default:
                break;
        }

        return $rules;
    }

    public function getBinaryRules(Request $request): array
    {
        $accountUpgradeRule = Rule::excludeIf(!$request->account_upgrades_enabled);
        $directReferralBonusRule = Rule::excludeIf(!$request->direct_referral_bonus_enabled);
        $pairingBonusRule = Rule::excludeIf(!$request->pairing_bonus_enabled);
        $infinityBonusRule = Rule::excludeIf(!$request->infinity_bonus_enabled);
        $regionTaggingBonusRule = Rule::excludeIf(!$request->region_tagging_bonus_enabled);

        return [
            // Account Upgrade Rules
            'can_only_upgrade_once' => [
                $accountUpgradeRule,
                'required',
                'boolean',
            ],
            'account_levels' => [
                $accountUpgradeRule,
                'required',
                'array'
            ],
            'account_levels.*.name' => [
                $accountUpgradeRule,
                'required',
                'string',
                'max:128',
            ],
            'account_levels.*.cost' => [
                $accountUpgradeRule,
                'required',
                'int',
                'max:50000',
            ],

            // Direct Referral Bonus Rules
            'direct_referral_bonus_amount' => [
                $directReferralBonusRule,
                'required',
                'int',
                'max:50000',
            ],

            // Pairing Bonus Rules
            'pairing_bonus_amount' => [
                $pairingBonusRule,
                'required',
                'int',
                'max:50000',
            ],
            'pairing_bonus_max_pairs' => [
                $pairingBonusRule,
                'required',
                'int',
                'max:300',
            ],
            'pairing_bonus_fifth_pairs_enabled' => [
                $pairingBonusRule,
                'required',
                'boolean',
            ],
            'flush_out_enabled' => [
                $pairingBonusRule,
                'required',
                'boolean',
            ],
            'pairing_bonus_max_waiting_points' => [
                $pairingBonusRule,
                'required',
                'int',
                'max:50000',
            ],

            // Infinity Bonus Rules
            'infinity_bonus_amount' => [
                $infinityBonusRule,
                'required',
                'int',
                'max:50000',
            ],
            'infinity_bonus_starting_level' => [
                $infinityBonusRule,
                'required',
                'int',
                'max:50',
            ],

            // Region Tagging Bonus Rules
            'region_tagging_bonus_commission_mode' => [
                $regionTaggingBonusRule,
                'required',
                'in:' . TenantService::getValidRegionTaggingBonusCommissionModesString(),
            ],
            'region_tagging_bonus_amount' => [
                $regionTaggingBonusRule,
                'required',
                'int',
            ],
        ];
    }
}
