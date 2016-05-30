@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="medium-6 medium-centered large-4 large-centered columns">
            <form method="POST" action="/auth/reset/password">
                {!! csrf_field() !!}
                <input type="hidden" name="token" value="{{$token}}" />
                <div class="row column log-in-form">
                    <h4 class="text-center">Reset your password</h4>
                    <label>Email
                        <input type="text" name="email" value="{{ old('email') }}" placeholder="somebody@example.com">
                    </label>
                    <label>Password
                        <input type="password" name="password" placeholder="Password">
                    </label>
                    <label>Re-Password
                        <input type="password" name="password_confirmation" placeholder="Password">
                    </label>
                    <p>
                        <input type="submit" class="button expanded" value="Reset" />
                    </p>
                </div>
            </form>
        </div>
    </div>
@endsection