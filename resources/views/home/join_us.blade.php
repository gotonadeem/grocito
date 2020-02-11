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
		<ul class="nav">
		<?php
		if($cms){
			echo $cms->description;
		}
		?>
		</ul>
			{{--<ul class="nav">
    <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>

    <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>

    <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>

    <li>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</li>


    </ul>--}}



    </div>
    
    
    <div class="form-v8-content">
            {{ Form::open(array('url' => 'join-us-store','class'=>'form-detail animate','id'=>'join_us_form','name'=>'join_us_form')) }}
            <div class="login_title">
                <h3 class="title-login">JOIN AS SELLER</h3>
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
							<input class="input-text" type="text" name="business_name" value="{!! old('business_name') !!}" required aria-required="true">
							<span class="label">Full Name*</span><span class="border"></span>
							<span class="error">{{ $errors->first('business_name') }}</span>
						</label>
					</div>
				</div>
              <div class="col-md-12 col-sm-12 col-xs-12">
			    <div class="form-row">
				  <label class="form-row-inner">
				  <input class="input-text" type="text" name="name" value="{!! old('name') !!}" required aria-required="true">
                  <span class="label">Business Name*</span><span class="border"></span>
				  <span class="error">{{ $errors->first('name') }}</span>
                  </label>
				 </div>
              </div>  
			  <div class="col-md-12 col-sm-12 col-xs-12">
			    <div class="form-row">
				  <label class="form-row-inner">
				  <input class="input-text" type="text" name="email" value="{!! old('email') !!}" >
                  <span class="label">Email Address</span><span class="border"></span>
				   <span class="error">{{ $errors->first('email') }}</span>
                   </label>
				 </div>
              </div>  
			  
			  <div class="col-md-6 col-sm-6 col-xs-12">
			    <div class="form-row">
				  <label class="form-row-inner form-row-select">
				  <select class="input-text" name="gender" required aria-required="false">
				   <option value="male">Male</option>
				   <option value="female">Female</option>
				  </select>
                  <span class="label">Gender*</span><span class="border"></span>
				   <span class="error">{{ $errors->first('gender') }}</span>
                   </label>
				 </div>
              </div>
			  <div class="col-md-6 col-sm-6 col-xs-12">
			    <div class="form-row">
				  <label class="form-row-inner form-row-select">
				 <select name="country_id" id="country_list" class="input-text" onchange="get_state(this.value)" required aria-required="true">
                      <?PHP foreach($countries as $vs): ?>
					     <option value="<?=$vs->id;?>"><?=$vs->name;?></option>
                      <?PHP endforeach; ?>
                    </select>
                    <span class="label">Country*</span><span class="border"></span>
					 <span class="error">{{ $errors->first('country_id') }}</span>
                     </label>
				 </div>
              </div>
			  <div class="col-md-6 col-sm-6 col-xs-12">
			    <div class="form-row">
				  <label class="form-row-inner form-row-select">
				 <select name="state_id" id="state_list" class="input-text" onchange="get_city(this.value)">
                      <option value="">Select State</option>
					  @foreach($state as $vs)
					   <option value="{{$vs->state->id}}">{{$vs->state->name}}</option>
					  @endforeach
                    </select>
                     <span class="label">State*</span><span class="border"></span>
					 <span class="error">{{ $errors->first('state_id') }}</span>
                     </label>
				 </div>
              </div> 
			  <div class="col-md-6 col-sm-6 col-xs-12">
			    <div class="form-row">
				  <label class="form-row-inner form-row-select">
				   <select id="city_list" class="input-text"  name="city_id">
                      <option value="">Select City</option>
                    </select>
                    <span class="label">City*</span><span class="border"></span>
					<span class="error">{{ $errors->first('city_id') }}</span>
                    </label>
				 </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-12">
			    <div class="form-row">
				   <label class="form-row-inner">
				  <input class="input-text" type="number" name="mobile" value="{!! old('mobile') !!}" required aria-required="true">
                  <span class="label">Mobile*</span><span class="border"></span>
				   <span class="error">{{ $errors->first('mobile') }}</span>
                   </label>
				 </div>
              </div>
			  <div class="col-md-6 col-sm-6 col-xs-12">
			    <div class="form-row">
				  <label class="form-row-inner">
				  <input class="input-text" type="text" name="pincode" value="{!! old('pincode') !!}" required aria-required="true">
                  <span class="label">Pincode*</span><span class="border"></span>
				   <span class="error">{{ $errors->first('pincode') }}</span>
                   </label>
				 </div>
              </div>
              <div class="col-md-12 col-sm-6 col-xs-12">
			    <div class="form-row">
				  <label class="form-row-inner">
				  <input class="input-text" type="text" name="address_2" value="{!! old('address_2') !!}" required aria-required="true">
                  <span class="label">Address*</span><span class="border"></span>
				  <span class="error">{{ $errors->first('address_2') }}</span>
                  </label>
				 </div>
              </div>
              
			  <div class="col-md-12 col-sm-12 col-xs-12">
			    <div class="form-group">
				  <input class="btn custom-btn submit-seller" type="submit" name="submit" placeholder="Submit">
				 </div>
              </div> 
              <div class="d-flex b-login">
			<a href="{{URL::to('seller/login')}}" class="back-login">Have an account. <b>Sign in</b></a>
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
