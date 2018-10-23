@extends('layouts.app')

@section('content')
@section('title', 'Register')
<div class="container">
    <div class="row">
        <div class='col-md-3'></div>
        <div class="col-md-6">
            <div class="login-box well">
                    <form autocomplete="off" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}
                        <legend>{{ __('Register') }}</legend>
                        <div class="form-group">
                        <label class="checkbox-inline">
                        {{ __('Company ?') }}  <input id="checkid" type="checkbox" @if(old('iscompany')==1) checked @endif  name="iscompany" value="1">
                      </label>

                          
                        </div>

             <div class="form-group" id="compnydetails">
           <div class="form-group">
                            <label for="company_name">{{ __('Company Name *') }}</label>
                            <input id="company_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="company_name" value="{{ old('company_name') }}"  autofocus>

                            @if ($errors->has('company_name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('company_name') }}</strong>
                                </span>
                            @endif
            </div>

                 
           </div>
                        <div class="form-group">
                            <label for="username-email">{{ __('First Name *') }}</label>
                            <input id="first_name" type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" required autofocus>

                                @if ($errors->has('first_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
                        </div>
                        
                        <div class="form-group">
                            <label for="username-email">{{ __('Last Name ') }}</label>
                            <input id="last_name" type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" required autofocus>

                                @if ($errors->has('last_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('E-Mail Address *') }}</label>
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        
                       
                        <div class="form-group">
                            <label for="password">{{ __('Password *') }}</label>
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('Confirm Password *') }}</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                        
                        <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                      
                        </div>
                        <a class="" href="{{ url('/login') }}"> Back to Login</a>
                       
                    </form>
                
            </div>
        </div>
        <div class='col-md-3'></div>
    </div>
</div>

<script>


    if ($('#checkid').is(':checked')) {
        $("#compnydetails").show();
    } 
    else {
        $("#compnydetails").hide();
    } 

    $( "#checkid" ).change(function() {
        if ($('#checkid').is(':checked')) {
            $("#compnydetails").show();
        } else {
            $("#compnydetails").hide();
        } 
    });

</script>

@endsection
