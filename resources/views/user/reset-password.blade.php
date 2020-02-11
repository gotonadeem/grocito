<!doctype html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>CARTLAY!! SELLER FORGET PASSWORD</title>
    <link rel="stylesheet" href="{{ URL::asset('public/front/css/style.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<div class="wraper">
    <div class="modal_login">
            {{ Form::open(array('url' => 'seller/reset-password','class'=>'modal-content_login animate reset-pass','id'=>'login_seller','name'=>'login_seller')) }}
            <div class="login_title">
                <h2>Seller Reset Password</h2>
                @if(Session::has('error_message'))
                    <p class="alert alert-danger">{{ Session::get('error_message') }}</p>
                @endif 
				@if(Session::has('success_message'))
                    <p class="alert alert-success">{{ Session::get('success_message') }}</p>
                @endif
            </div>
            <div class="container-fluid">
                <label for="uname"><b>New Password</b></label>
                {{Form::password('password',['class'=>'form-control','autofill'=>'false','placeholder'=>'Enter Password','id'=>'password'])}}            
                <div class="error-message">{{$errors->first('password')}}</div>
				
				<label for="password_confirmation">Confirm Password:</label>
				{{Form::password('password_confirmation',['class'=>'form-control','id'=>'password_confirmation','placeholder'=>'Enter Confirm Password'])}}
				<div class="error-message">{{$errors->first('password_confirmation')}}</div>
		  
				<button type="submit">Submit</button>
                
            </div>
            <div class="container-fluid">
                <span class="psw"><a href="{{URL::to('/')}}">Back To Login</a></span>
            </div>
        </form>
    </div>
</div>
</body>
</html>
