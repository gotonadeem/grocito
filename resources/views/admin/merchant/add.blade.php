@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <div class="row">
						<h3 class="pull-left"><a href="javascript:void(0)" class="btn btn-info button_margin_left">Delivry Boy's Information</a></h3>
						<div class="text-right"><a href="{{ URL::to('admin/merchant/merchant-list') }}" class="btn btn-primary waves-effect waves-light button_margin_right">Back</a>
                        <div class="ibox-tools">
                        </div>
						</div>
                        </div>
				    {{ Form::open(array('url' =>'admin/delivery-boy/store-delivery-boy','enctype'=>'multipart/form-data','class'=>'form-horizontal','autocomplete'=>'off','id'=>'add_seller','name'=>'add_seller')) }}
                                 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">First Name<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
                                         <input type="text" autocomplete="off" class="form-control" name="f_name" id="f_name" value="{!! old('f_name') !!}" placeholder="First Name">
                                         <div class="error-message">{{ $errors->first('f_name') }}</div>
                                     </div>
                                 </div>
								 </div>    
								 
								 <div class="col-sm-6">
								 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Last Name<span class="text-danger">*</span></label>
                                     <div class="col-sm-8">
                                         <input type="text" autocomplete="off" class="form-control" name="l_name" id="l_name" value="{!! old('l_name') !!}" placeholder="Last Name">
                                         <div class="error-message">{{ $errors->first('l_name') }}</div>
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
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">City</label>
                                     <div class="col-sm-8">
                                         <select class="form-control" name="city_id" onchange="get_warehouse(this.value)" id="city_list">
                                             <option value="">Select City</option>
											  @foreach($city_list as $vs)
											      <option value="{{$vs->id}}">{{$vs->name}}</option>
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
                                         </select>
                                         <div class="error-message">{{ $errors->first('warehouse_id') }}</div>
                                     </div>
                                 </div>
								 </div>
								  
								 
								 		 <div class="col-sm-6">
									  <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Address</label>
                                     <div class="col-sm-8">
                                         <textarea id="address_1" autocomplete="off" class="form-control" placeholder="Address1" name="address_1"> {!! old('address_1') !!} 
                                         </textarea>
                                         <div class="error-message">{{ $errors->first('address_1') }}</div>
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
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Profile Image</label>
                                     <div class="col-sm-8">
                                            <input type="file" class="form-control" name="profile_image" id="profile_image"  placeholder="Profile Image" value="{!! old('profile_image') !!}">
                                             <div class="error-message">{{ $errors->first('profile_image') }}</div>
                                     </div>
                                 </div>
                                 </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Aadhar Card<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" name="aadhar_image" id="aadhar_image"  placeholder="Aadhar Image" value="{!! old('aadhar_image') !!}">
                                    <div class="error-message">{{ $errors->first('aadhar_image') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Driving Licence<span class="text-danger">*</span></label>
                                <div class="col-sm-8">
                                    <input type="file" class="form-control" name="driving_licence_image" id="driving_licence_image"  placeholder="driving Image" value="{!! old('driving_licence_image') !!}">
                                    <div class="error-message">{{ $errors->first('driving_licence_image') }}</div>
                                </div>
                            </div>
                        </div>

                                 
                                 
								  
								  <div class="col-sm-12">	 
								
                                     <div class="form-group row">
                                         <div class="col-sm-12">
                                             <button type="submit" name="add_user"  class="btn pull-right btn-primary waves-effect waves-light pull-right submit_margin">
                                                 Submit
                                             </button>
                                            
                                         </div>
                                     </div>
                                 {{ Form::close() }}
                                 </div>
            </div>
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
