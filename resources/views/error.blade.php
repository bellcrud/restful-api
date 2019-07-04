<div>
    <h1>{{ $status_code or '' }}</h1>
    <p>{{ $message or ''}}</p>
    <!-- バリデーションエラーの場合はバリデーションに引っかかった項目を表示する -->
    @if (!empty($errors))
        @foreach( $errors->all() as $message )
            <div>{{ $message }}</div>
        @endforeach
        <p>登録先のアカウント情報を再度確認してください</p>
    @endif
    <hr>
    <p>
        <a href="/login">TOP</a>
    </p>
</div>