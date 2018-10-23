@extends('layouts.app')
@section('content')
@section('title', 'Employee Register')
<div class="container">
    <div class="row">
        <div class='col-md-3'></div>
        <div class="col-md-6">
            <div class="login-box well">
                    <form autocomplete="off" method="POST" action="{{ url('/updateregister') }}">
                    {{ csrf_field() }}
                        <legend>{{ __('Register') }}</legend>
                        
                     
                        <div class="form-group">
                            <label for="username-email">{{ __('First Name *') }}</label>
                            <input  type="hidden" class="form-control" name="reg_token" value="{{ $employeeData->reg_token }}" >
                            <input id="first_name" readonly type="text" class="form-control" name="first_name" value="{{ $employeeData->first_name }}" required autofocus>

                                @if ($errors->has('first_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="username-email">{{ __('Last Name ') }}</label>
                            <input id="last_name" readonly type="text" class="form-control" name="last_name" value="{{ $employeeData->last_name }}" required autofocus>

                                @if ($errors->has('last_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('E-Mail Address *') }}</label>
                            <input id="email" readonly type="email" class="form-control" name="email" value="{{ $employeeData->email }}" required>

                        </div>

                        
                       
                        <div class="form-group">
                            <label for="password">{{ __('Password *') }}</label>
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" >

                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('Confirm Password *') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                                    Register
                          </button>
                      
                        </div>
                   
                       
                    </form>
                
            </div>
        </div>
        <div class='col-md-3'></div>
    </div>
</div>


@endsection
