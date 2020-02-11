@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Payment History <b>{{$shipped_date}}</span> </b></h5>
                        <div class="ibox-tools">
                          <ul class="tab-top list-inline">
                                   <li> <a href="{{URL::to('admin/payment/view-payment/'.$user_id)}}" class="btn btn-primary pull-right">BACK</a></li>
                            </ul>
						
                 
                    </div>
                </div>
                <div class="ibox-content">
			          <div class="paymenttypes-container">
                <div class="composite-container">
                    <div class="row row-container">
                        <div class="row">
						<div class="col-md-6 title-container">
						<div class="col-md-8"><h4>Payment Amount :</h4> </div>
						
						<div class="col-md-4"><span class="total-amount"><h4>₹ {{$amount}} </h4></span>
						 </div>
						</div>
						</div>

						{{--<div class="row">
						<div class="col-md-6 title-container">
						<div class="col-md-8"><h4>RMA(Return,Exchange,Cancellation) Charge :</h4> </div>
						
						<div class="col-md-4"><span class="total-amount error"><h4>-₹ {{$penalty_amount}}</h4></span>
						 </div>
						</div>
						</div>--}}
						
					{{--	<div class="row">
						<div class="col-md-6 title-container">
						<div class="col-md-8"><h4>Product Sponsor Charge :</h4> </div>
						
						<div class="col-md-4"><span class="total-amount error"><h4>-₹ {{$sponsor_amount}}</h4></span>
						 </div>
						</div>
						</div>--}}
						{{--
						<div class="row">
						<div class="col-md-6 title-container">
						<div class="col-md-8"><h4>Net Amount :</h4> </div>
						
						<div class="col-md-4"><span class="total-amount"><h4>₹ {{$amount-$penalty_amount-$sponsor_amount}}</h4></span>
						 </div>
						</div>
						</div>--}}
						
                    </div>
					
                    <div class="listview-container">
                         <ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#home">Delivered Order</a></li>
							<li><a data-toggle="tab" href="#menu1">Return</a></li>
							<li><a data-toggle="tab" href="#menu2">Exchange</a></li>
							<li><a data-toggle="tab" href="#menu3">Cancelled</a></li>
					    </ul>
						 <div class="tab-content">
                         <div id="home" class="tab-pane fade in active">
						 <h3>Delivered Orders</h3>
							<div class="payment-grid">
								<div class="table-responsive">
									<table class="payment-grid-view table table-striped">
										<thead>
										<tr style="background-color: rgb(234, 234, 234);">
											<th>No</th>
											<th>Order Num</th>
											<th>Delivered Date</th>
											<th>Status</th>
											<th>Amount</th>

										</tr>
										</thead>
										<tbody>
										<?PHP
										$i = 1;
										?>
										@foreach($order_list as $vs)
										<tr>
											<td>{{$i}}</td>
											<td>{{$vs->order->order_id}}</td>
											<td>
												{{date('d-m-Y',strtotime($vs->delivery_date))}}</td>
											<td class="text-capitalize">{{$vs->status}}</td>
											<td>₹ {{$vs->price * $vs->qty}}</td>
										</tr>
										<?PHP
										$i++;
										?>
										@endforeach

									 </tbody>
									</table>
								</div>
							</div>
                          </div>
						    <div id="menu1" class="tab-pane fade">
							  <h3>Return Orders</h3>
							    <div class="payment-grid">
								<div class="table-responsive">
									<table class="payment-grid-view table table-striped">
										<thead>
										<tr style="background-color: rgb(234, 234, 234);">
											<th>No</th>
											<th>Order Num</th>
											<th>Return Date</th>
											<th>Status</th>
											

										</tr>
										</thead>
										<tbody>
										<?PHP
										$i = 1;
										?>
										@foreach($return_data as $vs)
										<tr>
											<td>{{$i}}</td>
											<td>{{$vs->order->order_id}}</td>
											<td>
												{{date('d-m-Y',strtotime($vs->approved_date))}}</td>
											<td class="text-capitalize">Return</td>
											
										</tr>
										<?PHP
										$i++;
										?>
										@endforeach

									 </tbody>
									</table>
								</div>
							</div>
							</div>
							
							 <div id="menu2" class="tab-pane fade">
							  <h3>Exchange Orders</h3>
							    <div class="payment-grid">
								<div class="table-responsive">
									<table class="payment-grid-view table table-striped">
										<thead>
										<tr style="background-color: rgb(234, 234, 234);">
											<th>No</th>
											<th>Order Num</th>
											<th>Return Date</th>
											<th>Status</th>
											<th>Shipping Charge</th>
										</tr>
										</thead>
										<tbody>
										<?PHP
										$i = 1;
										?>
										@foreach($exchange_data as $vs)
										<tr>
											<td>{{$i}}</td>
											<td>{{$vs->order->order_id}}</td>
											<td>
												{{date('d-m-Y',strtotime($vs->approved_date))}}</td>
											<td class="text-capitalize">Exchange</td>
											<td class='error'>- ₹ {{$exchangePenalty}}</td>
										</tr>
										<?PHP
										$i++;
										?>
										@endforeach

									 </tbody>
									</table>
								</div>
							</div>
							</div> 
							<div id="menu3" class="tab-pane fade">
							  <h3>Cancelled Orders</h3>
							    <div class="payment-grid">
								<div class="table-responsive">
									<table class="payment-grid-view table table-striped">
										<thead>
										<tr style="background-color: rgb(234, 234, 234);">
											<th>No</th>
											<th>Order Num</th>
											<th>Cancelled Date</th>
											<th>Status</th>

										</tr>
										</thead>
										<tbody>
										<?PHP
										$i = 1
										?>
										@foreach($cancelled_data as $vs)
										<tr>
											<td>{{$i}}</td>
											<td><?=$vs->order_id?></td>
											<td>
												{{date('d-m-Y',strtotime($vs->updated_at))}}</td>
											<td class="text-capitalize">Cancelled</td>
											

											<td>
											<?PHP  if($vs->order_id!=""): ?>
											 <?PHP endif; ?>
											</td>
										</tr>
										<?PHP
										$i++;
										?>
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
        </div>
    </div>
</div>
@include('admin.includes.admin_right_sidebar')
@include('admin.payment.deposite')
@include('admin.payment.withdraw')

  <div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Payment To Seller</h4>
      </div>
      <div class="modal-body">
	   <div id="msg"></div>
       <form id="mail_form" name="mail_form">
	       <div class="form-group">
		      <label for='email'>Transaction Id</label>
			  <input type="text" name="transaction_id" class="form-control" placeholder="Transaction Id" id="transaction_id">
		   </div> 
		   
		   <div class="form-group">
		      <label for='email'>Bank Name</label>
			  <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" id="bank_name">
		   </div>
		   
		   
	   </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="send_payment" class="btn btn-primary">Send</button>
      </div>
    </div>

  </div>
</div>


<div id="myModal2" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Payment Details</h4>
      </div>
      <div class="modal-body">
	   <div id="data_details"></div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- Mainly scripts -->
<script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/dataTables/datatables.min.js') }}"></script>
<!-- Custom and plugin javascript -->
<script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>
<script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<!-- Page-Level Scripts -->
<script>
    ASSET_URL = '{{ URL::asset('public') }}/';
    BASE_URL='{{ URL::to('/') }}';
	
</script>
<script>
		//$('.input-sm').attr('placeholder',"username,order id,mobile");
		$('.input-sm').css("font-size","13px");
		
</script>
@stop
