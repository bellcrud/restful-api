<?php

namespace App\Http\Middleware;

use App\Exceptions\TokenException;
use App\Token;
use Closure;

class TokenCheck
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 * @throws TokenException
	 */
    public function handle($request, Closure $next)
    {
    	//ヘッダーに認証用トークンが含まれているか確認
		if ($request->header('Authorization'))
		{
			//渡されたトークンがTokensテーブルに存在するか検索
			$token = Token::findByToken($request->header('Authorization'));

			//トークンがTokensテーブルに存在しかつ有効期限が過ぎていないかチェック
			if($token->count() && !Token::calculationPastDay($token->created_at))
			{
				return $next($request);
			}
			throw new TokenException();
		}
		throw new TokenException();
    }
}
