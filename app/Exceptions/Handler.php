<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($this->isHttpException($exception)) {

            //HTTPステータスコード取得
            $statusCode = $exception->getStatusCode();

            //エラーメッセージ取得
            $details = $exception->getMessage();

            //400エラー
            if ($statusCode == StatusCode::HTTP_BAD_REQUEST) {

                $statusMessage = "Bad Request";
            } //401エラー
            elseif ($statusCode == StatusCode::HTTP_UNAUTHORIZED) {

                $statusMessage = "Unauthorized";
            } //404エラー
            elseif ($statusCode == StatusCode::HTTP_NOT_FOUND) {

                $statusMessage = "リソースがありませんでした";
            } //405エラー
            elseif ($statusCode == StatusCode::HTTP_METHOD_NOT_ALLOWED) {

                $statusMessage = "Method Not Allowed";
            } //422エラー
            elseif ($statusCode == StatusCode::HTTP_UNPROCESSABLE_ENTITY) {

                $statusMessage = "バリデーション エラーです";
                $details = $exception->getHeaders();
            } //500エラー
            elseif ($statusCode == StatusCode::HTTP_INTERNAL_SERVER_ERROR) {

                $statusMessage = "サーバで予期しないエラーが発生しました";
            } //503エラー
            elseif ($statusCode == StatusCode::HTTP_SERVICE_UNAVAILABLE) {

                $statusMessage = "Service Unavailable";
            } //その他500エラー
            else {

                $statusCode = StatusCode::HTTP_INTERNAL_SERVER_ERROR;
                $statusMessage = "There Is Something Error";

            };

            $errors = [
                'code' => $statusCode,
                'message' => $statusMessage,
                'details' => $details,
            ];

            return response()->json(
                ['errors' => $errors],
                $statusCode,
                [],
                JSON_UNESCAPED_UNICODE
            );
        }
        //HTTP通信以外の例外が起こった場合500エラーで返す。
        $statusCode = StatusCode::HTTP_INTERNAL_SERVER_ERROR;
        $statusMessage = "There Is Something Error";
        $errors = [
            'code' => $statusCode,
            'message' => $statusMessage,
        ];
        return response()->json(
            ['errors' => $errors],
            $statusCode,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }
}
