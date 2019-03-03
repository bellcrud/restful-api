<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Token extends Model
{
    protected $fillable = [
        'user_id', 'token',
    ];

    /**
     * 登録ずみトークンの取得または再作成
     * ①ユーザーIDでtokensテーブルを検索
     * ②検索結果がなければ新たにトークンを新規作成
     * ③検索結果があった場合、有効期限のチェック
     * ④有効期限がきれていた場合、有効期限切れのレコードを削除し、トークンを再作成
     * @param $userId
     * @return Token|mixed
     * @throws \Throwable
     */
    public static function createCheckToken($userId)
    {
        $token = Token::where('user_id', $userId)->first();

        if (empty($token)) {
            //トークン新規作成
            $token = DB::transaction(function () use ($userId, $token) {
                $token = Token::create(['user_id' => $userId, 'token' => str_random(60)]);
                return $token;
            });
            return $token;
            //トークン有効期限日時が有効かチェック。無効の場合はトークン削除・トークン再作成を行う
        } elseif (self::calculationPastDay($token->created_at)) {
            $token = DB::transaction(function () use ($userId, $token) {
                //有効期限をすぎたトークンを削除
                Token::destroy($token->id);
                //トークンを再作成
                Token::create(['user_id' => $userId, 'token' => str_random(60)]);
            });
            return $token;
        }

        return $token;
    }

    /**
     * トークンの有効期限のチェック
     * 有効期限がきれていた場合 true
     * 有効期限が切れていない場合 false
     * @param $createdAt
     * @return bool
     */
    public static function calculationPastDay($createdAt): bool
    {
        //有効期限を作成
        $expirationDate = $createdAt->addDays(config('values.expirationDate'));
        //現在日時と有効期限日時を比較し、現在日時の方が後であれば新規作成
        return Carbon::now() > $expirationDate;
    }

    /**
     * tokensテーブルからtokenカラムで引数で渡された値の完全一致で検索
     * @param $token
     * @return Token|Model|object|null
     */
    public static function findByToken($token)
    {
        return Token::where('token', $token)->first();
    }
}
