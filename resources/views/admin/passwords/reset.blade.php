<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARTLAY | RESET password</title>
    <link href="{{ URL::asset('public/admin/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/admin/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/admin/css/animate.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('public/admin/css/style.css') }}" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="passwordBox animated fadeInDown">
    <div class="row">
        <div class="col-md-12">
            <div class="ibox-content">
                <h2 class="font-bold">Forgot password</h2>
                @if (session('success_message'))
                <div class="alert alert-success">
                    {{ session('success_message') }}
                </div>
                @endif
                @if (session('error_message'))
                <div class="alert alert-danger">
                    {{ session('error_message') }}
                </div>
                @endif
                <p>
                    Enter your email address and your password will be reset and emailed to you.
                </p>
                <div class="row">
                    <div class="col-lg-12">
                        {{ Form::open(array('url' => 'admin/reset-password','class'=>'form-horizontal','id'=>'forgot-password')) }}
                        <div class="form-group">
                            <div class="col-xs-12">
                                {{Form::password('password',['class'=>'form-control','autofill'=>'false','placeholder'=>'Enter Password','id'=>'password'])}}
                                <div class="error-message">{{ $errors->first('password') }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-xs-12">
                                {{Form::password('password_confirmation',['class'=>'form-control','placeholder'=>'Enter Confirm Password'])}}
                                <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                            </div>
                        </div>
                            <button type="submit" class="btn btn-primary block full-width m-b">Reset Password</button>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr/>
  
</div>
</body>
</html>