@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <div class="row">
						<h3 class="pull-left"><a href="javascript:void(0)" class="btn btn-info button_margin_left">Seller's Personal Information</a></h3>
						<div class="text-right"><a href="{{ URL::to('admin/seller/seller-list') }}" class="btn btn-primary waves-effect waves-light button_margin_right">Back</a>
                        <div class="ibox-tools">
                        </div>
						</div>
                    </div>
				    {{ Form::open(array('url' =>'admin/seller/store-seller','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'add_seller','name'=>'add_seller')) }}
                                 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Full Name<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
                                         <input type="text" autocomplete="off" class="form-control" name="business_name" id="business_name" value="{!! old('business_name') !!}" placeholder="Full name">
                                         <div class="error-message">{{ $errors->first('business_name') }}</div>
                                     </div>
                                 </div>
								 </div>    
								 
								 
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Business Name<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
                                         <input type="text" autocomplete="off" class="form-control" onblur="check_username(this.value,'username')" name="username" id="username" value="{!! old('username') !!}" placeholder="Business Name">
                                         <div class="error-message " id="username_msg">{{ $errors->first('username') }}</div>
                                     </div>
                                 </div>
								 </div>
								 
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 form-control-label">Email<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" autocomplete="off" class="form-control" name="email" onblur="check_username(this.value,'email')" id="email"  placeholder="Email" value="{!! old('email') !!}">
                                            <div class="error-message" id="email_msg">{{ $errors->first('email') }}</div>
                                        </div>
                                    </div>
                                </div>
							   <div class="col-sm-6">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Mobile no.<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
                                         <input type="number" autocomplete="off" maxlength="10" class="form-control" onblur="check_username(this.value,'mobile')" name="mobile" id="mobile"  placeholder="mobile" value="{!! old('mobile') !!}" >
                                         <div class="error-message" id="mobile_msg">{{ $errors->first('mobile') }}</div>
                                     </div>
                                 </div>
								 </div> 
								 <div class="col-sm-6">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Alternate Mobile no.</label>
                                     <div class="col-sm-8">
                                         <input type="number" autocomplete="off" maxlength="10"  class="form-control" name="alternate_mobile_no" id="alternate_mobile_no"  placeholder="Alternate mobile" value="{!! old('alternate_mobile_no') !!}" >
                                         <div class="error-message">{{ $errors->first('alternate_mobile_no') }}</div>
                                     </div>
                                 </div>
								 </div>
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 form-control-label">Country</label>
                                        <div class="col-sm-8">
                                            <select class="form-control country_list" name="country_id" onchange="get_state(this.value)">
                                                <option value="">Select Country</option>
                                                <?PHP foreach($country_list as $vs): ?>
                                                 <option value="<?=$vs->id;?>"><?=$vs->name;?></option>
                                                <?PHP endforeach; ?>
                                            </select>
                                            <div class="error-message">{{ $errors->first('country_id') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 form-control-label">State</label>
                                        <div class="col-sm-8">
                                            <select class="form-control state_list" name="state_id" id="state_list" onchange="get_city(this.value)">
                                                <option value="">Select State</option>

                                            </select>
                                            <div class="error-message">{{ $errors->first('state_id') }}</div>
                                        </div>
                                    </div>
                                </div>

								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">City</label>
                                     <div class="col-sm-8">
                                         <select class="form-control city_list" name="city_id" id="city_list" onchange="getPincode(this.value)">
                                             <option value="">Select City</option>
                                         </select>
                                         <div class="error-message">{{ $errors->first('city_id') }}</div>
                                     </div>
                                 </div>
								 </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Delivery Pincode</label>
                                <div class="col-sm-8">
                                    <select class="form-control delivery_pincode" name="delivery_pincode[]" id="delivery_pincode" multiple>
                                        <option value="">Select Pincode</option>
                                    </select>
                                    <div class="error-message">{{ $errors->first('delivery_pincode') }}</div>
                                </div>
                            </div>
                        </div>
								 
								 		 <div class="col-sm-6">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Pickup Address</label>
                                     <div class="col-sm-8">
                                         <textarea id="address_1" autocomplete="off" class="form-control" placeholder="Address1" name="address_1"> {!! old('address_1') !!} </textarea>
                                         <div class="error-message">{{ $errors->first('address_1') }}</div>
                                     </div>
                                 </div>
								 </div>
								 <div class="col-sm-6">
								  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Address</label>
                                     <div class="col-sm-8">
                                         <textarea id="address_1" autocomplete="off" placeholder="Address2" class="form-control" name="address_2">  {!! old('address_2') !!}</textarea>
                                         <div class="error-message">{{ $errors->first('address_2') }}</div>
                                     </div>
                                 </div>
								 </div> 
								 <div class="col-sm-6">
								  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Pincode</label>
                                     <div class="col-sm-8">
                                            <input type="text" class="form-control" name="pincode" id="pincode"  placeholder="Pincode" value="{!! old('pincode') !!}">
                                             <div class="error-message">{{ $errors->first('pincode') }}</div>
                                     </div>
                                 </div>
								 </div>
								  <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Upload Logo</label>
                                     <div class="col-sm-8">
                                         <input type="file" class="form-control" name="profile_image" id="profile_image">
                                         <div class="error-message">{{ $errors->first('profile_image') }}</div>
                                     </div>
                                 </div>	
								 </div>
								  <div class="col-sm-6">	 
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Seller Image</label>
                                     <div class="col-sm-8">
                                         <input type="file" class="form-control" name="seller_image" id="seller_image">
                                         <div class="error-message">{{ $errors->first('seller_image') }}</div>
                                     </div>
                                 </div>	 
								 </div>
								 
								  <div class="col-sm-12">
								   <a href="javascript:void(0)" class="btn btn-info">Complete KYC</a><br><br>
						           </div>
								    <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Account No.</label>
                                     <div class="col-sm-8">
                                         <input type="text" class="form-control" name="account_number" id="cin"  placeholder="Account Number" value="{!! old('account_number') !!}">
                                         <div class="error-message">{{ $errors->first('account_number') }}</div>
                                     </div>
                                 </div>
								 </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Bank Name.</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="bank_name" id="cin"  placeholder="bank name" value="{!! old('bank_name') !!}">
                                    <div class="error-message">{{ $errors->first('bank_name') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">IFSC Code</label>
                                     <div class="col-sm-8">
                                         <input type="text" class="form-control" placeholder="IFSC Code" name="ifsc_code" id="ifsc_code">
                                         <div class="error-message">{{ $errors->first('ifsc_code') }}</div>
                                     </div>
                                 </div>	 
								 </div>
								 
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Account Holder Name</label>
                                     <div class="col-sm-8">
                                         <input type="text" class="form-control" name="account_holder_name" id="account_holder_name"  placeholder="Account Holder Name" value="{!! old('account_holder_name') !!}">
                                         <div class="error-message">{{ $errors->first('account_holder_name') }}</div>
                                     </div>
                                 </div>
								 </div>
								 
								 	 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Upload Cancel Cheque.</label>
                                     <div class="col-sm-8">
                                          <input type="file" class="form-control" name="cancel_cheque" id="cancel_cheque">
                                        <div class="error-message">{{ $errors->first('cancel_cheque') }}</div>
                                     </div>
                                 </div>
								 </div>
								 <!--<div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">CIN No.</label>
                                     <div class="col-sm-8">
                                         <input type="text" class="form-control" name="cin_number" id="cin"  placeholder="CIN No." value="{!! old('cin') !!}">
                                         <div class="error-message">{{ $errors->first('cin_number') }}</div>
                                     </div>
                                 </div>
								 </div>-->
								 
								 
								
							
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">PAN No.</label>
                                     <div class="col-sm-8">
                                         <input type="text" class="form-control" name="pan_number" id="pan"  placeholder="PAN No." value="{!! old('pan') !!}">
                                         <div class="error-message">{{ $errors->first('pan_number') }}</div>
                                     </div>
                                 </div>
								 </div>
								  <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Upload PAN Copy</label>
                                     <div class="col-sm-8">
                                         <input type="file" class="form-control" name="pan_image" id="pan_img">
                                         <div class="error-message">{{ $errors->first('pan_image') }}</div>
                                     </div>
                                 </div>	
								 </div>	
								 
								 <!--<div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">TAN No.</label>
                                     <div class="col-sm-8">
                                         <input type="text" class="form-control" name="tan_number" id="tan"  placeholder="TAN No." value="{!! old('tan') !!}">
                                         <div class="error-message">{{ $errors->first('tan_number') }}</div>
                                     </div>
                                 </div>
								 </div>-->
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">GST No.</label>
                                     <div class="col-sm-8">
                                         <input type="text" class="form-control" name="gst_number" id="gst"  placeholder="GST No." value="{!! old('gst') !!}">
                                         <div class="error-message">{{ $errors->first('gst_number') }}</div>
                                     </div>
                                 </div>
								 </div>
            
								
                                     <div class="form-group row">
                                         <div class="col-sm-12">
                                             <button type="submit" name="add_user"  class="btn btn-primary waves-effect waves-light pull-right submit_margin">
                                                 Submit
                                             </button>
                                            
                                         </div>
                                     </div>
                                 {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    <script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/dataTables/datatables.min.js') }}"></script>
    <!-- Custom and plugin javascript -->
    <script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>
    <!-- Page-Level Scripts -->
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
    </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/seller.js') }}"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
        <script type="text/javascript">
            $(".country_list").select2();
            $(".state_list").select2();
            $(".city_list").select2();
            $(".delivery_pincode").select2();
        </script>

@stop
