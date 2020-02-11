<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}} | Login</title>

    <link href="{{ URL::asset('public/admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/admin/css/style.css') }}" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <div class="">
            <img height="100" width="300" class="img-responsive" src="{{ URL::asset('public/admin/img/logo.png') }}"> <br><br>
        </div>
        <h3>Welcome to {{env('APP_NAME')}}</h3>
        @if(Session::has('message'))
        <p class="alert alert-danger">{{ Session::get('message') }}</p>
        @endif
           {{ Form::open(array('url' => 'gadmin/admin','class'=>'m-t')) }}
            {!! csrf_field() !!}
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Email" required="">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required="">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
            <a href="{{ URL::to('admin/forgot-password') }}"><small>Forgot password?</small></a>
            {{ Form::close() }}
        <p class="m-t"> <small>{{env('APP_NAME')}}.com &copy; 2019</small> </p>
    </div>
</div>
<!-- Mainly scripts -->
<script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
</body>
</html>
