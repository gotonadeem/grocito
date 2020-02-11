@extends('front.layout.front')
@section('content')
<section class="myAccount my-5">

  <div class="container">
    <h2 class="shoping-cart-text">Update Information</h2>
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="account-info">
         
          {{--<div class="title">
            <h3 class="m-0">Address Book <a href="#" class="maddress float-right"><span>Manage Addresses</span></a></h3>
          </div>--}}
		    
          <div class="block-content">
          <div class="row">
		     
			  
          
            <div class="col-8 offset-2">
            <div class="box box-shipping-address">
						  <div class="box-content">
								 <div class="my-profile-content">
				  
					   {{ Form::model('',array('url' => 'update-user-profile','class'=>'form-horizontal','name'=>'update_profile',"enctype"=>"multipart/form-data")) }}
							<div class="row">
							  <div class="col-md-6 col-sm-6">
								<div class="form-group row align-items-center">
									<div class="col-4">
										<label>First Name<sub>*</sub></label>
									</div>
									<div class="col-8">
										<input type="text" class="form-control" value="{{$userData->user_kyc->f_name}}" placeholder="Enter Name" id="f_name" name="f_name">
										<span class="mobileMsg error">{{ $errors->first('f_name') }}</span>
									</div>
								</div>
							  </div>
								<div class="col-md-6 col-sm-6">
									<div class="form-group row align-items-center">
										<div class="col-4">
											<label>Last Name<sub>*</sub></label>
										</div>
										<div class="col-8">
											<input type="text" class="form-control" value="{{$userData->user_kyc->l_name}}" placeholder="Enter Name" id="l_name" name="l_name">
											<span class="mobileMsg error">{{ $errors->first('l_name') }}</span>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="form-group row align-items-center">
										<div class="col-4">
											<label>Email<sub>*</sub></label>
										</div>
										<div class="col-8">
											<input type="text" class="form-control" value="{{$userData->email}}" placeholder="Enter Email" id="email" name="email">
											<span class="mobileMsg error">{{ $errors->first('email') }}</span>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="form-group row align-items-center">
										<div class="col-4">
											<label>Profile Image<sub>*</sub></label>
										</div>
										<div class="col-8">
											<input type="file" class="form-control" id="profile_image" name="profile_image">
										</div>
										<div class="col-4"></div>
										<div class="col-8">
										 <?PHP
											if($userData->user_kyc->profile_image!=""):
												$url=URL::to('public/front/user_profile/'.$userData->user_kyc->profile_image);
											 else:
												$url=URL::to('public/images/default.png'); 	
											endif;
											
											?>
											<img src="{{$url}}" style="width: 100px; height: 100px; border-radius: 100%; margin-top: 10px;">
										</div>
									</div>
								</div>

							<div class="col-md-12 col-sm-12 text-right">
								<button type="submit" class="btn custom-btn py-2">Update </button>
							</div>
						  </form>
				  
				  
				  
				  
				  </div>
              </div>
            </div>
            </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endSection