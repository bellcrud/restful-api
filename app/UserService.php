<?php

namespace App;

use Illuminate\Support\Facades\DB;


class UserService
{

    /**
     * @param $providerUser
     * @param $provider
     * @return User
     * @throws \Throwable
     */
    public static function registerUser($providerUser, $provider): User
    {
        $user = DB::transaction(function () use ($providerUser, $provider) {
            $user = User::registerUser($providerUser, $provider);

            LinkedSocialAccount::registerLinkedSocialAccount($user, $providerUser, $provider);
            return $user;
        });
        return $user;
    }

}