<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Token;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use App\SocialAccountService;
use Validator;
use Exception;
use Cookie;


class SocialAccountController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return \Socialite::driver($provider)->redirect();
    }


    /**
     * OAuth処理
     * ②nameがデータがあり255文字以内か確認。
     * ③取得したメールアドレスがデータがり255文字以内か確認
     * ④ユーザー情報を検索または登録処理を行う
     * ⑤ユーザー認証
     * ⑥ユーザー情報をセッションに格納
     * @param SocialAccountService $accountService
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws ValidationException
     * @throws \Throwable
     */
    public function handleProviderCallback(SocialAccountService $accountService, $provider)
    {
        //ユーザー情報取得
        try {
            $user = Socialite::with($provider)->stateless()->user();
        } catch (Exception $e) {
            //handlerに渡らないので、エラーログを出力
            Log::warning($e);
            return redirect('/login')->with('errorMessage', $provider . config('messages.socialCertificationError'));
        }

        //名前・メールアドレス・プロバイダーID・プロバイダーネームのバリデーションルールを用意
        $input = ['name' => $user->name, 'email' => $user->email, 'providerId' => $user->id, 'providerName' => $provider];
        $rule = ['name' => 'required|max:255', 'email' => 'required|max:255|email', 'providerId' => 'required', 'providerName' => 'required'];

        //バリデーション処理を実行
        Validator::make($input, $rule)->validate();

        //ユーザー情報を検索または登録
        $authUser = $accountService->findOrCreate(
            $user,
            $provider
        );

        //User認証
        Auth::login($authUser);

        //token取得
        $token = Token::createCheckToken($authUser->id);

        //セッションに取得データ格納
        session(['userGitHubInfo' => $user->user, 'token' => $token->token]);

        //Cokkieにトークンを設定
        $cookie = Cookie::make('TOKEN', $token->token, config('cookie.tokenDeadline'), null, null, null, false);
        //不要なCookie情報を削除
        $cookie = $cookie->getName() . '=' . $cookie->getValue();

        //SPAにリダイレクト
        return redirect(env('REACT_APP_HOST_NAME') . '?' . $cookie);

    }
}
