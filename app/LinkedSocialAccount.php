<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LinkedSocialAccount extends Model
{
    protected $fillable = ['provider_name', 'provider_id'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function registerLinkedSocialAccount($user, $providerUser, $provider)
    {
        $user = DB::transaction(function () use ($user, $providerUser, $provider) {
            $user->accounts()->create([
                'provider_id' => $providerUser->getId(),
                'provider_name' => $provider,
            ]);
            return $user;
        });

        return $user;
    }
}