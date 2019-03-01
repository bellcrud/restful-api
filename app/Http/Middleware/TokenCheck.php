<?php

namespace App\Http\Middleware;

use App\Exceptions\TokenException;
use App\Token;
use Closure;

class TokenCheck
{
	/**
	 * Handle an incoming request.
	 * トークンチェック
	 * ヘッダーに組み込まれたトークンを検索し、Exception\Handlerに渡す
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure $next
	 * @return mixed
	 * @throws TokenException
	 */
    public function handle($request, Closure $next)
    {
		//ヘッダーに認証用トークンが含まれているか確認
		if (!$request->header('Authorization')){
			throw new TokenException();

			////トークンがTokensテーブルに存在しかつ有効期限が過ぎていないかチェック
		}elseif (!empty($token = Token::findByToken($request->header('Authorization'))) && !Token::calculationPastDay($token->created_at))
		{
			return $next($request);
		}else{
			throw new TokenException();
		}
    }
}
