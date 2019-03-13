<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class AccessLogAPI
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //レスポンス処理実行
        $response = $next($request);

        //リクエストURI取得
        $path = $request->getRequestUri();

        //アイテムIDを取得する
        $id = null;
        if (preg_match('/\/api\/v1\/items\/\d+/', $path)) {
            $id = mb_substr($path, config('values.idGet'));
            $path = preg_replace('/\/api\/v1\/items\/\d+/', '/api/v1/items/{id}', $path);
        }

        //HTTPメソッド取得
        $method = $request->getMethod();

        //レスポンスHTTPステータスコード取得
        $statusCode = $response->getStatusCode();

        //リクエストタイム取得
        $requestTime = microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];

        if (empty($id)) {
            //ログ出力用メッセージ成型
            $logMessage = $method . config('values.space') . $path . config('values.space') . $statusCode . config('values.space') . $requestTime;
        } else {
            //ログ出力用メッセージ成型
            $logMessage = $method . config('values.space') . $path . config('values.space') . $statusCode . config('values.space') . $requestTime . config('values.space') . 'itemId:' . $id;
        }

        //ログ出力
        Log::channel('accessLog')->info($logMessage);

        return $response;
    }
}
