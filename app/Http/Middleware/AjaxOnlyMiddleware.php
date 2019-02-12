<?php

namespace App\Http\Middleware;

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
            abort(404);
        }
        return $next($request);
    }
}
