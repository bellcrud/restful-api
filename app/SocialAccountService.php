<?php

namespace App;

use Laravel\Socialite\Contracts\User as ProviderUser;
use Illuminate\Support\Facades\DB;
use App\User;

class SocialAccountService
{
    //Userインスタンス
    public $user = null;

    /**
     * アカウント検索と登録処置
     * ①GitHubから取得してきた取得してきた情報がDBに登録されているか確認
     * ②メールアドレスを元にアカウントを検索し、登録されていなければ登録する
     * ③アカウントが登録されていなければ　inked_social_accounts　テーブルに登録する
     * @param ProviderUser $providerUser
     * @param $provider
     * @return User|\Illuminate\Database\Eloquent\Model|mixed|object|null
     * @throws \Throwable
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

            //ユーザーがと登録されていなければusersテーブルとlinked_social_accountsテーブルに登録
            if (!$user) {
                $user = User::registerUser($providerUser, $provider);
            }
            LinkedSocialAccount::registerLinkedSocialAccount($user, $providerUser, $provider);

            return $user;

        }
    }
}