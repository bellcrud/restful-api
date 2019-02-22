<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LinkedSocialAccount extends Model
{
    protected $fillable = ['provider_name', 'provider_id'];

    //ユーザーとアカウントは1対多の関係
    public function user()
    {
        return $this->belongsTo('App\User');
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
        DB::transaction(function () use ($user, $providerUser, $provider) {
            $user->accounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);
        });
    }
}