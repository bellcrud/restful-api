##Docker課題1「Dockerイメージの作成とコンテナ起動」

####概要
基本課題で作ったサーバサイドアプリケーションを動かすためのDockerイメージを作成する。
また、作成したDockerイメージからコンテナの作成・起動をし、 
問題なく動作することを確認できたらDockerイメージをECRにプッシュしてください。


####使用した技術
- Docker 
- ECR

####起動するための手順
1. docker for macをインストール
   docker
   https://hub.docker.com/editions/community/docker-ce-desktop-mac

2. このリポジトリを git clone する
`git clone git@bitbucket.org:teamlabengineering/okura-restful-api.git` 

3. .envファイルを以下のように編集する
```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:ZL0uh7NbgNP02XkUyc9KTS/paZUZs068b2NFv9HHTkw=
APP_DEBUG=true
APP_LOG_LEVEL=debug
APP_URL=ec2-54-250-40-155.ap-northeast-1.compute.amazonaws.com

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=restful_api
DB_USERNAME=root
DB_PASSWORD=root

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

GITHUB_CLIENT_ID=********
GITHUB_CLIENT_SECRET=********

REACT_APP_HOST_NAME=https://www.okurashoichi.com
APP_URL_PORT=http://ec2-13-231-26-227.ap-northeast-1.compute.amazonaws.com

AWS_ACCESS_KEY_ID=********
AWS_SECRET_ACCESS_KEY=********
AWS_DEFAULT_REGION=ap-northeast-1
AWS_BUCKET=okura-image

S3_URL=https://okura-image.s3.ap-northeast-1.amazonaws.com/
```
※起動の確認のためOAuth認証のログインなどはしないため、 
 GitHubアプリケーションの登録はしない。 
 
3. クローンしたリポジトリのディレクトリに移動し、docker imageをビルドする
`docker build -t okura-restful-api:latest .`

4. Dockerコンテナを起動する
`docker container run -d -p 9000:80 okura-restful-api:latest`

5. http://localhost:9000/login にアクセスする。

####docker composeを利用した起動手順
1. docker-compose.ymlがあるディレクトリに移動

2. `docker-compose up -d`コマンドを実行

3. `docker ps`コマンドを実行し、okura-restful-api:latestイメージから作成されたコンテナのCONTAINER IDを確認

4. `docker exec -it [3で確認したCONTAINER ID] /bin/bash`コマンドを実行し、コンテナにログイン

5. `php artisan migrate`コマンドを実行する。

6. http://localhost:9000/login にアクセスする。