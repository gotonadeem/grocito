@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title display">
						<div class="row">
						<h3 class="pull-left"><a href="javascript:void(0)" class="btn btn-info button_margin_left">Seller's Personal Information</a></h3>		
						</div>
                        <div class="text-right"><a href="{{ URL::previous() }}" class="btn btn-info">Back</a> 
                        </div>
                           <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">First Name</label>
                                     <div class="col-sm-8">
                                        {{$user->f_name}}
                                     </div>
                                 </div>
                             </div>
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Last Name</label>
                                     <div class="col-sm-8">
                                        {{$user->l_name}}
                                     </div>
                                 </div>
                             </div> 
							 
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Username</label>
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
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Alternate Mobile No</label>
                                     <div class="col-sm-8">
                                        {{$user->alternate_mobile_no}}
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
                                        {{$user->country->name}}
                                     </div>
                                 </div>
                             </div>  

							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">State</label>
                                     <div class="col-sm-8">
                                        {{$user->state->name}}
                                     </div>
                                 </div>
                             </div> 	 
							 
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">City</label>
                                     <div class="col-sm-8">
                                        {{$user->city->name}}
                                     </div>
                                 </div>
                             </div> 
							 
							 <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Profile Image</label>
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
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Cancel Cheque</label>
                                     <div class="col-sm-8">
									  @if($user->cancel_cheque)
									   <a href="{{URL::asset('public/admin/uploads/seller/'.$user->cancel_cheque)}}"><img src="{{URL::asset('public/admin/uploads/seller/'.$user->cancel_cheque)}}" style="height:100px;width:100px;"></a>
										@endif
                                     </div>
                                 </div>
                             </div>  <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">CIN No</label>
                                     <div class="col-sm-8">
                                        {{$user->cin_number}}
                                     </div>
                                 </div>
                             </div> 
							  <div class="col-sm-6">
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">CIN Copy</label>
                                     <div class="col-sm-8">
									  @if($user->cin_image)
									   <a href="{{URL::asset('public/admin/uploads/seller/'.$user->cin_image)}}"><img src="{{URL::asset('public/admin/uploads/seller/'.$user->cin_image)}}" style="height:100px;width:100px;"></a>
										@endif
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
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">TAN No</label>
                                     <div class="col-sm-8">
                                        {{$user->tan_number}}
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
        </div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    @include('admin.includes.admin_footer_inner')
    <!-- Page-Level Scripts -->
@stop
