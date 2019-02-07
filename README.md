#RESTful-API

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

####機能一覧
- アイテム全件取得
- アイテム登録
- アイテム更新
- アイテム削除
- アイテムキーワード検索
- アイテム一件取得

####ディレクト構成
```
[Controllers]

Controllers
├── Auth
│   ├── ForgotPasswordController.php
│   ├── LoginController.php
│   ├── RegisterController.php
│   └── ResetPasswordController.php
├── Controller.php
└── ItemsController.php

[Model]
├── Item.php
└── User.php


```


##開発環境のセットアップ手順

####Laravel環境の構築
1. ローカルリポジトリに移動して、laravelをインストール  
`composer create-project laravel/laravel ./restful_api "5.5.*" --prefer-dist`
2. composerアップデート `composer update`
3. アプリケーションキーの設定 `php artisan key:generate`

4. envファイルを書き換える  
   `APP_DEBUG=false DB_CONNECTION=mysql DB_HOST=127.0.0.1 DB_PORT=3306 DB_DATABASE=okuratodo DB_USERNAME=root DB_PASSWORD=`

####DB作成
1. MySQLコンソールを開く
2. DBを作成する  
   `CREATE DATABASE items;`
3. マイグレーション実行  
        `php artisan migrate`

####サーバ起動
1. サーバーを起動する