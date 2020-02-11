@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar right_side_bar_new">
<div class="payment-content payment-view">
<div class="payment-summary-container">
<div class="title-bar"><span class="title-text">Payments Overview</span></div>
<div class="summary-block">
<div>
<div class="summary-card-block">
	<div class="general-card">
		<div class="summary-card-cover">
			<div class="summary-card next-payment">
				<div class="card-header">
					<div class="card-heading">Today Payment</div>
					<div class="row desc-date-block">
						<div class="desc-block">Estimated value of next payment. This may change due to returns that come in before the next payout.</div>

					</div>
				</div>
				<?php
				//echo $today_payment[0]['total'];
				$total_today_payable_amount = 0;
					if($today_payment){
						foreach($today_payment as $td){
							$totalTodayAmount = $td->total;
							$totalTodayCommission = $td->total_admin_commission;
							$gstAmount =  ($totalTodayCommission * 18)/100;
							$totalAdminCmsn = $totalTodayCommission + $gstAmount;
							$tcsTax = 	($td->net_amount * 1)/100;
							$returnAmount = Helper::getSellerReturnPenaltyAmount(Auth::user()->id,$td->shippedDate);
							$exchangePenalty = Helper::getSellerExchangePenaltyAmount(Auth::user()->id,$td->shippedDate);
							$total_today_payable_amount += ($totalTodayAmount - $totalAdminCmsn - $tcsTax);
						}
					}
				?>
				<div class="card-inner-block">
					<div class="card-body">
						<div class="inner-card-scroll">
							<div class="card-inner-block">
								<div class="card-body">
									<div class="inner-card-scroll">
										<div class="row card-row">
											<div class="card-row-title"><span>Amount</span></div>
											<div class="sub-total">
												<div class="positive">
													<div class="value-block clickable-text">₹ {{round($total_today_payable_amount,2)}}</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						<!--	<div class="row card-row">
								<div class="card-row-title"><span>Return/Exchange/Cancelled Charge</span></div>
								<div class="sub-total">
									<div class="positive">
										<div class="value-block clickable-text error">- ₹ <?PHP /*
									echo $rma_amount; */?></div>
									</div>
								</div>
							</div>-->
							
							<!--<div class="row card-row">
								<div class="card-row-title"><span>Net Amount</span></div>
								<div class="sub-total">
									<div class="positive">
										<div class="value-block clickable-text">₹ <?php
/*										$actual=$today_payment-$rma_amount;
										echo round($actual,2);
										*/?></div>
									</div>
								</div>
							</div>-->
							
						</div>
					</div>
				</div>
				<div class="payment-button-bottom">
					<div class="view-details-button"><a class="blue-button box-shadow" href="{{URL::to('seller/next-payment-details')}}">View Details</a></div>
				</div>
			</div>
		</div>
		
		<div class="summary-card-cover">
			<div class="summary-card next-payment">
				<div class="card-header">
					<div class="card-heading">Pending Payments</div>
					<div class="row desc-date-block">
						<div class="desc-block" style="width: 100%;">Estimated value of the payments that are due to you. This may change due to returns that come in before the payment settlement dates.</div>
					</div>
				</div>
				<?php
				$total_seller_pending_amount = 0;
				$totalSAdminCmsn =0;
				$tcsSTax = 0;
				if($seller_total_amount){
				foreach($seller_total_amount as $tsm){
				$totalSAmount = $tsm->total;
				$totalSCommission = $tsm->total_admin_commission;
				$gstSAmount =  ($totalSCommission * 18)/100;
				$totalSAdminCmsn = $totalSCommission + $gstSAmount;
				$tcsSTax = 	($tsm->net_amount * 1)/100;
					$returnAmount = Helper::getSellerReturnPenaltyAmount(Auth::user()->id,$tsm->shippedDate);
					$exchangePenalty = Helper::getSellerExchangePenaltyAmount(Auth::user()->id,$tsm->shippedDate);
				$total_seller_payable_amount = ($totalSAmount - $totalSAdminCmsn - $tcsSTax);
				$total_seller_pending_amount = 	$total_seller_payable_amount - $paid_amount ;
				}
				}
				?>
				<div class="card-inner-block">
					<div class="card-body">
						<div class="inner-card-scroll">
							<div class="row card-row">
								<div class="card-row-title"><span>Amount</span></div>
								<div class="sub-total">
									<div class="positive">
										<div class="value-block clickable-text">₹ {{round($total_seller_pending_amount,2)}}</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="payment-button-bottom">
					<div class="view-details-button"><a class="blue-button box-shadow" href="{{'/seller/pending-payments'}}">View Details</a></div>
				</div>
			</div>
		</div>
		<div class="summary-card-cover">
			<div class="summary-card next-payment">
				<div class="card-header">
					<div class="card-heading">Paid Payments</div>
					<div class="row desc-date-block">
						<div class="desc-block" style="width: 100%;">History of amounts transferred to your account.</div>
					</div>
				</div>
				<div class="card-inner-block">
					<div class="card-body">
						<div class="inner-card-scroll">
							<div class="row card-row">
								<div class="card-row-title"><span>Amount</span></div>
								<div class="sub-total">
									<div class="positive">
										
										<div class="value-block clickable-text">₹ {{$paid_amount ? $paid_amount :0}}</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="payment-button-bottom">
					<div class="view-details-button"><a class="blue-button box-shadow" href="{{'/seller/previous-payments'}}">View Details</a></div>
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

