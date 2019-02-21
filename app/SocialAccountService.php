<?php

namespace App;

use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{

    /**
     * アカウント検索と登録処置
     * ①GitHubから取得してきた取得してきた情報がDBに登録されているか確認
     * ②メールアドレスを元にアカウントを検索し、登録されていなければ登録する
     * ③アカウントが登録されていなければ　inked_social_accounts　テーブルに登録する
     * @param ProviderUser $providerUser
     * @param $provider
     * @return User|\Illuminate\Database\Eloquent\Model|mixed|object|null
     */
    public function findOrCreate(ProviderUser $providerUser, $provider)
    {
        //アカウント情報が登録されているかの確認
        $account = LinkedSocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();

        //アカウントが登録されていればアカウント情報を返すのみ
        if ($account) {
            return $account->user;
        } else {
            $user = User::where('email', $providerUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                ]);
            }

            $user->accounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);

            return $user;

        }
    }
}