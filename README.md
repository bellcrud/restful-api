##インフラ課題3「CIサーバを使った自動デプロイ」

####概要
bitbucketにソースをpushすると自動でec2にデプロイする
デプロイ時アクセスするとメンテナンスページが表示される

####使用した技術
- bitbucket pipelines 
- S3
- CodeDeploy 
- CodePipeline
- CloudFront

####確認URL
- http://dhxewfmlpcn2x.cloudfront.net/

####インフラ設計図
- 下記のパスに保存されております。
  okura-restful-api/document/インフラ設計図.png