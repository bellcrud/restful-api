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
            <div>
                <a href="/home" class="btn btn-default btn-md">Home</a>
            </div>
            <div><h3>管理画面</h3></div>
            {!! Form::model($aggregateLogs, ['route' => 'management.find', 'method' => 'get']) !!}
            {!! Form::date('dayStart', $dayStart) !!}
            {!! Form::label('~') !!}
            {!! Form::date('dayEnd',$dayEnd) !!}
            {!! Form::submit('検索',['class'=> 'btn btn-default btn-md'])!!}
            {!! Form::close() !!}
            @if (!empty($errors))
                @foreach( $errors->all() as $message )
                    <li style="color: red; margin: 10px;">{{ $message }}</li>
                @endforeach
            @endif
            <div style="">
                <table border="1">
                    <tr>
                        <th>
                            HTTPステータスコード
                        </th>
                        <th>
                            HTTPメソッド名
                        </th>
                        <th>
                            APIエンドポイント
                        </th>
                        <th>
                            処理平均時間
                        </th>
                        <th>
                            アクセス数
                        </th>
                        <th>
                            日付
                        </th>
                    </tr>
                    @if(!empty($aggregateLogs))
                        @foreach($aggregateLogs as $aggregateLog)
                            <tr>
                                <td>
                                    {{ $aggregateLog->status_code }}
                                </td>
                                <td>
                                    {{ $aggregateLog->method }}
                                </td>
                                <td>
                                    {{ $aggregateLog->api_uri }}
                                </td>
                                <td>
                                    {{ $aggregateLog->ave_execution_time }}
                                </td>
                                <td>
                                    {{ $aggregateLog->total_access_count }}
                                </td>
                                <td>
                                    {{ $aggregateLog->created_at }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>
            @if(!empty($aggregateLogs))
                <div>{!! $aggregateLogs->appends(['dayStart'=>$dayStart,'dayEnd'=>$dayEnd])->render() !!}</div>
            @endif
        </div>
    </div>
</div>
</body>
</html>

