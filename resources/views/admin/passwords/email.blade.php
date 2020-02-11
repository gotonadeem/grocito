<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{env('APP_NAME')}} | Forgot password</title>
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
                            <form class="m-t" role="form" method="POST" action="{{ url('admin/forgot-password') }}">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <div class="form-group">
                                <input name="email" type="email" class="form-control" value="{{ old('email') }}" placeholder="Email address" required="">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary block full-width m-b">Send new password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr/>
</div>
</body>
</html>