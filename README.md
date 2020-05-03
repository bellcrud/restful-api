## Docker課題3「CodePipelineを使ったCI環境構築」
 

### 概要  

CodePipeline、CodeBuildを使ってリポジトリにプッシュされたらECSに自動デプロイするCI環境を構築してください。ここではサーバサイドアプリケーションだけではなく、フロントエンドアプリケーションも同じCI環境上に構築してください。


### 使用した技術  

- Docker 
- ECR
- CodePipeline
- CodeBuild

### ECR  


|Repository|URI|
|----------------|---------------------|
|okura-restful-api | 720146896923.dkr.ecr.ap-northeast-1.amazonaws.com/okura-restful-api     |



### ECS  


|Cluster|Service|Task|
|--------------------|------------------|--------------------|
|okura-cluster2  |okura-service   |okura-task    |

### 確認URL  
 - https://www.okurashoichi.com/login

### インフラ設計図  
![インフラ設計図](https://bitbucket.org/teamlabengineering/okura-restful-api/raw/8bdc365d089db13ee315d05426a36580e670d009/document/%E3%82%A4%E3%83%B3%E3%83%95%E3%83%A9%E8%A8%AD%E8%A8%88%E5%9B%B3.png)
