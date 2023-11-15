<?php

namespace App\Services\Admin;

use App\Actions\Fortify\PasswordValidationRules;
use App\Exceptions\MissingEmailException;
use App\Models\Tenant;
use App\Models\User;
use App\Notifications\AccountDisabledNotification;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;

class TenantService
{
    use PasswordValidationRules;

    public static function create(array $input): Tenant
    {
        $tenant = self::createTenantModel($input);
        $owner = UserService::create($input);
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
            'name',
            'logo_url',
            'market_plan',
            'has_account_levels',
            'infinity_bonus_enabled',
            'pairing_bonus_fifth_pairs_enabled',
            'pairing_bonus_enabled',
            'direct_referral_bonus_enabled',
            'region_tagging_bonus_enabled',
            'flush_out_enabled',
            'market_plan',
            'color_primary',
            'color_primary_faded',
            'color_error',
            'color_success',
            'color_dark',
            'color_background',
            'email',
        ]);

        $tenant = Tenant::firstOrCreate($payload);

        return $tenant->refresh();
    }

    /**
     * Updates the Tenant object in the database.
     * @param Tenant $tenant
     * @param array $input
     * @return Tenant
     * @throws MissingEmailException
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
