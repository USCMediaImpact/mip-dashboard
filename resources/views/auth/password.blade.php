@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="medium-6 medium-centered large-4 large-centered columns">
            <form method="POST" action="/auth/reset">
                {!! csrf_field() !!}
                <div class="row column log-in-form">
                    <h4 class="text-center">Forgot your password?</h4>
                    <label>Email
                        <input type="text" name="email" value="{{ old('email') }}" placeholder="somebody@example.com">
                    </label>
                    @if (count($errors) > 0)
                        <div class="callout alert">
                        <ol>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ol>
                        </div>
                    @endif
                    <p>
                        <input type="submit" class="button expanded" value="Send Reset Password Email" />
                    </p>

                    
                    <p class="text-center"><a href="/auth/login">Continue login?</a></p>
                </div>
            </form>
        </div>
    </div>
@endsection