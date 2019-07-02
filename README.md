##インフラ課題3「CIサーバを使った自動デプロイ」

####概要
bitbucketにソースをpushすると自動でec2にデプロイする
デプロイ時アクセスするとメンテナンスページが表示される

####使用した技術
- bitbucket pipelines 
- S3
- CodeDeploy 
- CodePipeline
- ELB

####確認URL
- http://okura-elb-1476899064.ap-northeast-1.elb.amazonaws.com/

####インフラ設計図
- 下記のパスに保存されております。
  okura-restful-api/document/インフラ設計図.png