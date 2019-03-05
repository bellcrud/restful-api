#課題4 定期ジョブ機能
##概要
APIへのアクセス情報を任意の形式でログファイルへ出力する処理を追加し、  
1日1度起動するバッチ処理で、ログファイルから以下の情報を集計した結果をDBに保存する

##使用した技術

####言語
- PHP　7.2.5

####フレームワーク
- Laravel 5.6

####ミドルウェア
- Mysql 5.7.24

####その他
- Socialite
- postman
- swagger

##全体の設計・構成

####定期ジョブ機能
- 一日一回APIアクセスログを集計し、DBに格納
- 実行時間：毎日深夜１２時に実行

####ディレクト構成
```
//Controller・Exception・Middleware
app
├── Console
│   ├── Commands
│   │    └── AccessLogAggregate.php //課題４にて新規追加
│   └── Kernel.php
├── Exceptions
│   ├── Handler.php
│   └── TokenException.php
├── Http
    ├── Controllers
    │   ├── Auth
    │   │   ├── ForgotPasswordController.php
    │   │   ├── LoginController.php
    │   │   ├── RegisterController.php
    │   │   ├── ResetPasswordController.php
    │   │   └── SocialAccountController.php
    │   ├── Controller.php
    │   ├── ItemsController.php
    │   └── ManagementController.php //課題４にて新規追加
    ├── Kernel.php
    └── Middleware
        ├── AccessLogAPI.php //課題4にて新規追加
        ├── AjaxOnlyMiddleware.php
        ├── EncryptCookies.php
        ├── RedirectIfAuthenticated.php
        ├── RequireJson.php
        ├── TokenCheck.php　
        ├── TrimStrings.php
        ├── TrustProxies.php
        └── VerifyCsrfToken.php

//テーブル関連
[Model]
├── Item.php
├── User.php
├── AccessLog.php
└── AggregateLog.php

migrations
├── 2014_10_12_000000_create_users_table.php
├── 2014_10_12_100000_create_password_resets_table.php
├── 2019_02_05_054602_create_items_table.php
├── 2019_02_20_025008_create_linked_social_accounts_table.php
├── 2019_02_20_040800_prepare_users_table_for_social_authentication.php
├── 2019_02_26_083823_create_tokens_table.php 
├── 2019_03_01_053718_create_aggregate_logs.php
└── 2019_03_03_104020_create_access_logs_table.php

views
├── error.blade.php
├── home.blade.php
├── login.blade.php
├── management.blade.php
└── welcome.blade.php

logs
├── api //課題４にて新規追加
│   ├── access-2019-02-28.log
│   ├── access-2019-03-01.log
│   ├── access-2019-03-03.log
│   ├── access-2019-03-04.log
│   └── access-2019-03-05.log
└── batch //課題４にて新規追加
    └── batch.log


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
APP_KEY=base64:wENUj/GLeKiwtpL99bewMSdR7eGcXS0Iif2QCpfnsz0=
APP_DEBUG=true
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

LOG_CHANNEL=stack
MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

```
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

####DB作成
1. MySQLコンソールを開く
2. DBを作成する  
   `CREATE DATABASE restful_api;`
3. マイグレーション実行  
        `php artisan migrate`
        
####シンボリックの作成
 1. public/storageからstorage/app/publicへシンボリックリンクを張る  
    アップロードしたファイルを閲覧するのに必要  
     `php artisan storage:link`

####サーバ起動
1. サーバーを起動する  
   `php artisan serve --host=localhost`  
   
2. http://localhost:8000 にアクセスする。

3. GitHubのアカウントでログインをすることできます。  
アカウントは任意のアカウントで問題ありません。  
＊ただし、アカウント情報にnameやe-mailの情報が登録されていないまたは、正しい情報出ない場合(名前が255文字以上など)
登録できない可能性があります。エラーメッセージにしたがって修正するかまたは、別のアカウントを使用してください。

4. postmanを使用し、API用のURIでHTTP通信を行なってください。  
その際ヘッダーにはトークンが必要になります。
以下の値をヘッダーに入力して下さい。
key : Authorization
value : *****
*****にはトークンを入力します。トークンはOAuth認証でログインした際に作成されます。
下記のテーブルカラムより任意のトークンを取得し、入力してください。
DB名 : restful_api
テーブル名 : tokens
カラム名 : token

5. APIにアクセスすると下記パスにログが出力されます。
restful_api/storage/logs/api

6. 定期ジョブが実行されると下記パスにログが出力されます。
/Users/okurashoichi/PhpstormProjects/restful_api/storage/logs/batch

7. 手動でジョブを実行する場合は下記のコマンドを入力してください。
`php artisan command:aggregate`  
**＊　初回実行時は集計するログファイルが無いため手動で 5 で記載したパスにログファイルを作成する必要があります。**  
ファイルはAPIにアクセス後「access-2019-03-05」(日付部分は実行日)のファイルが作成されるため、実行した前日の日付にファイル名を変更して  
からジョブコマンドを実行してください。  