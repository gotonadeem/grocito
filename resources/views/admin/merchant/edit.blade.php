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
					
					 <div class="col-md-12"><h4>Edit Delivery Boy</h4></div>
                        <div class="row">
						<h3 class="pull-left"><a href="javascript:void(0)" class="btn btn-info button_margin_left">Delivery Boy's Information</a></h3>
						<div class="text-right"><a href="{{URL::to('admin/delivery-boy/delivery-boy-list')}}" class="btn btn-primary waves-effect waves-light button_margin_right">Back</a>
                        <div class="ibox-tools">
                        </div>
						</div>
                    </div>
				     {{ Form::model($user,array('url' => 'admin/delivery-boy/edit-delivery-boy/'.$user->user->id,'class'=>'form-horizontal','enctype'=>'multipart/form-data')) }}
                                 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">First Name<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
									     {{Form::text('f_name',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'f_name','placeholder'=>'First Name'])}}
                                         <div class="error-message">{{ $errors->first('f_name') }}</div>
                                     </div>
                                 </div>
								 </div>    
								 
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Last Name<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
									    {{Form::text('l_name',NULL,['class'=>'form-control','autocomplete'=>'off','id'=>'l_name','placeholder'=>'Last Name'])}}
                                         <div class="error-message">{{ $errors->first('l_name') }}</div>
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
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">City</label>
                                     <div class="col-sm-8">
                                         <select class="form-control" name="city_id" onchange="get_warehouse(this.value)" id="city_list">
                                             <option value="">Select City</option>
											  @foreach($city_list as $vs)
											   <?PHP if($user->city_id==$vs->id): ?>
											   <option selected value="<?=$vs->id;?>"><?=$vs->name;?></option>
											 <?PHP else: ?>
											   <option value="<?=$vs->id;?>"><?=$vs->name;?></option>				 
										     <?PHP endif; ?>
											  @endforeach
											  
                                         </select>
                                         <div class="error-message">{{ $errors->first('city_id') }}</div>
                                     </div>
                                 </div>
								 </div>

								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Select Warehouse</label>
                                     <div class="col-sm-8">
                                         <select class="form-control" name="warehouse_id" id="warehouse_id">
                                             <option value="">Select Warehouse</option>  
                                             @foreach($warehouse_list as $vs)
											  <?PHP if($user->warehouse_id==$vs->id): ?>
											     <option value="{{$vs->id}}">{{$vs->name}}</option>
											 <?PHP else: ?>
											     <option value="{{$vs->id}}">{{$vs->name}}</option>				 
										     <?PHP endif; ?>
											
                                             @endforeach   											 
										 </select>
                                         <div class="error-message">{{ $errors->first('warehouse_id') }}</div>
                                     </div>
                                 </div>
								 </div>
								 
								  
								 
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label"> Address</label>
                                     <div class="col-sm-8">
									    {{Form::textarea('address_1',$user->address_1,['class'=>'form-control','rows'=>2,'autocomplete'=>'off','id'=>'address_1','placeholder'=>'Pickup Address'])}}
                                        <div class="error-message">{{ $errors->first('address_1') }}</div>
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
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Profile Image</label>
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
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Aadhar Image</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" name="aadhar_image" id="aadhar_image">
                                    <div class="error-message">{{ $errors->first('aadhar_image') }}</div>
                                    @if($user->aadhar_image)
                                        <img src="{{ URL::asset('public/admin/uploads/seller/') }}/<?=$user->aadhar_image?>" height="50" width="50">
                                    @endif
                                </div>
                            </div>
                        </div>
						<div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Driving Licence Image</label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" name="driving_licence_image" id="driving_licence_image">
                                    <div class="error-message">{{ $errors->first('driving_licence_image') }}</div>
                                    @if($user->driving_licence_image)
                                        <img src="{{ URL::asset('public/admin/uploads/seller/') }}/<?=$user->driving_licence_image?>" height="50" width="50">
                                    @endif
                                </div>
                            </div>
                        </div>

								 
								
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
@stop