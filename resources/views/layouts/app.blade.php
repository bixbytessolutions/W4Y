<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> W4Y | @yield('title', 'Welcome')</title>
    <link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<style>
body {
    background-color: #f9f9f9 !important;
    font-family: 'Open Sans', sans-serif!important;
    font-size:11px;
}
.well{
    background-color:#fff!important;
    border-radius:0!important;
    border:black solid 1px;
}

.well.login-box {
    /*width:400px; */
    border:#d1d1d1 solid 1px;
    margin:0 auto;
    margin-top:30px;
    width:35%;
}
.well.login-box legend {
  font-size:26px;
  text-align:center;
  font-weight:300;
}
.well.login-box label {
  font-weight:300;
  font-size:13px;
  
}
.well.login-box input[type="text"] {
  box-shadow:none;
  border-color:#ddd;
  border-radius:0;
}

.well.welcome-text{
    font-size:21px;
}

/* Notifications */

.notification{
    position:fixed;
    top: 20px;
    right:0;
    background-color:#FF4136;
    padding: 20px;
    color: #fff;
    font-size:21px;
    display:none;
}
.notification-success{
  background-color:#3D9970;
}

.notification-show{
    display:block!important;
}

/*Loged in*/
.btn-default {
    color: #333;
    background-color: #f9f9f9;
    border-color: #ccc;
    border: 1px solid;
    text-align: center;
    cursor: pointer;
    color: #5e5e5e;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
    background: -webkit-gradient(linear, 50% 0%, 50% 100%, color-stop(0%, #fefefe), color-stop(100%, #f9f9f9)), #f9f9f9;
    background: -moz-linear-gradient(#fefefe, #f9f9f9), #f9f9f9;
    background: -webkit-linear-gradient(#fefefe, #f9f9f9), #f9f9f9;
    background: linear-gradient(#fefefe, #f9f9f9), #f9f9f9;
    border-color: #c3c3c3 #c3c3c3 #bebebe;
    -moz-box-shadow: rgba(0, 0, 0, 0.06) 0 1px 0, rgba(255, 255, 255, 0.1) 0 1px 0 0 inset;
    -webkit-box-shadow: rgba(0, 0, 0, 0.06) 0 1px 0, rgba(255, 255, 255, 0.1) 0 1px 0 0 inset;
    box-shadow: rgba(0, 0, 0, 0.06) 0 1px 0, rgba(255, 255, 255, 0.1) 0 1px 0 0 inset;
}
.invalid-feedback{
    color: #ff0000;
    display: block;
}
</style>
<body>

    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
