@extends('layouts.app')

@section('content')
@section('title', 'Login')
<div class="container">
    <div class="row">
        <div class='col-md-3'></div>
        <div class="col-md-6">
            <div class="login-box well">
            @if (Session::has('message'))
                <div id="msgSuccess" class="alert alert-success">{{ Session::get('message') }}</div>
            @endif
            @if ($errors->has('global'))
                <div id="msgSuccess" class="alert alert-danger">{{ $errors->first('global') }}</div>
            @endif
            <form method="POST" action="{{ route('login') }}">
               {{ csrf_field() }}
                        <legend>Sign In</legend>
                        <div class="form-group">
                            <label for="username-email">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div class="input-group">
                          <div class="checkbox">
                            <label>
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                            </label>
                          </div>
                        </div>
                        <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>
                           
                        </div>
                        <span class='text-center'><a href="{{ url('/password/reset') }}" class="text-sm">Forgot Password?</a></span>
                        <div class="form-group">
                            <p class="text-center m-t-xs text-sm">Do not have an account?</p> 
                            <a href="{{ route('register') }}" class="btn btn-default btn-block m-t-md">Create an account</a>
                        </div>
                    </form>
                
            </div>
        </div>
        <div class='col-md-3'></div>
    </div>
</div>
<script>
$(window).load(function(){
    $('#msgSuccess').delay(3000).fadeOut(350);
});


</script>
@endsection
