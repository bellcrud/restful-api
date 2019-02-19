<?php

namespace App\Http\Middleware;
use Symfony\Component\HttpFoundation\Response as StatusCode;//追加

use Closure;

class AjaxOnlyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //リクエストがajaxではない場合の処理
        if(!$request->ajax()) {
            abort(StatusCode::HTTP_BAD_REQUEST);
        }
        return $next($request);
    }
}
