@extends('seller.layouts.seller')
@section('content')
<link rel="stylesheet" href="{{ URL::asset('public/front/css/fastselect.min.css') }}">
<script src="{{ URL::asset('public/front/js/fastselect.standalone.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('public/front/css/datepicker.css') }}">
<link rel="stylesheet" href="{{ URL::asset('public/front/css/style2.css') }}">
<div id="rightSidenav" class="right_side_bar right_side_bar_new">
  <div class="catalog-upload">
    <div class="catalog-upload-heading"><h3 class="complete-heading">Complete Profile</h3>
      <div class="myaccount" id="closebutton">
        <div class="user_profile">
          <fieldset id="step1">
		     <input type="hidden" name="seller_image_value" id="seller_image_value" value="<?=$user_info['user_kyc']['seller_image']?>">
             <input type="hidden" name="profile_image_value" id="profile_image_value" value="<?=$user_info['user_kyc']['profile_image']?>">
             <input type="hidden" name="cancel_cheque_value" id="cancel_cheque" value="<?=$user_info['user_kyc']['cancel_cheque']?>">
             <input type="hidden" name="cin_image_value" id="cin_image" value="<?=$user_info['user_kyc']['cin_image']?>">
             <input type="hidden" name="pan_image_value" id="pan_image" value="<?=$user_info['user_kyc']['pan_image']?>">
             <input type="hidden" name="signature_value" id="signature_value" value="<?=$user_info['user_kyc']['signature']?>">
            <form id="step_first" class="set-file-img" method="post" name="step_first" enctype="multipart/form-data" autocomplete="off">
              <h2>Business  Information</h2>
              <div class="input_box_myaccount">
                <!-- business_name as seller full name -->
                <div class="input_box_inner">
                  <label>Full name.* </label>
                  <div class="width100-percent">
                    <input type="text"  placeholder="Full Name" id="business_name" name="business_name" value="<?=isset($user_info['user_kyc']['business_name'])?$user_info['user_kyc']['business_name']:''; ?>" class="business_name">
                  </div>
                </div>
                <div class="input_box_inner mob_num_div">
                  <div class="input_box_inner_inner">
                    <label>Mobile No.* </label>
                    <div class="width100-percent">
                      <input type="number" placeholder="Mobile no" value="{{Auth::user()->mobile}}" id="mobile" name="mobile" class="mobile">
                      <span class="error" id="mobile_error_msg"></span>
                    </div>
				          </div>
                </div>

              </div>
              <div class="input_box_myaccount" >
                <div class="input_box_inner"> 
                  <label>Email Address</label>
                  <div class="width100-percent">
                    <input type="email" placeholder="Email Address" value="{{Auth::user()->email}}" id="email" name="email" class="email">
                    <span class="error" id="email_error_msg"></span>
				          </div>
                </div>
                <div class="input_box_inner">
                  <label>Country</label>
                  <div class="width100-percent">
                    <select name="country_id" id="country_list">
                      <option value="">Select Country</option>
                      <?PHP foreach($countries as $vs): ?>
					  <?PHP if($user_info['user_kyc']['country_id']==$vs->id): ?>
                      <option selected value="<?=$vs->id;?>"><?=$vs->name;?></option>
					   <?PHP else: ?>
					   <option  value="<?=$vs->id;?>"><?=$vs->name;?></option>
					  <?PHP endif; ?>
                      <?PHP endforeach; ?>
                    </select>
                    <div class="error-message">{{ $errors->first('country') }}</div>
                  </div>
                </div>
              </div>
              <div class="input_box_myaccount country-filed">
                
                <div class="input_box_inner">
                  <label>State*</label>
                  <div class="width100-percent">
                    <select name="state_id" id="state_list" >
                      <option value="">Select State</option>
					  <?PHP foreach($state as $vs): ?>
	    				   <option selected value="<?=$vs->state->id;?>"><?=$vs->state->name;?></option>
					  <?PHP endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="input_box_inner">
                  <label>City* </label>
                  <div class="width100-percent">
                    <select id="city_list"  name="city_id"  onchange="getPincode(this.value)">
                      <option value="">Select City</option>
					   <?PHP foreach(Helper::get_city($user_info['user_kyc']['state_id']) as $vs):
                        ?>
					    <?PHP if($user_info['user_kyc']['city_id']==$vs->id): ?>
	    				   <option selected value="<?=$vs->id;?>"><?=$vs->name;?></option>
                         <?PHP else: ?>
    					   <option value="<?=$vs->id;?>"><?=$vs->name;?></option>				 
					   <?PHP endif; ?>
					  <?PHP endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="input_box_inner">
                  <label>Pin code* </label>
                  <div class="width100-percent">
                  <input type="text"  placeholder="Pincode" id="pincode" name="pincode" value="<?=isset($user_info['user_kyc']['pincode'])?$user_info['user_kyc']['pincode']:''; ?>" class="pincode">
                  </div>
                </div>

                <div class="input_box_inner">

                    <label >Delivery Pincode</label>
                    <div class="width100-percent">
                      <select class="form-control delivery_pincode" name="delivery_pincode[]" id="delivery_pincode" multiple>
                        <option value="">Select Pincode</option>
                        <?PHP
                        $pinArr = explode(",", $user_info['user_kyc']->delivery_pincode);
                        foreach($deliveryPincode as $pinList):
                        $selected = in_array( $pinList->pincode, $pinArr ) ? ' selected="selected" ' : '';?>
                        <option value="<?php echo $pinList->pincode; ?>" <?php echo $selected; ?>><?php echo $pinList->pincode; ?></option>
                        <?PHP endforeach; ?>
                      </select>
                      <div class="error-message">{{ $errors->first('delivery_pincode') }}</div>
                    </div>

                </div>

                <div class="input_box_inner">
                  <label>Pickup Address*</label>
                  <div class="width100-percent">
                  <textarea  placeholder="Pickup Address" id="address_1" class="address_1" name="address_1"><?=isset($user_info['user_kyc']['address_1'])?$user_info['user_kyc']['address_1']:''; ?>
</textarea>
                  </div>
                </div>
                <div class="input_box_inner">
                  <label>Latitude* </label>
                  <div class="width100-percent">
                    <input type="text"  placeholder="latitude" id="latitude" name="latitude" value="<?=isset($user_info['user_kyc']['latitude'])?$user_info['user_kyc']['latitude']:''; ?>" class="latitude">
                  </div>
                </div>
                <div class="input_box_inner">
                  <label>Longitude* </label>
                  <div class="width100-percent">
                    <input type="text"  placeholder="Longitude" id="longitude" name="longitude" value="<?=isset($user_info['user_kyc']['longitude'])?$user_info['user_kyc']['longitude']:''; ?>" class="longitude">
                  </div>
                </div>

                <div class="input_box_inner">
                  <label>Food License No. </label>
                  <div class="width100-percent">
                    <input type="text"  placeholder="Food License No." id="food_license_no" name="food_license_no" value="<?=isset($user_info['user_kyc']['food_license_no'])?$user_info['user_kyc']['food_license_no']:''; ?>" class="food_license_no">
                  </div>
                </div>
                <!-- username as Business name -->
                <div class="input_box_inner first_name_mob">
                  <div class="title">
                    <div class="title_input_text">
                      <label>Business Name.* </label>
                      <div class="width100-percent">
                        <input type="text" placeholder="Business Name" value="{{$user_info['username']}}" name="username" id="username" class="username">
                        <span class="error" id="fname_error_msg"></span>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="input_box_inner">
                  <label>Business Reg. No.* </label>
                  <div class="width100-percent">
                    <input type="text"  placeholder="Business Reg. No" id="business_reg_no" name="business_reg_no" value="<?=isset($user_info['user_kyc']['business_reg_no'])?$user_info['user_kyc']['business_reg_no']:''; ?>" class="business_reg_no">
                  </div>
                </div>
              </div>
              <div class="input_box_myaccount address-feild">
                <div class="input_box_inner">
                  <label>Address*</label>
                  <div class="width100-percent">
                  <textarea placeholder="Street Address" id="address_2" class="address_2" name="address_2"><?=isset($user_info['user_kyc']['address_2'])?$user_info['user_kyc']['address_2']:''; ?>
</textarea>
                  </div>
                </div>
              </div>
              <div class="input_box_myaccount">
               <div class="input_box_inner">
                  <label>Upload Logo</label>
                  <div class="width100-percent">
                  <input type="file" style="width:70%" aria-required="true" aria-invalid="false" class="valid"  name="profile_image" id="profile_image">
				  <label id="profile_image-error" class="error" for="profile_image"></label>
				   @if($user_info['user_kyc']['profile_image'])
				   <img src="{{ URL::asset('public/admin/uploads/seller/'.$user_info['user_kyc']['profile_image']) }}" height="50" width="50">
                  @endif

                  </div>
				</div>
                <div class="input_box_inner">
                  <label>Seller Image*</label>
                  <div class="width100-percent">
                  <input type="file" style="width:70%" name="seller_image" id="seller_image">
                   <label id="seller_image-error" class="error" for="seller_image"></label> 
                   @if($user_info['user_kyc']['seller_image'])
				   <img src="{{ URL::asset('public/admin/uploads/seller/'.$user_info['user_kyc']['seller_image']) }}" height="50" width="50">
                   @endif
                  </div>
				</div>
              </div>
              <p class="btn-custum"> <a href="javascript:void(0)" id="step1Next" class="next">Next &gt;</a> </p>
            </form>
          </fieldset>
            <!-- ****************** Step Second ********************-->
          <fieldset id="step2">
            <form id="step_second" class="kyc_css row" name="step_second" action="" autocomplete="off">
            <h2>Complete KYC</h2>
              <div class="col-sm-6">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 form-control-label">Account No.*</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="account_number" id="account_number"  placeholder="Account Number" value="{{$user_info['user_kyc']['account_number']}}">
                    <div class="error-message">{{ $errors->first('account_number') }}</div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 form-control-label">Bank Name*</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="bank_name" id="bank_name"  placeholder="Bank name" value="{{$user_info['user_kyc']['bank_name']}}">
                    <div class="error-message">{{ $errors->first('bank_name') }}</div>
                  </div>
                </div>
              </div>
			  
			    <div class="col-sm-6">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 form-control-label">IFSC Code*</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="ifsc_code" id="ifsc_code"  placeholder="IFSC Code" value="{{$user_info['user_kyc']['ifsc_code']}}">
                    <div class="error-message">{{ $errors->first('cin_number') }}</div>
                  </div>
                </div>
              </div>
			  
			  <div class="col-sm-6">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 form-control-label">Account Holder Name*</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="account_holder_name" value="{{$user_info['user_kyc']['account_holder_name']}}" id="account_holder_name"  placeholder="Account Holder Name">
                    <div class="error-message">{{ $errors->first('account_holder_name') }}</div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 form-control-label">PAN No.*</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="pan_number" id="pan_number"   placeholder="PAN No." value="{{$user_info['user_kyc']['pan_number']}}">
                    <div class="error-message">{{ $errors->first('pan_number') }}</div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 form-control-label">GST No.*</label>
                  <div class="col-sm-8">
                    <input type="text" class="form-control" name="gst_number" id="gst_number" value="{{$user_info['user_kyc']['gst_number']}}"  placeholder="GST No." value="{!! old('gst') !!}">
                    <div class="error-message">{{ $errors->first('gst_number') }}</div>
                  </div>
                </div>
              </div>
			  
              <div class="col-sm-6">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 form-control-label">Upload Cancel Cheque.</label>
                  <div class="col-sm-8">
                    <input type="file" style="width:70%;" class="form-control" name="cancel_cheque" value="{{$user_info['user_kyc']['cancel_cheque']}}">
                    <div class="error-message">{{ $errors->first('cancel_cheque') }}</div>
                     <label id="cancel_cheque-error"  for="cancel_cheque"></label>
					 @if($user_info['user_kyc']['cancel_cheque'])
					<img src="{{ URL::asset('public/admin/uploads/seller/'.$user_info['user_kyc']['cancel_cheque']) }}" height="50" width="50">
				     @endif
					 
				  </div>
                </div>
              </div>
              
              <div class="col-sm-6">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-sm-4 form-control-label">Upload PAN Copy</label>
                  <div class="col-sm-8">
                    <input type="file" style="width:70%;" class="form-control" name="pan_image" value="{{$user_info['user_kyc']['pan_image']}}">
                    <div class="error-message">{{ $errors->first('pan_image') }}</div>
					 <label id="pan_image-error"  for="pan_image"></label>
					  @if($user_info['user_kyc']['pan_image'])
					<img src="{{ URL::asset('public/admin/uploads/seller/'.$user_info['user_kyc']['pan_image']) }}" height="50" width="50">
				      @endif
                  </div>
                </div>
              </div>
              
              
			  
			  
              <p class="btn-custum"> <a href="#" id="step1Prev" class="prev">&lt; Back</a> <a href="#" id="step2Next" class="next">Next &gt;</a> </p>
            </form>
          </fieldset>
          <fieldset id="step3">
            <form id="form_step3" enctype="multipart/form-data" autocomplete="off" class="doc-info">
              <h2>Seller Agreement</h2>
              <div class="article-summary full-width"> 
			  <div class="text-agreement">
			  <?PHP
			   $tillDate = date('d-m-Y', strtotime("+3 months", strtotime(date('d-m-Y'))));
			   $agreement_data= str_replace("@@current_date@@",date('d-m-Y'),$agreement->description);
			   $agreement_data= str_replace("@@seller_name@@",$user_info['user_kyc']['f_name']." ".$user_info['user_kyc']['l_name'],$agreement_data);
			   $agreement_data= str_replace("@@seller_address@@",$user_info['user_kyc']['address_1'],$agreement_data);
			   $agreement_data= str_replace("@@valid_till@@",$tillDate,$agreement_data);
			   $agreement_data= str_replace("@@commission@@",$user_info['user_kyc']['cartlay_commission'],$agreement_data);
			  ?>
			  {!!$agreement_data!!}
                <div style="text-align:center;"> </div>
                </div> </div>
                <div class="term-condition_div">
                <div class="termcondition_accept">
                <p><input checked type="checkbox" name="tc" id="tc"><span>Yes, I Agree <a href="#">Seller Agreement.</a></span></p>
                </div>
                <!--<div class="customer_sign">
                <label>Upload signature</label>
                <input type="file" name="signature" id="signature">
				<label id="signature-error" class="error" for="signature_image"></label>
				   @if($user_info['user_kyc']['signature'])
					<img src="{{ URL::asset('public/admin/uploads/seller/'.$user_info['user_kyc']['signature']) }}" height="50" width="50">
				      @endif
                </div>-->
                </div>
              <p class="btn-custum"> <a href="#" id="step2Prev" class="prev">&lt; Back</a> </p>
              <input id="Submitbtn" class="submit" name="Submitbtn" type="button" value="Submit" />
            </form>
          </fieldset>
        </div>
      </div>
    </div>
    </div>
</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script> 
 <!-- Bootbox -->
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
<script src="{{ URL::asset('public/front/js/jquery.validate.min.js') }}"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/additional-methods.min.js"></script>
<script>
$(document).ready(function(){
});
</script> 
<script>
/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function myFunction_btn() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script> 
<script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script> 
<script src="{{ URL::asset('public/front/js/bootstrap-datepicker.js') }}"></script> 

<script src="{{ URL::asset('public/front/developer/js/page_js/complete_profile.js') }}"></script> 
<script>
        //CKEDITOR.replace( 'description' );
		$('.datepicker').datepicker()

</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
<script type="text/javascript">

  $(".delivery_pincode").select2({
    tags: false,
    placeholder: "Select a pincode",
  })
</script>
<style>
.error
{
 font-size: 12px;
}
</style>
@endsection