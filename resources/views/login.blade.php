<div>
    <div>
        @if (session('errorMessage'))
            <div>
                {{ session('errorMessage') }}
            </div>
        @endif
        <a href="/login/github">Log in with Github</a>
    </div>
</div>
