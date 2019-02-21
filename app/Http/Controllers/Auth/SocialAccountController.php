<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
     * ①ユーザー情報をGitHubから取得
     * ②nameがデータがあり255文字以内か確認。
     * ③取得したメールアドレスがデータがり255文字以内か確認
     * ④ユーザー情報を検索または登録っしょりを行う
     * ⑤ユーザー認証
     * ⑥ユーザー情報をセッションに格納
     *
     * @return Response
     */
    public function handleProviderCallback(\App\SocialAccountService $accountService, $provider)
    {
        //ユーザー情報取得
        try {
            $user = \Socialite::with($provider)->user();
        } catch (\Exception $e) {
            return redirect('/')->with('errorMessage', $provider . config('database.socialCertificationError'));;
        }

        //名前またはニックネームが登録されていないまたは255文字以上の場合ログインページにリダイレクト
        if ($user->name === NULL || strlen($user->name) === 255) {
            return redirect('/')->with('errorMessage', $provider . config('database.nameValidation'));
        }

        //メールアドレスが登録されていないまたは255文字以上の場合ログインページにリダイレクト
        if ($user->email === NULL || strlen($user->email) === 255) {
            return redirect('/')->with('errorMessage', $provider . config('database.emailValidation'));
        }

        //ユーザー情報を検索または登録
        $authUser = $accountService->findOrCreate(
            $user,
            $provider
        );

        $userGitHubInfo = $user->user;

        //User認証
        Auth::login($authUser);

        //セッションに取得データ格納
        session(['userGitHubInfo' => $userGitHubInfo]);

        return redirect('/home');

    }
}
