@extends('seller.layouts.seller')
@section('content')
	<div id="rightSidenav" class="right_side_bar right_side_bar_new">
		<div class="payment-content payment-view">
			<div>
				<div class="header-section clearfix">
					<div class="breadcrumbs-container"><a class="nav-content" href="{{'/seller/payment'}}">Payments</a><span class="icon-separator fa fa-angle-right"></span><a class="nav-content disabled">Pending Payment</a></div>
					<div class="header-container row">

						<div class="header-title col-sm-4 text-capitalize">Pending Payment</div>

						<div class="header-desc col-sm-8 padding-0 text-right row">

							<div class="payments-info-container download-csv-container">

								<!--<button class="download-csv-button blue-button box-shadow">Download Payment Excel</button>-->

							</div>


						</div>

					</div>

				</div>

				<div class="paymenttypes-container">

					<div class="composite-container">



						<div class="listview-container">
							<div class="payment-grid">
								<div class="table-responsive">
									<table class="payment-grid-view table table-striped">
										<thead>
										<tr style="background-color: rgb(234, 234, 234);">

											<th>No</th>
											<th>Date</th>
											<th>Total Amount</th>
											<th>Net Amount</th>
											<th>Admin Commission</th>
											<th>TCS</th>
											<th>Payable Amount</th>
											<th>View Orders</th>
										</tr>
										</thead>
										<tbody>

										<?php
												$sn=1;

											foreach ($pendindOrderDate as $p_date){
										$totalPendingAmount=0;
										$totalPendingNetAmount=0;
										$totalAdminCmsn=0;
										$tcsTax=0;
												$orderData = Helper::getPendingOrderByDateNew($p_date->pending_order_date,Auth::user()->id);
												foreach ($orderData as $o_data){
													$totalPendingAmount += $o_data->total;
													$totalPendingNetAmount += $o_data->net_amount;
													$totalCommission = $o_data->total_admin_commission;
													$gstAmount =  ($totalCommission * 18)/100;
													$totalAdminCmsn += $totalCommission + $gstAmount;


												}
										$tcsTax = 	($o_data->net_amount * 1)/100;
										$total_pending_payable_amount = ($totalPendingAmount - $totalAdminCmsn - $tcsTax);
											?>
										<tr>
											<td>{{$sn}}</td>
											<td>{{$p_date->pending_order_date}}</td>
											<td>{{round($totalPendingAmount , 2)}}</td>
											<td>{{round($totalPendingNetAmount , 2)}}</td>
											<td>{{round($totalAdminCmsn , 2)}}</td>
											<td>{{round($tcsTax ,2)}}</td>

											<td>{{round($total_pending_payable_amount , 2)}}</td>


											<td><a href="{{'/seller/pending-payment-order/'.$p_date->pending_order_date}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>

										</tr>

									<?php $sn++; } ?>

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
@endsection