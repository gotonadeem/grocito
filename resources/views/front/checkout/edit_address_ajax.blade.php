<?php $cityStateName = Helper::getCityStateByPincode(); ?>
<div class="new-address-row">
          <div class="form-group d-flex justify-content-between align-items-center">
            <div class="col-md-6">
			 <p class="m-0">State</p>
			<input type="text" class="form-control" id="state" readonly name="state" value="{{$cityStateName->state_name}}"></div>
            <div class="col-md-6">
			 <p class="m-0">City</p>
			<input type="text" class="form-control" id="city" readonly name="city" value="{{$cityStateName->city_name}}"></div>
          </div>
        </div>
		
		<div class="new-address-row">
          <div class="form-group d-flex justify-content-between align-items-center">
            <div class="col-md-12">
			 <p class="m-0">Pincode</p>
			<input type="text" class="form-control" id="pincode" name="pincode" readonly value="{{session('pincode')}}"></div>
          </div>
        </div>
		
        <div class="new-address-inner">
          <div class="new-address-form-row">
            <p>Name</p>
            <div class="form-group d-flex justify-content-start align-items-center">
              <select name="title" class="form-control" id="title">
                <option <?=($data->title=="Mr.")?"selected":""?>  value="Mr.">Mr.</option>
                <option <?=($data->title=="Mrs.")?"selected":""?> value="Mrs.">Mrs.</option>
                <option <?=($data->title=="Miss")?"selected":""?> value="Miss">Miss</option>
              </select>
              <input type="text" class="form-control" name="name" value="{{$data->name}}" id="name" placeholder="First & Last Name">
            </div>
          </div>
          <div class="new-address-form-row">
            <p>Flat / House / Office No.</p>
            <div class="form-group d-flex justify-content-start align-items-center">
              <input type="text" class="form-control" name="house" value="{{$data->house}}" id="house" placeholder="Flat / House / Office No">
            </div>
          </div>
          <div class="new-address-form-row">
            <p>Street / Society / Office Name</p>
            <div class="form-group d-flex justify-content-start align-items-center">
              <input type="text" class="form-control" name="street" id="street" value="{{$data->street}}" placeholder="Street / Society / Office Name">
            </div>
          </div>
          <div class="new-address-form-row">
            <p>Mobile</p>
            <div class="form-group d-flex justify-content-start align-items-center">
              <input type="number" class="form-control" name="mobile" id="mobile" value="{{$data->mobile}}" placeholder="Mobile">
            </div>
          </div>
          <div class="new-address-form-row">
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" class="custom-control-input" id="customHome1" <?=(($data->type=="home")?"checked":'')?> name="type" value="home">
              <label class="custom-control-label" for="customHome1">Home</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" class="custom-control-input" id="customOffice1" <?=(($data->type=="office")?"checked":'')?> name="type" value="office">
              <label class="custom-control-label" for="customOffice1">Office</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" class="custom-control-input" id="customOthers1" <?=(($data->type=="other")?"checked":'')?> name="type" value="other">
              <label class="custom-control-label" for="customOthers1">Others</label>
            </div>
          </div>
          <div class="mt-3">
            <button onclick="address_update()" class="btn new-delivery-address__btn flush--left " type="button" data-dismiss="modal">Continue</button>
            <button class="btn btn--inverted-gray new-delivery-address__btn ml-3" data-dismiss="modal">Cancel</button>
          </div>
        </div>
		