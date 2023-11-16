<?php

namespace App\Services\Admin;

use App\Actions\Fortify\PasswordValidationRules;
use App\Exceptions\MissingEmailException;
use App\Models\Tenant;
use App\Services\UserService;
use Illuminate\Support\Arr;

class TenantService
{
    use PasswordValidationRules;

    public static function getValidRegionTaggingBonusCommissionModes(): array
    {
        return [
            Tenant::REGION_TAGGING_BONUS_COMMISSION_MODE_FIXED_VALUE,
            Tenant::REGION_TAGGING_BONUS_COMMISSION_MODE_PERCENTAGE,
        ];
    }

    public static function getValidRegionTaggingBonusCommissionModesString(): string
    {
        return implode(',', self::getValidRegionTaggingBonusCommissionModes());
    }

    public static function getValidMarketPlans(): array
    {
        return [
            Tenant::MARKET_PLAN_BINARY,
            Tenant::MARKET_PLAN_TRADITIONAL,
        ];
    }

    public static function getValidMarketPlansString(): string
    {
        return implode(',', self::getValidMarketPlans());
    }


    public static function create(array $input): Tenant
    {
        $tenant = self::createTenantModel($input);
//        $owner = UserService::create($input);
        return $tenant->refresh();
    }

    /**
     * Creates a Tenant object in the database.
     * @param array $input
     * @return Tenant
     */
    public static function createTenantModel(array $input): Tenant
    {
        $payload = Arr::only($input, [
            // Basic Information
            'name',
            'domain',
            'database',
            'contact_person',
            'email',
            'mobile_number',
            'province_id',
            'municipality_id',
            'additional_address_information',
            // Feature Configuration
            'market_plan',
            'max_sub_accounts',
            'account_upgrades_enabled',
            'can_only_upgrade_once',
            'account_levels',
            'direct_referral_bonus_enabled',
            'direct_referral_bonus_amount',
            'pairing_bonus_enabled',
            'pairing_bonus_amount',
            'pairing_bonus_max_pairs',
            'pairing_bonus_fifth_pairs_enabled',
            'flush_out_enabled',
            'pairing_bonus_max_waiting_points',
            'infinity_bonus_enabled',
            'infinity_bonus_amount',
            'infinity_bonus_starting_level',
            'region_tagging_bonus_enabled',
            'region_tagging_bonus_commission_mode',
            'region_tagging_bonus_amount',
            // Services
            'ticketing_enabled',
            'eloading_enabled',
            'insurance_enabled',
            'bills_payment_enabled',
            // Brand Customization
            'logo_url',
            'color_primary',
            'color_success',
            'color_error',
            'color_info',
            'color_warning',
        ]);

        $tenant = Tenant::create($payload);

        return $tenant->refresh();
    }

    /**
     * Updates the Tenant object in the database.
     * @param Tenant $tenant
     * @param array $input
     * @return Tenant
     */
    public static function updateModel(Tenant $tenant, array $input): Tenant
    {
        $tenant->first_name = $input['first_name'];
        $tenant->middle_name = $input['middle_name'];
        $tenant->last_name = $input['last_name'];
        $tenant->email = $input['email'];
        $tenant->username = $input['username'];
        $tenant->mobile_number = $input['mobile_number'];
        $tenant->province_id = $input['province_id'];
        $tenant->municipality_id = $input['municipality_id'];
        $tenant->save();
    }

}
