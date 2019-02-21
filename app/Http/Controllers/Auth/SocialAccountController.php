<?php

namespace App\Http\Controllers\Auth;

use function GuzzleHttp\Promise\exception_for;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

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
     * Obtain the user information
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
        if ($user->name === NULL || strlen($user->name) === 255 && $user->nickname === NULL || strlen($user->nickname) === 255) {
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


        auth()->login($authUser, true);

        return view('home', ['user' => $user, 'userGitHubInfo' => $userGitHubInfo]);

    }
}
