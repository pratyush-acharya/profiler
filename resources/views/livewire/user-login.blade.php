<div>   
    @include('get-alert')
    <form class="box">
        <h1>Login</h1>
        <a href="{{ route('redirectToGoogle') }}">
            <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png"
            class="text-center">
        </a>
        <div id="signout" wire:ignore></div>
    </form>
</div>