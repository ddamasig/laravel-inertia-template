<?php

namespace App\Services;

use App\Actions\Fortify\PasswordValidationRules;
use App\Exceptions\MissingEmailException;
use App\Models\User;
use App\Notifications\AccountDisabledNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Models\Role;

class UserService
{
    use PasswordValidationRules;

    public static function create(array $input)
    {
        self::createModel($input);
    }

    public static function createModel(array $input): User
    {
        $payload = Arr::only($input, [
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

        if (!$payload['password']) {
            $payload['password'] = Hash::make(rand());
        }
        $user = User::firstOrCreate($payload);

        $passwordMode = $input['password_mode'];
        if ($passwordMode === 'manual') {
            self::setPasswordManually($user, $input['password']);
        } else if ($passwordMode === 'temporary') {
            self::sendResetPasswordLink($user);
        }

        // Send a verification email to the new user
        event(new Registered($user));

        return $user->refresh();
    }

    /**
     * @param User $user
     * @param array $input
     * @return User
     * @throws MissingEmailException
     */
    public static function updateModel(User $user, array $input): User
    {
        $user->first_name = $input['first_name'];
        $user->middle_name = $input['middle_name'];
        $user->last_name = $input['last_name'];
        $user->email = $input['email'];
        $user->username = $input['username'];
        $user->mobile_number = $input['mobile_number'];
        $user->province_id = $input['province_id'];
        $user->municipality_id = $input['municipality_id'];
        $user->save();

        $status = $input['status'];
        if ($status === 'active') {
            self::activateUser($user);
        } else if ($status === 'inactive') {
            self::deactivateUser($user);
        }

        $passwordMode = $input['password_mode'];
        if ($passwordMode === 'manual') {
            self::setPasswordManually($user, $input['password']);
        } else if ($passwordMode === 'temporary') {
            self::sendResetPasswordLink($user);
        }

        return $user->refresh();
    }

    /**
     * Updates the User's password.
     * @param User $user
     * @param string $password
     * @return User
     */
    public static function setPasswordManually(User $user, string $password): User
    {
        $user->password = Hash::make($password);
        $user->save();

        return $user->refresh();
    }

    /**
     * Sets the User's password to a random string then sends an e-mail to notify the user.
     * @param User $user
     * @return User
     * @throws MissingEmailException
     */
    public static function sendResetPasswordLink(User $user): User
    {
        if (!$user->email) {
            throw new MissingEmailException('Failed to set temporary password. The user does not have an email.');
        }

        Password::sendResetLink(['email' => $user->email]);

        return $user;
    }

    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public static function uploadAvatar(User $user, UploadedFile $avatar): Media
    {
        $user->clearMediaCollection('avatar');
        return $user->addMedia($avatar)
            ->toMediaCollection('avatar');
    }

    public static function assignRole(User $user, int $roleId): void
    {
        $role = Role::findOrFail($roleId);
        $user->assignRole($role->name);
    }

    public static function activateUser(User $user): void
    {
        $user->status = 'active';
        $user->save();
    }

    public static function deactivateUser(User $user): void
    {
        $user->status = 'inactive';
        $user->save();

        $user->notify(new AccountDisabledNotification());
    }
}
