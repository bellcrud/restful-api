<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Laravel側ログアウトメソッドを呼び出し名を変更
     *
     * 処理を新たに追加したlogoutをログアウト時に呼びだす際
     * デフォルトのメソッドを実行する際に呼びだす際にメソッド名が被るのを
     * 回避するため変更
     */
    use AuthenticatesUsers{
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * Laravelデフォルトのメソッドも合わせて実行する。
     */
    public function logout(Request $request)
    {
        $this->performLogout($request);

        return redirect('/login');
    }
}
