<!doctype html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
    <meta charset="utf-8">
    <title>{{env('APP_NAME')}}!! JOIN AS SELLER</title>
	 <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="stylesheet" href="{{ URL::asset('public/front/css/style.css') }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="{{ URL::asset('public/front/js/jquery.validate.min.js') }}"></script> 
	<style>
	.error{ color:red !important; }
	</style>
</head>
<body>
<div class="wraper">
    <div class="seller-modal-signup">
    
    <div class="seller-modal-signup-inner">
    
    <div class="seller-content-para">
    
    <div class="logo-seller-panel"><a href="{{url::to('/')}}"><img class="img-responsive" src="{{ URL::asset('public/admin/img/logo.png') }}"></a></div>
    
    <h2>Join us as Seller</h2>

    </div>

    <div class="form-v8-content">
            {{ Form::open(array('url' => 'join-us-store','class'=>'form-detail animate','id'=>'join_us_form','name'=>'join_us_form')) }}
            <div class="login_title">
                <h3 class="title-login">An OTP has been sent to your registered mobile number</h3>
                <div class="logo-seller-panel"><a href="{{url::to('/')}}"><img class="img-responsive" src="{{ URL::asset('public/admin/img/logo.png') }}"></a></div>
	
                @if(Session::has('success_message'))
                    <p class="alert alert-success">{{ Session::get('success_message') }}</p>
                @endif

				@if(Session::has('error_message'))
                    <p class="alert alert-danger">{{ Session::get('error_message') }}</p>
                @endif
            </div>
            <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
			    <div class="form-row">
				  <label class="form-row-inner">
				  <input class="input-text" type="text" name="name" value="{!! old('name') !!}" required aria-required="true">
                  <span class="label">Enter Otp</span><span class="border"></span>
				  <span class="error">{{ $errors->first('name') }}</span>
                  </label>
				 </div>
              </div>  
			  <div class="col-md-12 col-sm-12 col-xs-12">
			    <div class="form-group">
				  <input class="btn custom-btn submit-seller" type="submit" name="submit" placeholder="Submit">
				 </div>
              </div> 

			 </div> 
            </div>
            
			
        </form>
        </div>
        
        </div>
    </div>
</div>
    <script>
		ASSET_URL = '{{ URL::asset('') }}';
		BASE_URL='{{ URL::to('/') }}';
	</script>
	
<script src="{{ URL::asset('public/front/developer/js/page_js/join_us.js') }}"></script> 
</body>
</html>
