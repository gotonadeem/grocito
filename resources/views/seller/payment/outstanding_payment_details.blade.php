@extends('seller.layouts.seller')

@section('content')

<div id="rightSidenav" class="right_side_bar right_side_bar_new">

    <div class="payment-content payment-view">

        <div>

            <div class="header-section clearfix">

                <div class="breadcrumbs-container"><a class="nav-content" href="{{'/seller/payment'}}">Payments</a><span class="icon-separator fa fa-angle-right"></span><a class="nav-content disabled">Total Outstanding Payment</a></div>

                <div class="header-container row">

                    <div class="header-title col-sm-4 text-capitalize"> Total Amount= ₹ {{$total_outstanding}} </div>

                    <div class="header-desc col-sm-8 padding-0 text-right row">

                        <div class="payments-info-container download-csv-container">

                            <button class="download-csv-button blue-button box-shadow">Download Payment Excel</button>

                        </div>

                        <div class="payments-info-container paymentdate-container text-left"><span class="payments-info-title">Payment Due Date</span>

                            <div class="truncate">{{$next_payment_date}}</div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="paymenttypes-container">

                <div class="composite-container last-payment-div">

                    <div class="row row-container">

                      <div class="col-md-12">
                        <div class="payment-row">
                        <div class="col-md-12 title-container"><label>Payable Amount :</label> <span class="total-amount">₹ {{round($next_payment_amount-$gst-$commission,2)}}</span></div>
                         
						 <div class="col-md-12 title-container"><label>Last Hold Amount :</label> <span class="total-amount">₹{{round($old_amount,2)}}</span></div>
						 
						<div class="col-md-12 title-container"><label>Total Amount(Payable Amount+Hold Amount) :</label> <span class="total-amount">₹{{round($next_payment1,2)}}</span></div>
						
						
                        <div class="col-md-12 title-container"><label>Return/Exchange/Cancelled Charge :</label> <span class="total-amount error">-₹ {{$penalty_amount}}</span></div>
						
						<div class="col-md-12 title-container"><label>Product Sponsor Charge :</label> <span class="total-amount error">-₹ {{$sponsor_amount}}</span></div>
					    @php
						  $amount= $next_payment1-$penalty_amount-$sponsor_amount;
						@endphp
                       <div class="col-md-12 title-container"><label>Total:</label> <span class="total-amount">₹{{round($amount,2)}}</span></div>
						
						
						<div class="col-md-12 title-container"><label>Net Payable Amount :</label> <span class="total-amount">₹ {{round($next_payment1/2,2)}}</span></div>
						<div class="col-md-12 title-container"><label>Next Hold Amount :</label> <span class="total-amount">₹ {{round($next_payment1/2,2)}}</span></div>
                         </div>
						 </div>

                    </div>

                    <div class="listview-container">
                         <ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#home">Shipped Order</a></li>
							<li><a data-toggle="tab" href="#menu1">Return</a></li>
							<li><a data-toggle="tab" href="#menu2">Exchange</a></li>
							<li><a data-toggle="tab" href="#menu3">Cancelled</a></li>
							<li><a data-toggle="tab" href="#menu4">RTO</a></li>
					    </ul>
						 <div class="tab-content">
                         <div id="home" class="tab-pane fade in active">
						 <h3>Shipped Orders</h3>
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
											<th>View Details</th>
										</tr>
										</thead>
										<tbody>
										@php
										$i = 1
										@endphp
										@foreach($order_list as $vs)
										<tr>
											<td>{{$i}}</td>
											<td>{{$vs->order->order_id}}</td>
											<td>
												{{date('d-m-Y',strtotime($vs->order->shipped_date))}}</td>
											<td class="text-capitalize">{{$vs->status}}</td>
											<td>
											  <?PHP 
											  $commission= $vs->order->payment_amount * $user_data->cartlay_commission/100;
		                                      $gst= ($commission * 18/100);
		 ?>
		
											₹ {{round($vs->order->payment_amount-$commission-$gst,2)}}</td>
											<td><a href="{{'payment-order-details/'.$vs->order_id}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>
										</tr>
										@php
										$i++;
										@endphp
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
											<th>Shipping Charge+Order Amount</th>
											<th>View Details</th>
										</tr>
										</thead>
										<tbody>
										@php
										$i = 1
										@endphp
										@foreach($return_data as $vs)
										<tr>
											<td>{{$i}}</td>
											<td>{{$vs->order->order_id}}</td>
											<td>
												{{date('d-m-Y',strtotime($vs->approved_date))}}</td>
											<td class="text-capitalize">Return</td>
											<td class="error"> -₹											
											<?PHP
											//dd($vs->reseller_payment);
											if(!is_null($vs->order) and !is_null($vs->reseller_payment))
											{
											if($vs->order->payment_amount==$vs->reseller_payment->return_amount)
												   {
													echo $vs->reseller_payment->return_amount+$vs->reseller_payment->extra_amount+($vs->reseller_payment->shipping_charge*2);   
												   }
												   else
												   {
													   echo $vs->reseller_payment->return_amount+$vs->reseller_payment->extra_amount+$vs->reseller_payment->shipping_charge; 
													 
												   }
											}
											else
											{
												echo $vs->order->id;
											}
												   ?>
												   </td>
											<td><a href="{{'payment-order-details/'.$vs->order_id}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>
										</tr>
										@php
										$i++;
										@endphp
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
											<th>View Details</th>
										</tr>
										</thead>
										<tbody>
										@php
										$i = 1
										@endphp
										@foreach($exchange_data as $vs)
										<tr>
											<td>{{$i}}</td>
											<td>{{$vs->order->order_id}}</td>
											<td>
												{{date('d-m-Y',strtotime($vs->created_at))}}</td>
											<td class="text-capitalize">Return</td>
											<td class='error'>- ₹ {{$vs->reseller_payment->exchange_amount}}</td>
											<td><a href="{{'payment-order-details/'.$vs->order_id}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>
										</tr>
										@php
										$i++;
										@endphp
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
											<th>Cancelled Penalty</th>
											<th>View Details</th>
										</tr>
										</thead>
										<tbody>
										@php
										$i = 1
										@endphp
										@foreach($cancelled_data as $vs)
										<tr>
											<td>{{$i}}</td>
											<td><?=!is_null($vs->order)?$vs->order->order_id:'NA'?></td>
											<td>
												{{date('d-m-Y',strtotime($vs->created_at))}}</td>
											<td class="text-capitalize">Cancelled</td>
											
											<td class='error'>-₹
										      <?=$vs->amount;?>
											 </td>
											<td><a href="{{'payment-order-details/'.$vs->order_id}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>
										</tr>
										@php
										$i++;
										@endphp
										@endforeach

									 </tbody>
									</table>
								</div>
							</div>
							</div>
							<div id="menu4" class="tab-pane fade">
								  <h3>RTO Orders</h3>
									<div class="payment-grid">
									<div class="table-responsive">
										<table class="payment-grid-view table table-striped">
											<thead>
											<tr style="background-color: rgb(234, 234, 234);">
												<th>No</th>
												<th>Order Num</th>
												<th>Cancelled Date</th>
												<th>Status</th>
												<th>Cancelled Penalty</th>
												<th>View Details</th>
											</tr>
											</thead>
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