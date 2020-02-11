@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Delivery Boy Payment View</h5>
					     <div class="ibox-tools">
                        </div>
                    </div>
					  <div class="tabs-view">
						  <ul class="nav nav-pills">
							<li class="active"><a data-toggle="pill" href="#home">Unpaid</a></li>
							<li><a data-toggle="pill" href="#menu1">Paid</a></li>
						  </ul>
			  <div class="tab-content">
                <div id="home" class="tab-pane fade in active">
				 <div class="ibox-content">
                        <div class="table-responsive">
                                <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sno #</th>
										<th>From Date</th>
										<th>To Date</th>
										<th>Total Orders</th>
										<th>Total Distance</th>
										<th>Orders Amount</th>
										<th>Amount(Per Km)</th>
										<th>Total Working(Days)</th>
										<th>COD Amount</th> 
										<th>Bonus</th>							
										<th>Payable Amount</th>							
										<th>Action</th>
                                    </tr>
                                    </thead>
									 <tbody>
									    @foreach($data as $ks=>$vs)
										  @if(Helper::check_delivery_boy_payment($vs->id,$userdata->id)==0)
										<?PHP
										$rdata=Helper::get_rider_total($vs->from_date,$vs->to_date,Request::segment(4));
										$bonus=0;
										?>
										<tr>
										<th>{{$ks+1}}</th>
										<th>{{$vs->from_date}}</th>
										<th>{{$vs->to_date}}</th>
										<th><?=$total_count=((count((array)$rdata)>0)?$rdata->total_count:0)?></th>
										<th><?=$total_distance=((count((array)$rdata)>0)?$rdata->total_distance:0)?></th>
										<th><?=$total=(count((array)$rdata)>0)?$rdata->grand_total:0?></th>
										<th><?=$amount_per_km=(count((array)$rdata)>0)?$rdata->amount_per_km:0?></th>
										<th><?=$total_days=Helper::get_total_days($vs->from_date,$vs->to_date,Request::segment(4))?></th>
										<th><?=$cod_total=Helper::get_cod_total($vs->from_date,$vs->to_date,Request::segment(4))?></th>
										<th><?=$bonus=(count((array)$rdata)>0)?$rdata->bonus:0?></th>
										<th><?=$payable=$total+$bonus?></th>
										<th>
										<a href="javascript:void(0)" onclick="pay_now(<?=$vs->id?>,<?=$total_count?>,<?=$total_distance?>,<?=$total?>,<?=$cod_total?>,<?=($bonus>0)?$bonus:0?>,<?=$payable?>,<?=Request::segment(4)?>,<?=$amount_per_km?>)">Pay Now</a>
										| <a href="<?=URL::to('admin/payment-details')?>?from_date=<?=$vs->from_date?>&to_date=<?=$vs->to_date?>&id=<?=$vs->id?>&user_id=<?=$userdata->id?>">View Details</a>
										</th>
										
										</tr>
										 @endif
										 
										@endforeach
                                    </tbody>
                            </table>
                        </div>
                    </div>
		         </div>
				 
				 <div id="menu1" class="tab-pane ">
				 <div class="ibox-content">
                        <div class="table-responsive">
                                <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sno #</th>
										<th>From Date</th>
										<th>To Date</th>
										<th>Total Orders</th>
										<th>Total Distance</th>
										<th>Orders Amount</th>
										<th>Amount(Per Km)</th>
										<th>Total Working(Days)</th>
										<th>COD Amount</th> 
										<th>Bonus</th>							
										<th>Payable Amount</th>							
										<th>Action</th>
                                    </tr>
                                    </thead>
									 <tbody>
									    @foreach($paid as $ks=>$vs)
										<tr>
										<th>{{$ks+1}}</th>
										<th>{{$vs->payment_slot->from_date}}</th>
										<th>{{$vs->payment_slot->to_date}}</th>
										<th>{{$vs->order_count}}</th>
										<th>{{$vs->distance}}</th>
										<th>{{$vs->amount}}</th>
										<th>{{$vs->distance_wise_amount}}</th>
										<th>{{$vs->no_of_days}}</th>
										<th>{{$vs->cod}}</th>
										<th>{{$vs->bonus}}</th>
										<th>{{$vs->payment_amount}}</th>
										<th><a href="<?=URL::to('admin/payment-details')?>?from_date=<?=$vs->payment_slot->from_date?>&to_date=<?=$vs->payment_slot->to_date?>&id=<?=$vs->payment_slot_id?>&user_id=<?=$vs->delivery_boy_id?>">View Details</a></th>
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
    </div>
	
	<div id="myModal" class="modal" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
	<form id="payment" name="payment" method="post" enctype="multipart/form-data">
    <div class="modal-content">
      <div class="modal-header">
		<h4 class="modal-title">Pay Now</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        
      </div>
      <div class="modal-body">
                      <div class="form-group">
                        <label for="firstname">Transaction Id</label>
                        <input id="transaction_id" class="form-control" name="transaction_id" required="" type="text">
                      </div>
					  <div class="form-group">
                        <label for="firstname">Description</label>
                         <textarea class="form-control" name="description" id="description"></textarea>
                      </div>
					  <div class="form-group">
                        <label for="firstname">Slip</label>
                        <input id="slip" class="form-control" name="slip" required="" type="file">
                      </div>
      </div>
      <div class="modal-footer">
        <button type="submit" name="submit" id="submit" class="btn btn-primary" >Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
	</form>

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
function pay_now(id,total_count,total_distance,total,cod_total,bonus,payable,rider_id,amount_per_km)
{
	localStorage.clear();
	localStorage.setItem("id",id);
	localStorage.setItem("total_count",total_count);
	localStorage.setItem("total_distance",total_distance);
	localStorage.setItem("total",total);
	localStorage.setItem("cod_total",cod_total);
	localStorage.setItem("bonus",bonus);
	localStorage.setItem("payable",payable);
	localStorage.setItem("rider_id",rider_id);
	localStorage.setItem("amount_per_km",amount_per_km);
	$("#myModal").fadeIn();
	$("#myModal").modal("show");
	
}

$('#payment').submit(function(event) {
    event.preventDefault();
    var formData = new FormData($(this)[0]);
	 formData.append('id',localStorage.getItem('id'));
	 formData.append('total_count',localStorage.getItem('total_count'));
	 formData.append('total_distance',localStorage.getItem('total_distance'));
	 formData.append('total',localStorage.getItem('total'));
	 formData.append('cod_total',localStorage.getItem('cod_total'));
	 formData.append('bonus',localStorage.getItem('bonus'));
	 formData.append('payable',localStorage.getItem('payable'));
	 formData.append('rider_id',localStorage.getItem('rider_id'));
	 formData.append('amount_per_km',localStorage.getItem('amount_per_km'));
	   $.ajax({
		headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
        url: BASE_URL+'/admin/rider/make-rider-payment',
        type: 'POST',              
        data: formData,
		cache: false,
        contentType : false,
        processData : false,
        success: function(result)
        {
			result= JSON.parse(result);
            if(result.status)
			{
				location.reload();
			}
        },
        error: function(data)
        {
            console.log(data);
        }
    });
});


        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
    </script>
    <script>
		$('.input-sm').attr('placeholder',"username,email,mobile");
	</script>
@stop
