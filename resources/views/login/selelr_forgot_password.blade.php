<!doctype html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
<meta charset="utf-8">
<title>{{env('APP_NAME')}}!! SELLER LOGIN</title>
<link rel="stylesheet" href="{{ URL::asset('public/front/css/style.css') }}">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="wraper">
  <div class="seller-login-wraper">
    <div class="seller-modal_login">
      <div class="form-v8-content"> 
	  
	   <div class="login_title">
          <h3 class="title-login">Forgot Password</h3>
          <div class="logo-seller-panel"><img class="img-responsive" src="{{ URL::asset('public/admin/img/logo.png') }}"></div>
          @if(Session::has('error_message'))
          <p class="alert alert-danger">{{ Session::get('error_message') }}</p>
          @endif
          
          @if(Session::has('success_message'))
          <p style="background: white;color: green;" class="alert alert-success">{{ Session::get('success_message') }}</p>
          @endif </div>
        <div class="container-fluid">
		 {{ Form::open(array('url' => 'seller/forgot-password','class'=>'form-detail animate','id'=>'seller_forgot_password','name'=>'seller_forgot_password')) }}
      
          <div class="form-row">
            <label class="form-row-inner">
              <input type="number" name="mobile" id="mobile" class="input-text" required="" aria-required="true">
              <span class="label"><i class="fa fa-mobile" aria-hidden="true"></i> Mobile Number</span> <span class="border"></span> </label>
          </div>
         
          <div class="row">
            <div class="container-fluid">
              <div class="row add-margin">
                



                <div class="login-btn">
                  <button type="submit" class="btn custom-btn">Submit</button>
                </div>
               
              </div>
            </div>
          </div>
		  </form>
		  <!--<div class="row">
		         <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="forgot-pass-div"><a href="{{URL::to('seller/forget-password')}}">Forgot Password?</a></div>
                </div>
		  </div>-->
		  
        </div>
        <div class="container-fluid">
          <div class="col-md-12 col-sm-12 col-xs-12 joinus-seler-div"> <a href="{{URL::to('join-as-seller')}}" class="joinus-seler">Join Us as Seller</a> </div>
        </div>
       
      </div>
    </div>
  </div>
</div>
</body>
</html>