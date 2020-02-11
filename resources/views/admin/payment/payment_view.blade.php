@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Payment History ({{$data->user_kyc->f_name." ".$data->user_kyc->l_name}})</h5>
                        <div class="ibox-tools">
                          <ul class="tab-top list-inline">
                                
                                <!--<li> <a href="javascript:void(0)" onclick="get_popup()" class="btn btn-primary pull-right">Settlement</a></li> -->
								<li> <a href="{{URL::to('admin/payment/payment-report')}}" class="btn btn-primary pull-right">BACK</a></li>
                            </ul>
						
                 
                    </div>
                </div>
                <div class="ibox-content">
                                     
				   <div class="table-responsive">
                        <table id="transaction-table" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>Sr.</th>
								<th>Date</th>
                                <th>Total Amount</th>
                                <th>Net Amount</th>
                                <th>Commission Amount</th>
                                <th>GST (18 %)</th>
                                <th>Total Commission</th>
                                <th>TCS</th>
								{{--<th>RMA Charges</th>--}}
								<th>Payable Amount</th>

                                <th>Action</th>
                            </tr>
                            </thead>
							<tbody>
							 <?PHP
							 $i=1;
							$adminCommission = 0;
							 $totalGstAmount = 0;
							 $pp_amount=0;
							 $total_payable_sum1=0;
							 $total_paid_sum1 = 0;
							 $total_payable_amount=0;
							 foreach($response as $ks=>$vs):
							 $returnAmount = Helper::getSellerReturnPenaltyAmount($data->id,$vs->shippedDate);
							 $exchangePenalty = Helper::getSellerExchangePenaltyAmount($data->id,$vs->shippedDate);
						     $adminGstAmount = round(($vs->total_admin_commission * 18)/100,2);
							 $totalAdminCommission = 	$vs->total_admin_commission + $adminGstAmount;
							 $tcsTax = 	round(($vs->net_amount * 1)/100,2);
							 $total_payable_amount = ($vs->total - $totalAdminCommission - $tcsTax);
							 $status=Helper::check_payment(Request::segment(4),$vs->shippedDate);

							 ?>
							<tr>

								<td>{{$i}}</td>
								<td>{{$vs->shippedDate}}</td>
								<td>{{$vs->total}}</td>
								<td>{{$vs->net_amount}}</td>
								<td>{{$vs->total_admin_commission}}</td>
								<td>{{$adminGstAmount}}</td>
								<td>{{$totalAdminCommission}}</td>
								<td>{{$tcsTax}}</td>
{{--								<td>{{$returnAmount + $exchangePenalty}}</td>--}}
								<td>{{$total_payable_amount}}</td>

								<td>
									<?php
									if($status){
									?>
									<a href="javascript:void(0)" style="color:green">Paid</a>   |
									<a href="{{URL::to('admin/orders-between-dates')}}/{{$vs->shippedDate}}/<?=Request::segment(4)?>" class="view">Orders</a> |
									<a href="{{URL::to('admin/payment/payment-details')}}/<?=Request::segment(4)?>?amount=<?=$total_payable_amount?>&shipped_date={{$vs->shippedDate}}&commission=<?=$totalAdminCommission;?>"><i class="fa fa-history"></i></a>
									<?PHP
									}
									else
									{ ?>
									<a  href="javascript:void(0)" class="pay_now" style="color:red" id="<?=$vs->shippedDate;?>,<?=$total_payable_amount?>,<?=Request::segment(4);?>,<?=$totalAdminCommission;?>,<?=$tcsTax;?>">Pay</a> |
									<a href="{{URL::to('admin/orders-between-dates')}}/{{$vs->shippedDate}}/<?=Request::segment(4)?>" class="view">Orders</a> |
									<a href="{{URL::to('admin/payment/payment-details')}}/<?=Request::segment(4)?>?amount=<?=$total_payable_amount?>&shipped_date={{$vs->shippedDate}}&commission=<?=$totalAdminCommission?>"><i class="fa fa-history"></i></a>

									<?PHP
									}
									?>
								</td>


							</tr>
							 
						<?php

						$adminCommission +=$totalAdminCommission;
						$total_payable_sum1 += $total_payable_amount;
						$i++;
						endforeach; ?>
							</tbody>

                            <tfoot>
							<tr>
							   <th></th>
                                <th></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th><?=$adminCommission;?></th>

								<th></th>
                                <th style="color:green"><?=round($total_payable_sum1,2);?></th>

                                
								<th colspan="1">
                                </th>
							</tr>
                            </tfoot>   							
                        </table>
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
			   <span class="error transaction_error" style="display: none">This field is required</span>
		   </div> 
		   
		   <div class="form-group">
		      <label for='email'>Bank Name</label>
			  <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" id="bank_name">
			   <span class="error bank_error" style="display: none;">This field is required</span>
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
        <h4 class="modal-title">Payment Settlement</h4>
      </div>
      <div class="modal-body">
	   <div id="data_details"></div>
            <form id="mail_form" name="mail_form">
			<input type="hidden" id="start_date" value="">
			<input type="hidden" id="end_date" value="">
			<input type="hidden" id="user_id" value="<?=Request::segment(4)?>">
			<input type="hidden" id="week_number" value="<?=Request::segment(4)?>">
	       <div class="form-group">
		      <label for='email'>Transaction Id</label>
			  <input type="text" name="transaction_id" class="form-control" placeholder="Transaction Id" id="s_transaction_id">
		   </div> 
		   
		   <div class="form-group">
		      <label for='email'>Bank Name</label>
			  <input type="text" name="bank_name" class="form-control" placeholder="Bank Name" id="s_bank_name">
		   </div> 
		   <div class="form-group">
		      <label for='email'>Amount</label>
			  <input type="text" name="amount" class="form-control" placeholder="Amount" id="s_amount">
		   </div>
	   </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="submit_payment()">Submit</button>
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
<script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/payment_view.js') }}"></script>
<script>
		//$('.input-sm').attr('placeholder',"username,order id,mobile");
		$('.input-sm').css("font-size","13px");
		
</script>
<script type="text/javascript">
           
		   function get_popup()
		   {
			   $("#start_date").val($(".p_start_date").last().text());
			   $("#end_date").val($(".p_end_date").last().text());
			   $("#week_number").val($(".p_week_number").last().text());
			   $("#myModal2").modal('show');
		   }
		   
		  
		   
		   $(document).ready(function () {
			
               var daysToAdd = 4;
               $("#FromDate").datepicker({
                   onSelect: function (selected) {
                       var dtMax = new Date(selected);
                       dtMax.setDate(dtMax.getDate() + daysToAdd);
                       var dd = dtMax.getDate();
                       var mm = dtMax.getMonth() + 1;
                       var y = dtMax.getFullYear();
                       var dtFormatted = y + '-'+ mm + '/'+ dd;
                       // $("#ToDate").datepicker("option", "minDate", dtFormatted);
                   }
               });

               $("#ToDate").datepicker({
                   onSelect: function (selected) {
                       var dtMax = new Date(selected);
                       dtMax.setDate(dtMax.getDate() - daysToAdd);
                       var dd = dtMax.getDate();
                       var mm = dtMax.getMonth() + 1;
                       var y = dtMax.getFullYear();
                       var dtFormatted = y + '/'+ mm + '/'+ dd;
                       // $("#FromDate").datepicker("option", "maxDate", dtFormatted)
                   }
               });
           });
</script>
<style>
th,td{ white-space: nowrap; }
</style>
@stop
