@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title display">
					   <div class="row">
						 <div class="col-md-6"><h4>View Seller Information</h4></div>
					   </div>
						<div class="row">
						<h3 class="pull-left"><a href="javascript:void(0)" class="btn btn-info button_margin_left">Seller's Business Information</a></h3>		
						<div class="text-right"><a href="{{ URL::previous() }}" class="btn btn-primary waves-effect waves-light button_margin_right">Back</a> 
                        </div>
						</div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Full Name</label>
                                <div class="col-sm-8">
                                    {{$user->business_name}}
                                </div>
                            </div>
                        </div>
                           <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Business Name</label>
                                     <div class="col-sm-8">
                                        {{$user->user->username}}
                                     </div>
                                 </div>
                             </div>

                             <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Email Address</label>
                                     <div class="col-sm-8">
                                        {{$user->user->email}}
                                     </div>
                                 </div>
                             </div>
                              
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Mobile</label>
                                     <div class="col-sm-8">
                                        {{$user->user->mobile}}
                                     </div>
                                 </div>
                             </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Food License No</label>
                                <div class="col-sm-8">
                                    {{$user->food_license_no}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Business Reg. No.</label>
                                <div class="col-sm-8">
                                    {{$user->business_reg_no}}
                                </div>
                            </div>
                        </div>
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Pickup Address</label>
                                     <div class="col-sm-8">
                                        {{$user->address_1}}
                                     </div>
                                 </div>
                             </div>
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Address</label>
                                     <div class="col-sm-8">
                                        {{$user->address_2}}
                                     </div>
                                 </div>
                             </div> 
							 
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Pincode</label>
                                     <div class="col-sm-8">
                                        {{$user->pincode}}
                                     </div>
                                 </div>
                             </div>
							 
                              <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Country</label>
                                     <div class="col-sm-8">
									  @if(!is_null($user->country))
										   
                                        {{$user->country->name}}
										
									   @endif 
                                       
                                     </div>
                                 </div>
                             </div>  

							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">State</label>
                                     <div class="col-sm-8">
									   @if(!is_null($user->state))
										   
                                        {{$user->state->name}}
										
									   @endif 
                                     </div>
                                 </div>
                             </div> 	 
							 
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">City</label>
                                     <div class="col-sm-8">
                                       @if(!is_null($user->city))
										   
                                        {{$user->city->name}}
										
									   @endif 
                                     </div>
                                 </div>
                             </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Delivery Pincode</label>
                                <div class="col-sm-8">
                                    @if(!is_null($user->delivery_pincode))
                                    {{$user->delivery_pincode}}
                                    @endif
                                </div>
                            </div>
                        </div>
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Logo</label>
                                     <div class="col-sm-8">
									  @if($user->profile_image)
									   <a href="{{URL::asset('public/admin/uploads/seller/'.$user->profile_image)}}"><img src="{{URL::asset('public/admin/uploads/seller/'.$user->profile_image)}}" style="height:100px;width:100px;"></a>
										@endif
                                     </div>
                                 </div>
                             </div> 
							 
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Seller Image</label>
                                     <div class="col-sm-8">
                                       @if($user->seller_image)
									   <a href="{{URL::asset('public/admin/uploads/seller/'.$user->seller_image)}}"><img src="{{URL::asset('public/admin/uploads/seller/'.$user->seller_image)}}" style="height:100px;width:100px;"></a>

										@endif
                                     </div>
                                 </div>
                             </div> 
							 
					  <div class="col-sm-12">
					   <a href="javascript:void(0)" class="btn btn-info">KYC Details</a><br><br>
					   </div>
								   
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Account No</label>
                                     <div class="col-sm-8">
                                        {{$user->account_number}}
                                     </div>
                                 </div>
                             </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Account Holder Name</label>
                                <div class="col-sm-8">
                                    {{$user->account_holder_name}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Bank Name</label>
                                <div class="col-sm-8">
                                    {{$user->bank_name}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">IFSC Code</label>
                                <div class="col-sm-8">
                                    {{$user->ifsc_code}}
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">PAN No</label>
                                <div class="col-sm-8">
                                    {{$user->pan_number}}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Cancel Cheque</label>
                                     <div class="col-sm-8">
									  @if($user->cancel_cheque)
									   <a href="{{URL::asset('public/admin/uploads/seller/'.$user->cancel_cheque)}}"><img src="{{URL::asset('public/admin/uploads/seller/'.$user->cancel_cheque)}}" style="height:100px;width:100px;"></a>
										@endif
                                     </div>
                                 </div>
                             </div>  

                              
                            <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">PAN Copy</label>
                                     <div class="col-sm-8">
									   @if($user->pan_image)
									   <a href="{{URL::asset('public/admin/uploads/seller/'.$user->pan_image)}}"><img src="{{URL::asset('public/admin/uploads/seller/'.$user->pan_image)}}" style="height:100px;width:100px;"></a>
										@endif
                                     </div>
                                 </div>
                             </div>
							
							 
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">GST No</label>
                                     <div class="col-sm-8">
                                        {{$user->gst_number}}
                                     </div>
                                 </div>
                             </div> 
                </div>
                </div>
                </div>
            </div>
			<div class="row">
			<h4>Seller's Products</h4>
			<div class="ibox-content">
                            <div class="table-responsive">
                                <table id="product-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Category</th>
                                        <th>Product Name</th>
                                        

                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
			</div>
        </div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    @include('admin.includes.admin_footer_inner')
    <!-- Page-Level Scripts -->
	<script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
		var seller_id="{{$id}}";
    </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/product.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"username,email");
	</script>
@stop
