<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //ユーザーとアカウントは1対多の関係(複数のアカウントを保持している可能性があるため)
    public function accounts()
    {
        return $this->hasMany(LinkedSocialAccount::class);
    }

    /**
     * ユーザー情報登録処理
     * @param $providerUser
     * @param $provider
     * @return User
     * @throws \Throwable
     */
    public static function registerUser($providerUser, $provider): User
    {
        $user = User::create([
            'email' => $providerUser->getEmail(),
            'name' => $providerUser->getName(),
        ]);
        return $user;
    }
}
