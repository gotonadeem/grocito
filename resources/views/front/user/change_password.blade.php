@extends('front.layout.front')
@section('content')
<section class="myAccount my-5">
  <div class="container">
    <h2 class="shoping-cart-text">Change Password</h2>
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="account-info">
          <div class="title">
		  <div> @if(Session::has('success_message'))
				<p class="alert alert-info">{{ Session::get('success_message') }}</p>
				@endif</div>
            <h3 class="m-0">Change Password</h3>
          </div>
            {{ Form::open(array('url' =>'change-password','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'add_seller','name'=>'add_seller')) }}
		  <div class="block-content" style="margin-left: 30%;">
            <div class="row">
              <div class="col-md-6 col-sm-6">
                   <div class="inner-input-field">
					  <div class="form-group">
						<label for="First Name">New Password<sup>*</sup></label>
						<input type="password" placeholder="New Password" name="new_password" class="form-control">
						{{ $errors->first('new_password') }}
					  </div>
					  <div class="form-group">
						<label for="Last Name">Confirm password<sup>*</sup></label>
						<input type="password" placeholder="Confirm Password" name="password_confirmation" class="form-control">
						{{ $errors->first('password_confirmation') }}
					  </div>
					  
					  <div class="form-group">
						<button id="place_order" class="btn custom-btn text-uppercase w-100 mt-3">Change Password</button>
					  </div>
					</div>  
              </div>
            </div>
          </div>
		  </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endSection