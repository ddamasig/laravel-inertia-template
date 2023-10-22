<?php

namespace App\Services;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UserService
{
    use PasswordValidationRules;

    public static function create(array $input)
    {
        self::createModel($input);
    }

    public static function createModel(array $input)
    {
        $whitelistedKeys = Arr::only($input, [
            'first_name',
            'middle_name',
            'last_name',
            'email',
            'mobile_number',
            'province_id',
            'municipality_id',
            'username',
            'password',
        ]);
        return User::firstOrCreate($whitelistedKeys);
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public static function uploadAvatar(User $user, UploadedFile $avatar): Media
    {
        return $user->addMedia($avatar)
            ->toMediaCollection('avatar');
    }
}
