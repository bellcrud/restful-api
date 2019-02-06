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

        //HTTPステータスコード取得
        $statusCode = $exception->getCode();

        //エラーメッセージ取得
        $errorMessages = $exception->getMessage();

        //400エラー
        if ($statusCode == StatusCode::HTTP_BAD_REQUEST) {

            $statusMessage = "Bad Request";
        }
        //401エラー
        elseif ($statusCode == StatusCode::HTTP_UNAUTHORIZED) {

            $statusMessage = "Unauthorized";
        }
        //404エラー
        elseif ($statusCode == StatusCode::HTTP_NOT_FOUND) {

            $statusMessage = "Resource not found";
        }
        //405エラー
        elseif ($statusCode == StatusCode::HTTP_METHOD_NOT_ALLOWED) {

            $statusMessage = "Method Not Allowed";
        }
        //500エラー
        elseif ($statusCode == StatusCode::HTTP_INTERNAL_SERVER_ERROR) {

            $statusMessage = "Internal Server Error";
        }
        //503エラー
        elseif ($statusCode == StatusCode::HTTP_SERVICE_UNAVAILABLE) {

            $statusMessage = "Service Unavailable";
        }
        //その他500エラー
        else {

            $statusCode = StatusCode::HTTP_INTERNAL_SERVER_ERROR;
            $statusMessage = "There Is Something Error";

        };

        return response()->json([
            'status' => $statusCode,
            'status_message' => $statusMessage,
            'error_messages' => $errorMessages,
        ],$statusCode);
    }
}
