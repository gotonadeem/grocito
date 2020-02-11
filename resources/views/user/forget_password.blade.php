<!doctype html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>{{env('APP_NAME')}}!! SELLER FORGET PASSWORD</title>
    <link rel="stylesheet" href="{{ URL::asset('public/front/css/style.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="wraper">
    <div class="modal_login forget-pass-model">
            {{ Form::open(array('url' => 'seller/forget-password','class'=>'modal-content_login animate','id'=>'login_seller','name'=>'login_seller')) }}
            <div class="login_title">
                <h2 class="content-none">Seller Forget Password</h2>
                @if(Session::has('error_message'))
                    <p class="alert alert-danger">{{ Session::get('error_message') }}</p>
                @endif 
				@if(Session::has('success_message'))
                    <p class="alert alert-success">{{ Session::get('success_message') }}</p>
                @endif
            </div>
            <div class="container-fluid">
                <label for="uname"><b>Email address</b></label>
                <input type="email" placeholder="Enter email address" name="email" required>
                <button class="frget-submit" type="submit">Submit</button>   
            </div>
            <div class="container-fluid">
            <p class="blogin-btn">
                <span class="psw frget-pass"><a href="{{URL::to('/')}}">Back To Login</a></span>
                </p>
            </div>
        </form>
    </div>
</div>
</body>
</html>
