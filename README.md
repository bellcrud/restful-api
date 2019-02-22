#課題1 RESTful-API
##概要
DBに登録されたアイテム情報を登録・検索・変更・削除ができる  
RESTfulなAPI  
- 商品画像
- 商品タイトル(最大100文字)
- 説明文(最大500文字)
- 価格

##使用した技術

####言語
- PHP　7.2.5

####フレームワーク
- Laravel 5.5

####ミドルウェア
- Mysql 5.7.24

####その他
- swagger
- postman

##全体の設計・構成

####API機能一覧
- アイテム全件取得
- アイテム登録
- アイテム更新
- アイテム削除
- アイテムキーワード検索
- アイテム一件取得

####ディレクト構成
```
Controllers
├── Auth
│   ├── ForgotPasswordController.php
│   ├── LoginController.php
│   ├── RegisterController.php
│   ├── ResetPasswordController.php
├── Controller.php
└── ItemsController.php



[Model]
├── Item.php
└── User.php

Middleware
    ├── AjaxOnlyMiddleware.php
    ├── EncryptCookies.php
    ├── RedirectIfAuthenticated.php
    ├── TrimStrings.php
    ├── TrustProxies.php
    └── VerifyCsrfToken.php
    
app
└── domain
    └── Base64Validation.php
```


##開発環境のセットアップ手順

####ミドルウェアのインストール
ミドルウェアのインストールに関しては(こちら)[https://bitbucket.org/teamlabengineering/guidelines/src/master/%E7%92%B0%E5%A2%83%E6%A7%8B%E7%AF%89%E6%89%8B%E9%A0%86%EF%BC%88PHP%EF%BC%89.md]
をご覧ください。

####Laravel環境の構築
1. composerアップデート `composer update`
2. アプリケーションキーの設定 `php artisan key:generate`
3. .env.exampleファイルをコピーする
    `$cp .env.example .env`
4. envファイルを書き換える  
 ```  
   APP_NAME=Laravel
   APP_ENV=local
   APP_KEY=base64:ccVnKA6FMkFRUzRRZ4Nwk07oTSo0LxTLxLkewyk6UNk=
   APP_DEBUG=false
   APP_LOG_LEVEL=debug
   APP_URL=http://localhost
   
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=restful_api
   DB_USERNAME=root
   DB_PASSWORD=
   
   BROADCAST_DRIVER=log
   CACHE_DRIVER=file
   SESSION_DRIVER=file
   SESSION_LIFETIME=120
   QUEUE_DRIVER=sync
   
   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379
   
   MAIL_DRIVER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   
   PUSHER_APP_ID=
   PUSHER_APP_KEY=
   PUSHER_APP_SECRET=
   PUSHER_APP_CLUSTER=mt1
   
   IMAGE_DIRECTORY=storage
```
####DB作成
1. MySQLコンソールを開く
2. DBを作成する  
   `CREATE DATABASE restful_api;`
3. マイグレーション実行  
        `php artisan migrate``
        
####シンボリックの作成
 1. public/storageからstorage/app/publicへシンボリックリンクを張る  
    アップロードしたファイルを閲覧するのに必要  
     `php artisan storage:link`

####サーバ起動
1. サーバーを起動する  
   `php artisan serve --host=localhost`  
   
2. http://localhost:8000 にアクセスする。

    
#基本課題2「OAuthを使ったソーシャルログイン」
####概要
OAuthを利用したログイン/ログアウト機能の実装。
- ログイン機能
- ログアウト機能
- GitHubでのOAuth認証機能

####言語
課題１と同様

####フレームワーク
- Laravel 5.6(＊課題２から5.5から5.6にバージョンアップ)

####ミドルウェア
課題1と同様

####その他
- Socialite

##全体の設計・構成

####OAuth認証一覧機能
- ログイン機能
- ログアウト機能
- GitHubでのOAuth認証機能
- Github登録情報を表示

####ディレクト構成
```
Controllers
├── Auth
│   ├── ForgotPasswordController.php
│   ├── LoginController.php
│   ├── RegisterController.php
│   ├── ResetPasswordController.php
│   └── SocialAccountController.php　//課題２で新規追加
├── Controller.php
└── ItemsController.php



[Model]
├── Item.php
└── User.php
└── LinkedSocialAccount.php　//課題２で新規追加

Middleware
    ├── AjaxOnlyMiddleware.php
    ├── EncryptCookies.php
    ├── RedirectIfAuthenticated.php
    ├── TrimStrings.php
    ├── TrustProxies.php
    └── VerifyCsrfToken.php
    
app
├── SocialAccountService.php　//課題２で新規追加
└── domain
    └── Base64Validation.php
```
##開発環境のセットアップ手順

####ミドルウェアのインストール
課題1と同様

####Laravel環境の構築
課題1と同様

####APIキー設定  
.envファイルに以下を追記してください
```
GITHUB_CLIENT_ID=***********
GITHUB_CLIENT_SECRET=**********
「**********」にはGitHubアプリケーションの登録後に提供された値を記述してください
```
GitHubのAPIキー作成取得方法は以下のサイトを参照してください
(作成方法)[https://yurakawa.hatenablog.jp/entry/2018/06/04/002033]  
作成する際に必要な項目には以下の値を入力してください  
Application name　`okura-restful-api`  
Homepage URL `http://localhost:8000/`  
Authorization callback URL `http://localhost:8000/login/github/callback`  

####サーバ起動
1. サーバーを起動する  
   `php artisan serve --host=localhost`  
   
2. http://localhost:8000 にアクセスする。

3. GitHubのアカウントでログインをすることできます。  
アカウントは任意のアカウントで問題ありません。  
＊ただし、アカウント情報にnameやe-mailの情報が登録されていないまたは、正しい情報出ない場合(名前が255文字以上など)
登録できない可能性があります。エラーメッセージにしたがって修正するかまたは、別のアカウントを使用してください。
　　
