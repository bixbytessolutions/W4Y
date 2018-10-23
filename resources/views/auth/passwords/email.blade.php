@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class='col-md-3'></div>
        <div class="col-md-6">
            <div class="login-box well">
            @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
             @endif

            <form method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}
                 <legend>{{ __('Reset Password') }}</legend>
                        <div class="form-group">
                            <label for="username-email">{{ __('E-Mail Address') }}</label>
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

@if ($errors->has('email'))
    <span class="invalid-feedback">
        <strong>{{ $errors->first('email') }}</strong>
    </span>
@endif
                        </div>
                       
                        <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                           
                        </div>
                        <a class="" href="{{ url('/login') }}"> Back to Login</a>
                     
                    </form>
                
            </div>
        </div>
        <div class='col-md-3'></div>
    </div>
</div>


@endsection
