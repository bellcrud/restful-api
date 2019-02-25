<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * APIでアクセスした場合のステータスコードに対するメッセージ一覧
     * @var array
     */
    protected $statusCodeMessage = [
        200 => 'OK',
        400 => 'Bad Request',
        401 => '権限がありません',
        404 => 'リソースがありませんでした',
        422 => 'バリデーションエラーです',
        500 => 'サーバーで予期しないエラーが起きました。',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    /**
     * 親クラスのprepareJsonResponseをオーバーライド
     * このメソッドはjson形式でresponseする場合のみ実行される
     * 以下の3つjson形式で返す
     * ①HTTPステータスコード
     * ②メッセージ
     * ③詳細
     * @param \Illuminate\Http\Request $request
     * @param Exception $exception
     * @return JsonResponse
     */
    protected function prepareJsonResponse($request, Exception $exception)
    {

        //exceptionがHTTPException以外の場合全てステータスコードを500で返す
        $errors = [
            'code' => $this->isHttpException($exception) ? $exception->getStatusCode() : 500,
            'message' => $this->getStatusCodeMessage($this->isHttpException($exception) ? $exception->getStatusCode() : 500),
            'details' => $exception->getMessage(),
        ];

        return response()->json(
            ['errors' => $errors],
            $this->isHttpException($exception) ? $exception->getStatusCode() : 500,
            [],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * ステータスコードに対するメッセージを取得する
     * 取得先はこのクラスに定義しているプロパティの'statusCodeMessage'
     * @param int $statusCode
     * @return mixed
     */
    public function getStatusCodeMessage(int $statusCode)
    {
        return $this->statusCodeMessage[$statusCode];
    }

    /**
     * ログインせずにログインが必要なルートにアクセスした場合のメッセージを格納
     * @param Exception $exception
     * @return Exception|HttpException
     */
    protected function prepareException(Exception $exception)
    {
        //Laravel側のデフォルトの処理を邪魔しないように先に実行しておく
        $exception = parent::prepareException($exception);

        if ($exception instanceof AuthenticationException) {
            //ログインしていない場合はログイン画面にリダイレクトする
            $exception = new HttpException(401, 'ログインをしてからアクセスしてください');
        } elseif ($exception instanceof ValidationException) {
            //バリデーションエラーの場合はerror画面にリダイレクトする。
            $exception->redirectTo('error');
        }
        return $exception;
    }

    /**
     * レスポンスで画面遷移先指定し、Exceptionの内容を画面で表示できるように配列に格納
     * @param HttpException $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $exception)
    {
        return response()->view("error",
            [
                'exception' => $exception,
                'message' => $exception->getMessage(),
                'status_code' => $exception->getStatusCode(),
            ],
            $exception->getStatusCode(), // レスポンス自体のステータスコード
            $exception->getHeaders()
        );

    }

}
