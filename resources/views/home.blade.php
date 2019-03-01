<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <div class="center jumbotron">
        <div class="text-center">

            <div>
                <a href="/logout" class="btn btn-default btn-md">Log out</a>
            </div>
            <div><h3>プロフィール</h3></div>
            <div>
                <table border="1">
                    <tr>
                        <th>
                            アカウント名
                        </th>
                        <th>
                            アカウントURL
                        </th>
                        <th>
                            フォローワー数
                        </th>
                        <th>
                            フォロー数
                        </th>
                        <th>
                            リポジトリ数
                        </th>
                        <th>
                            リポジトリURL
                        </th>
                        <th>
                            トークン
                        </th>
                    </tr>
                    <tr>
                        <td>
                            {{ session('userGitHubInfo')['login'] }}
                        </td>
                        <td>
                            {{ session('userGitHubInfo')['url'] }}
                        </td>
                        <td>
                            {{ session('userGitHubInfo')['followers'] }}
                        </td>
                        <td>
                            {{ session('userGitHubInfo')['following'] }}
                        </td>
                        <td>
                            {{ session('userGitHubInfo')['public_repos'] }}
                        </td>
                        <td>
                            {{ session('userGitHubInfo')['repos_url'] }}
                        </td>
                        <td style="word-break: break-all;">
                            {{ session('token') }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>

