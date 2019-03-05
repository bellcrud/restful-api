<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ログイン認証
Route::get('login/{provider}', 'Auth\SocialAccountController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\SocialAccountController@handleProviderCallback');
Route::get('logout', 'Auth\LoginController@logout');

//ログイン画面
Route::get('/', function () {
    return view('login');
})->name('login');

//ログイン認証済みの場合アクセス可
Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', function () {
        return view('home');
    });
    //ログ集計データ初期画面
    Route::get('/management', 'ManagementController@index')->name('management.index');
    //ログ集計データ日付検索
    Route::get('/management/find', 'ManagementController@findAggregateLog')->name('management.find');
});

//エラー画面
Route::get('error', function () {
    return view('error');
});