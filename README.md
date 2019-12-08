## Docker課題2「ECSを使ったDockerコンテナデプロイ」 
 

#### 概要  

Docker課題1でECRにプッシュしたDockerイメージを、ECSにデプロイしてください。
また、構築するにあたってクラスタとサービスの起動タイプは「EC2」を選択し、ECSインスタンスの数は2つ、その中で動作するタスクの数は2つにしてください。


#### 使用した技術  

- Docker 
- ECR

#### ECR  


|Repository|URI|
|----------------|---------------------|
|okura-restful-api | 720146896923.dkr.ecr.ap-northeast-1.amazonaws.com/okura-restful-api     |



#### ECS  


|Cluster|Service|Task|
|--------------------|------------------|--------------------|
|okura-cluster2  |okura-service   |okura-task    |

#### 確認URL  

http://okura-ecs-elb-1352259772.ap-northeast-1.elb.amazonaws.com:8080/login

#### インフラ設計図  
![インフラ設計図](https://bitbucket.org/teamlabengineering/okura-restful-api/raw/d2e4e7b5c3bf9b6b108f3e670d9d9ffa7dc91b82/document/%E3%82%A4%E3%83%B3%E3%83%95%E3%83%A9%E8%A8%AD%E8%A8%88%E5%9B%B3.png)
