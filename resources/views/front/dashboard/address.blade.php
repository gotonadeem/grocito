@extends('front.layout.front')
@section('content')
<section class="myAccount my-5">
  <div class="container">
    <h2 class="shoping-cart-text">Account Address</h2>
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="account-info">
         
         {{-- <div class="title">
            <h3 class="m-0">Address Book <a href="#" class="maddress float-right"><span>Manage Addresses</span></a></h3>
          </div>--}}
		    
          <div class="block-content">
          <div class="row">
		     
			  
          
            <div class="col-md-6 col-sm-6" style="margin-left: 23%;">
            <div class="box box-shipping-address"> <strong class="box-title"> </strong>
						  <div class="box-content">
								 <div class="my-profile-content">
				  
					   {{ Form::model('',array('url' => 'update-address/'.$address->id,'class'=>'form-horizontal','name'=>'add_bank')) }}
							<div class="row">
							  <div class="col-md-6 col-sm-6">
								<div class="form-group">
								  <label>Name:</label>
								  <input type="text" class="form-control" value="{{$address->name}}" placeholder="Enter Name" id="name" name="name">
								  <span class="mobileMsg error">{{ $errors->first('name') }}</span>
								</div>
							  </div>

							  
							  <div class="col-md-6 col-sm-6">
								<div class="form-group">
								  <label>Address:</label>
								  <input type="text" class="form-control" value="{{$address->address}}" name="address" id="address" placeholder="Enter Address">
								  <span class="mobileMsg error">{{ $errors->first('address') }}</span>
								</div>
							  </div>
							  
							  <div class="col-md-6 col-sm-6">
								<div class="form-group">
								  <label>Flat / House / Office No:</label>
								  <input type="text" class="form-control" value="{{$address->house}}" name="house" id="house" placeholder="House">
								  <span class="mobileMsg error">{{ $errors->first('house') }}</span>
								</div>
							  </div>
								<div class="col-md-6 col-sm-6">
									<div class="form-group">
										<label>Street / Society / Office Name:</label>
										<input type="text" class="form-control" value="{{$address->street}}" name="street" id="street" placeholder="street">
										<span class="mobileMsg error">{{ $errors->first('street') }}</span>
									</div>
								</div>
								<div class="col-md-6 col-sm-6">
									<div class="form-group">
										<label>Pincode:</label>
										<input type="text" class="form-control" value="{{$address->pincode}}" name="pincode" id="pincode" placeholder="Enter Pincode" >
										<span class="mobileMsg error">{{ $errors->first('pincode') }}</span>
									</div>
								</div>
							  <div class="col-md-6 col-sm-6">
								<div class="form-group">
								  <label>City:</label>
								  <select name="city" class="form-control" disabled>
								     <option>Jaipur</option>
								  </select>
								  <span class="mobileMsg error">{{ $errors->first('city') }}</span>
								</div>
							  </div>
							
							  <div class="col-md-6 col-sm-6">
								<div class="form-group">
								  <label>State:</label>
								   <select name="state" class="form-control" disabled>
								     <option>Rajsthan</option>
								  </select>
								   <span class="mobileMsg error">{{ $errors->first('state') }}</span>
								</div>
							  </div>
							  

							  
							  <div class="col-md-12 col-sm-12">
								<button type="submit" class="btn custom-btn w-100 py-2">Update Address</button>
							  </div>
							</div>
						  </form>
				  
				  
				  
				  
				  </div>
              </div>
            </div>
            </div>
            </div>
		{{--	<div class="row">
			    @foreach($address_list as $vs)
			     <div class="col-md-4" style="background: aliceblue;">
				  <p><a href="">Make It Default Address</a> &nbsp;&nbsp;<a href="{{URL::to('edit-address/'.$vs->id)}}">Edit</a></p>
			     <address>
				  {{$vs->name}}<br>
				  {{$vs->mobile}}<br>
				  {{$vs->house}}<br>
				  {{$vs->state}}<br>
				  {{$vs->city}}<br>
				  {{$vs->pincode}}<br>
                </address>
				</div>
			  @endforeach
			</div>--}}
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endSection