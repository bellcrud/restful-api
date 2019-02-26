<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class LinkedSocialAccount extends Model
{
    protected $fillable = ['provider_name', 'provider_id'];

    //ユーザーとアカウントは1対多の関係
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * linked_social_accountテーブルへの登録処理
     * @param $user
     * @param $providerUser
     * @param $provider
     * @return mixed
     * @throws \Throwable
     */
    public static function registerLinkedSocialAccount($user, $providerUser, $provider)
    {
        $user->accounts()->create([
            'provider_id' => $providerUser->getId(),
            'provider_name' => $provider,
        ]);
    }


    /**
     * プロバイダーIDを検索条件にし、linked_social_accountテーブルから検索する。
     * @return LinkedSocialAccount|Model|object|null
     */
    public static function findUser($providerUser, $provider)
    {
        return LinkedSocialAccount::where('provider_name', $provider)
            ->where('provider_id', $providerUser->getId())
            ->first();
    }
}