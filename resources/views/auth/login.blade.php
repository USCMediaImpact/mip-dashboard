@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="medium-6 medium-centered large-4 large-centered columns">
            <form method="POST" action="/auth/login">
                {!! csrf_field() !!}
                <div class="row column log-in-form">
                    <h4 class="text-center">Log in with your email account</h4>
                    <label>Email
                        <input type="text" name="email" value="{{ old('email') }}" placeholder="somebody@example.com">
                    </label>
                    <label>Password
                        <input type="password" name="password" placeholder="Password">
                    </label>
                    <input id="remember" type="checkbox" name="remember"><label for="remember">Remember Me</label>
                    <p>
                        <input type="submit" class="button expanded" value="Log In" />
                    </p>
                    <p class="text-center"><a href="/auth/reset">Forgot your password?</a></p>
                </div>
            </form>
        </div>
    </div>
@endsection