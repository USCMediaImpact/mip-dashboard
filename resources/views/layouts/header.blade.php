<div class="top-bar">
    <div class="top-bar-title">
        <span class="show-for-small-only menu-toggle-button" data-toggle="offCanvas">
            <button class="menu-icon dark" type="button" data-toggle></button>
        </span>
        <h1 class="top_logo">Meida Impact</h1>
    </div>
    <div class="top-bar-right hide-for-small-only">
        @if(isset($user))
        <span class="welcome">Welcome {{$user->name}}</span>
        <a class="button tiny" href="/auth/logout">Logout</a>
        @endif
    </div>
</div>