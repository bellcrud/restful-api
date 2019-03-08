<div>
    @if (!empty($errors))
        @foreach( $errors->all() as $message )
            <div>{{ $message }}</div>
        @endforeach
    @endif
    <div>
        @if (session('errorMessage'))
            <div>
                {{ session('errorMessage') }}
            </div>
        @endif
        <a href="/login/github">Log in with Github</a>
    </div>
</div>
