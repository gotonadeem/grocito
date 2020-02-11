<?php
/**
 * Created by PhpStorm.
 * User: wingstud
 * Date: 10/8/17
 * Time: 12:49 PM
 */
?>
@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
					
					 <div class="col-md-12"><h4>Edit Seller</h4></div>
                        <div class="row">
						<h3 class="pull-left"><a href="javascript:void(0)" class="btn btn-info button_margin_left">Seller's Busines Information</a></h3>
						<div class="text-right"><a href="{{ URL::previous() }}" class="btn btn-primary waves-effect waves-light button_margin_right">Back</a>
                        <div class="ibox-tools">
                        </div>
						</div>
                    </div>
				     {{ Form::model($user,array('url' => 'admin/seller/edit-seller/'.$user->user->id,'class'=>'form-horizontal','enctype'=>'multipart/form-data')) }}
                                 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Full Name<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
									     {{Form::text('business_name',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'business_name','placeholder'=>'Full Name'])}}
                                         <div class="error-message">{{ $errors->first('business_name') }}</div>
                                     </div>
                                 </div>
								 </div>    
							
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Business Name<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
									 {{Form::text('username',$user->user->username,['class'=>'form-control','autocomplete'=>'off','onblur'=>"check_username(this.value,'Business Name')",'id'=>'username','placeholder'=>'Username'])}}
                                         <div class="error-message " id="username_msg">{{ $errors->first('username') }}</div>
                                     </div>
                                 </div>
								 </div>
								 
                                <div class="col-sm-6">
                                    <div class="form-group row">
                                        <label for="inputEmail3" class="col-sm-4 form-control-label">Email<span class="text-danger">*</span></label>
                                        <div class="col-sm-8">
										 {{Form::text('email',$user->user->email,['class'=>'form-control','autocomplete'=>'off','onblur'=>"check_username(this.value,'email')",'id'=>'email','placeholder'=>'Email Address'])}}
                                            <div class="error-message" id="email_msg">{{ $errors->first('email') }}</div>
                                        </div>
                                    </div>
                                </div>
							   <div class="col-sm-6">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Mobile no.<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
									  {{Form::text('mobile',$user->user->mobile,['class'=>'form-control','type'=>'number','autocomplete'=>'off','onblur'=>"check_username(this.value,'mobile')",'id'=>'mobile','placeholder'=>'Mobile'])}}
                                         <div class="error-message" id="mobile_msg">{{ $errors->first('mobile') }}</div>
                                     </div>
                                 </div>
								 </div> 
								 <div class="col-sm-6">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Alternate Mobile no.</label>
                                     <div class="col-sm-8">
									   {{Form::text('alternate_mobile_no',$user->alternate_mobile_no,['class'=>'form-control','maxlength'=>'10','type'=>'number','autocomplete'=>'off','id'=>'alternate_mobile_no','placeholder'=>'Alternate Mobile No'])}}
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
                                                <?PHP foreach($country_list as $vs):
                                                   if($vs->id==$user->country_id):
												?>												  
                                                 <option selected value="<?=$vs->id;?>"><?=$vs->name;?></option>
												 <?PHP  else: ?>
												  <option value="<?=$vs->id;?>"><?=$vs->name;?></option>
												 <?PHP endif; ?>
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
												<?PHP foreach(Helper::get_state($user->country_id) as $vs): ?>
												<?PHP if($user->state_id==$vs->id): ?>
												   <option selected value="<?=$vs->id;?>"><?=$vs->name;?></option>
												 <?PHP else: ?>
												   <option value="<?=$vs->id;?>"><?=$vs->name;?></option>				 
											     <?PHP endif; ?>
											     <?PHP endforeach; ?>
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
											 <?PHP foreach(Helper::get_city($user->state_id) as $vs): ?>
											 <?PHP if($user->city_id==$vs->id): ?>
											   <option selected value="<?=$vs->id;?>"><?=$vs->name;?></option>
											 <?PHP else: ?>
											   <option value="<?=$vs->id;?>"><?=$vs->name;?></option>				 
										     <?PHP endif; ?>
										     <?PHP endforeach; ?>
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
                                        <?PHP
                                        $pinArr = explode(",", $user->delivery_pincode);
                                        foreach($deliveryPincode as $pinList):
                                        $selected = in_array( $pinList->pincode, $pinArr ) ? ' selected="selected" ' : '';?>
                                        <option value="<?php echo $pinList->pincode; ?>" <?php echo $selected; ?>><?php echo $pinList->pincode; ?></option>
                                        <?PHP endforeach; ?>
                                    </select>
                                    <div class="error-message">{{ $errors->first('delivery_pincode') }}</div>
                                </div>
                            </div>
                        </div>
								 
								 		 <div class="col-sm-6">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Pickup Address</label>
                                     <div class="col-sm-8">
									    {{Form::textarea('address_1',$user->address_1,['class'=>'form-control','rows'=>2,'autocomplete'=>'off','id'=>'address_1','placeholder'=>'Pickup Address'])}}
                                        <div class="error-message">{{ $errors->first('address_1') }}</div>
                                     </div>
                                 </div>
								 </div>
								 <div class="col-sm-6">
								  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Address</label>
                                     <div class="col-sm-8">
									     {{Form::textarea('address_2',$user->address_2,['class'=>'form-control','rows'=>2,'autocomplete'=>'off','id'=>'address_2','placeholder'=>'Address'])}} 
                                         <div class="error-message">{{ $errors->first('address_2') }}</div>
                                     </div>
                                 </div>
								 </div> 
								 <div class="col-sm-6">
								  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Pincode</label>
                                     <div class="col-sm-8">
									 	   {{Form::text('pincode',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'pincode','placeholder'=>'Pincode'])}}
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
										 @if($user->profile_image)
										 <img src="{{ URL::asset('public/admin/uploads/seller/') }}/<?=$user->profile_image?>" height="50" width="50">
                                         @endif
									 </div>
                                 </div>	
								 </div>
								  <div class="col-sm-6">	 
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Seller Image</label>
                                     <div class="col-sm-8">
                                         <input type="file" class="form-control" name="seller_image" id="seller_image">
                                         <div class="error-message">{{ $errors->first('seller_image') }}</div>
										  @if($user->seller_image)
										 <img src="{{ URL::asset('public/admin/uploads/seller/') }}/<?=$user->seller_image?>" height="50" width="50">
                                         @endif
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
									  {{Form::text('account_number',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'account_number','placeholder'=>'Account Number'])}}
                                       <div class="error-message">{{ $errors->first('account_number') }}</div>
										 
                                     </div>
                                 </div>
								 </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Bank Name</label>
                                <div class="col-sm-8">
                                    {{Form::text('bank_name',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'bank_name','placeholder'=>'Bank Name'])}}
                                    <div class="error-message">{{ $errors->first('bank_name') }}</div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Upload Cancel Cheque.</label>
                                     <div class="col-sm-8">
                                          <input type="file" class="form-control" name="cancel_cheque" id="cancel_cheque">
                                        <div class="error-message">{{ $errors->first('cancel_cheque') }}</div>
										  @if($user->cancel_cheque)
										 <img src="{{ URL::asset('public/admin/uploads/seller/') }}/<?=$user->cancel_cheque?>" height="50" width="50">
                                         @endif
                                     </div>
                                 </div>
								 </div>
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">IFSC Code.</label>
                                     <div class="col-sm-8">
									 	{{Form::text('ifsc_code',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'ifsc_code','placeholder'=>'Ifsc code'])}} 
                                         <div class="error-message">{{ $errors->first('ifsc_code') }}</div>
									   </div>
                                 </div>
								 </div>
								 <div class="col-sm-6">	 
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Account Holder Name</label>
                                     <div class="col-sm-8">
                                         {{Form::text('account_holder_name',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'account_holder_name','placeholder'=>'Account Holder Name'])}}
                                         <div class="error-message">{{ $errors->first('account_holder_name') }}</div>
										
                                     </div>
                                 </div>	 
								 </div> 
								
							
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">PAN No.</label>
                                     <div class="col-sm-8">
									  	{{Form::text('pan_number',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'pan_number','placeholder'=>'PAN No.'])}} 
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
										   @if($user->pan_image)
										 <img src="{{ URL::asset('public/admin/uploads/seller/') }}/<?=$user->pan_image?>" height="50" width="50">
                                         @endif
                                     </div>
                                 </div>	
								 </div>	
								 
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">GST No.</label>
                                     <div class="col-sm-8">
									   {{Form::text('gst_number',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'gst_number','placeholder'=>'GST No.'])}}
                                          <div class="error-message">{{ $errors->first('gst_number') }}</div>
                                     </div>
                                 </div>
								 </div>
								 
								 {{--<div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Grocito Commission(%)</label>
                                     <div class="col-sm-8">
									   {{Form::text('cartlay_commission',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'gst_number','placeholder'=>'Commision'])}}
                                          <div class="error-message">{{ $errors->first('cartlay_commission') }}</div>
                                     </div>
                                 </div>
								 </div>--}}
								 <input type="hidden" id="verfiy_status" name="verfiy_status" value="{{$verifyStatus}}">
                                     <div class="form-group row">
                                         <div class="col-sm-12">
                                             <button type="submit" name="edit_user"  class="btn btn-primary waves-effect waves-light pull-right submit_margin">
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
            //$(".delivery_pincode").select2();
            $(".delivery_pincode").select2({
                tags: false,
                placeholder: "Select a pincode",
            })
        </script>

@stop