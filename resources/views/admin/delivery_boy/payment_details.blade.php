@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Order List</h5>
					     <div class="ibox-tools">
						   <div class="text-right"><a href="{{ URL::to('admin/delivery-boy-payment/view/'.$_GET['user_id']) }}" class="btn btn-info">Back</a>
                            
                        </div>
                        </div>
                    </div>
					  <div class="tabs-view">
			 
                <div id="home" class="tab-pane fade in active">
				 <div class="ibox-content">
                        <div class="table-responsive">
                                <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sno #</th>
										<th>order Id</th>
										<th>Total Distance</th>
										<th>Bonus</th>
										<th>Ride Type</th>
										<th>Payment Mode</th>
										<th>Seller Name</th>
										<th>Warehouse</th> 
										<th>Action</th>
                                    </tr>
                                    </thead>
									<tbody>
									@foreach($data as $ks=>$vs)
									   <tr>
                                        <td>{{$ks+1}}</td>
										<td>{{$vs->order->order_id}}</td>
										<td>{{$vs->distance}}</td>
										<td>{{$vs->bonus}}</td>
										<td>{{$vs->type}}</td>
										<td>{{$vs->payment_mode}}</td>
										<td>{{$vs->seller->username}}</td>
										<td>{{$vs->warehouse->name}}</td> 
										<td><a href="{{URL::to('admin/order/view-order/'.$vs->order_id)}}">View Details</a></td>
                                    </tr>
									@endforeach
									</tbody>
                            </table>
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
    <script>
		$('.input-sm').attr('placeholder',"username,email,mobile");
	</script>
@stop
