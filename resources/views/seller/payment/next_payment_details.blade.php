@extends('seller.layouts.seller')
@section('content')
	<div id="rightSidenav" class="right_side_bar right_side_bar_new">
		<div class="payment-content payment-view">
			<div>
				<div class="header-section clearfix">
					<div class="breadcrumbs-container"><a class="nav-content" href="{{'/seller/payment'}}">Payments</a><span class="icon-separator fa fa-angle-right"></span><a class="nav-content disabled">Today Payment</a></div>
					<div class="header-container row">

						<div class="header-title col-sm-4 text-capitalize">
							Today Payment</div>

						<div class="header-desc col-sm-8 padding-0 text-right row">

							<div class="payments-info-container download-csv-container">

								<!--<button class="download-csv-button blue-button box-shadow">Download Payment Excel</button>-->

							</div>


						</div>

					</div>

				</div>
				<?php
				//echo $today_payment[0]['total'];
				$total_today_payable_amount = 0;
				$totalAdminCmsn = 0;
				$tcsTax=0;
				if($todayPaymentData){
					foreach($todayPaymentData as $td){
						$totalTodayAmount = $td->total;
						$totalTodayCommission = $td->total_admin_commission;
						$gstAmount =  ($totalTodayCommission * 18)/100;
						$totalAdminCmsn = $totalTodayCommission + $gstAmount;
						$tcsTax = 	($td->net_amount * 1)/100;
						$returnAmount = Helper::getSellerReturnPenaltyAmount(Auth::user()->id,$td->shippedDate);
						$exchangePenalty = Helper::getSellerExchangePenaltyAmount(Auth::user()->id,$td->shippedDate);
						$total_today_payable_amount = ($totalTodayAmount - $totalAdminCmsn - $tcsTax - $returnAmount - $exchangePenalty);
					}
				}
				?>
				<div class="paymenttypes-container">

					<div class="composite-container">

						<div class="row row-container">

							<div class="col-md-10 title-container">Payment Amount : <span class="total-amount">₹ {{round($total_today_payable_amount,2)}}</span></div>

						</div>

						<div class="listview-container">
							<div class="payment-grid">
								<div class="table-responsive">
									<table class="payment-grid-view table table-striped">
										<thead>
										<tr style="background-color: rgb(234, 234, 234);">

											<th>No</th>
											<th>Date</th>
											<th>Admin Commission</th>
											<th>TCS</th>
											<th>Payable Amount</th>

											<th>View Orders</th>
										</tr>
										</thead>
										<tbody>


											<tr>
												<td>1</td>
												<td>{{date('d-m-Y')}}</td>
												<td>{{round($totalAdminCmsn,2)}}</td>
												<td>{{round($tcsTax,2)}}</td>
												<td class="text-capitalize">₹ {{round($total_today_payable_amount,2)}}</td>

												<td><a href="{{'/seller/today-payment-order'}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>

											</tr>



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