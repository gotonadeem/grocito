@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title display">
					   <div class="row">
						 <div class="col-md-6"><h4>View Delivery Boy  Information</h4></div>
					   </div>
						<div class="row">
						<h3 class="pull-left"><a href="javascript:void(0)" class="btn btn-info button_margin_left">Delivery Boy's Information</a></h3>		
						<div class="text-right"><a href="{{ URL::previous() }}" class="btn btn-primary waves-effect waves-light button_margin_right">Back</a> 
                        </div>
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
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Code</label>
                                     <div class="col-sm-8">
                                        {{$user->user->unique_code}}
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
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Address</label>
                                     <div class="col-sm-8">
                                        {{$user->address_1}}
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
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Aadhar Image</label>
                                <div class="col-sm-8">
                                    @if($user->aadhar_image)
                                        <a href="{{URL::asset('public/admin/uploads/seller/'.$user->aadhar_image)}}"><img src="{{URL::asset('public/admin/uploads/seller/'.$user->aadhar_image)}}" style="height:100px;width:100px;"></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-4 form-control-label">Driving Licence Image</label>
                                <div class="col-sm-8">
                                    @if($user->driving_licence_image)
                                        <a href="{{URL::asset('public/admin/uploads/seller/'.$user->driving_licence_image)}}"><img src="{{URL::asset('public/admin/uploads/seller/'.$user->driving_licence_image)}}" style="height:100px;width:100px;"></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="ibox-content">

                            <div class="table-responsive">
                                <table id="order-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Order Id</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>P-mode</th>
                                        <th>Date</th>
                                        {{--<th>Action</th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $snn = 1; ?>
                                    @foreach($deliveryBoyOrder as $order)
                                        <?php
                                        $amount = Helper::get_order_meta_total($order->order->id);
                                        ?>
                                        <tr>
                                            <td>{{$snn}}</td>
                                            <td>{{$order->order->order_id}}</td>
                                            <td>{{$amount}}</td>
                                            <td>{{str_replace('_',' ',$order->type)}}</td>
                                            <td>{{$order->payment_mode}}</td>
                                            <td>{{date('d-m-Y',strtotime($order->created_at))}}</td>
                                        </tr>
                                        <?php $snn++;?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="ibox-content">
                            <b>Login History </b>
                            <div class="table-responsive">
                                <table id="table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Login Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $sn = 1; ?>
                                    @foreach($riderHistory as $history)
                                    <tr>
                                        <td>{{$sn}}</td>
                                        <td>{{date('d-m-Y',strtotime($history->loginDate))}}</td>
                                        <td>{{$history->login_time}}</td>
                                        <td>{{$history->is_login == 1?'Login':'Logout'}}</td>
                                    </tr>
                                    <?php $sn++;?>
                                    @endforeach
                                    </tbody>
                                </table>
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
	<script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
		var d_b_id="{{$id}}";
    </script>
	<script>
		$('.input-sm').attr('placeholder',"username,email");
	</script>
	<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/d_boy_order.js') }}"></script>
@stop
