@extends('seller.layouts.seller')

@section('content')

<div id="rightSidenav" class="right_side_bar right_side_bar_new">

    <div class="payment-content payment-view">

        <div>

            <div class="header-section clearfix">

                <div class="breadcrumbs-container"><a class="nav-content" href="{{'/seller/previous-payments'}}">Paid Payments</a><span class="icon-separator fa fa-angle-right"></span><a class="nav-content disabled">Order Details</a></div>

                <div class="header-container row">

                    <div class="header-title col-sm-6 text-capitalize">Order List  {{$start_date}} </div>

                    <div class="header-desc col-sm-6 padding-0 text-right row">

                        <div class="payments-info-container download-csv-container">

                            <!--<button class="download-csv-button blue-button box-shadow">Download Payment Excel</button>-->

                        </div>

                      

                    </div>

                </div>

            </div>

            <div class="paymenttypes-container">

                <div class="composite-container last-payment-div">

                    <div class="row row-container">
                    <div class="col-md-12">
						<?php foreach ($amountData as $amnt){
							$last_payment_amount1=$amnt->total;
							$orderNetAmount=$amnt->net_amount;
						}?>
                        <div class="payment-row">
						<div class="col-md-12 title-container"><label>Total Amount :</label> <span class="total-amount">₹{{round($last_payment_amount1,2)}}</span></div>
                        
						<div class="col-md-12 title-container"><label>Grocito Total Commission :</label> <span class="total-amount error">-₹ {{$adminCommission}}</span></div>
						<div class="col-md-12 title-container"><label>TCS :</label> <span class="total-amount error">-₹ {{round($orderNetAmount* 1)/100 ,2}}</span></div>
						<div class="col-md-12 title-container"><label>Total Payable Amount :</label> <span class="total-amount ">₹ {{$last_payment_amount1 - $adminCommission - $tcsCommission}}</span></div>
                        </div>
                      </div>
                    </div>

                    <div class="listview-container">
                           <ul class="nav nav-tabs">
							<li class="active"><a data-toggle="tab" href="#home">Shipped Order</a></li>
							<li><a data-toggle="tab" href="#menu1">Return</a></li>
							<li><a data-toggle="tab" href="#menu2">Exchange</a></li>
							<li><a data-toggle="tab" href="#menu3">Cancelled</a></li>
							{{--<li><a data-toggle="tab" href="#menu4">RTO</a></li>--}}
					    </ul>
						<div class="tab-content">
                         <div id="home" class="tab-pane fade in active">
                        <div class="payment-grid">

                            <div class="table-responsive">

                                <table class="payment-grid-view table table-striped">

                                    <thead>

                                    <tr style="background-color: rgb(234, 234, 234);">

                                        <th>No</th>

                                        <th>Order Num</th>

                                        <th>Shipped Date</th>

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
                                        <td>{{date('d-m-Y',strtotime($vs->delivery_date))}}</td>
                                        <td class="text-capitalize">{{$vs->status}}</td>
                                        <td>₹ <?php
										         $amount= $vs->order->total_amount;
										         $com= ($amount*$comm/100);
												 $gst=($com*18/100);
												echo round($amount- $com-$gst,2); 
										    ?>
										</td>

                                        <td><a href="{{'/seller/payment-previous-order-details/'.$vs->order_id}}?start_date=<?=Request::segment(3)?>&end_date=<?=Request::segment(4)?>" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>

                                    </tr>
									<?php
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
											
											<th>View Details</th>
										</tr>
										</thead>
										<tbody>
										<?php
										$i = 1
										?>
										@foreach($return_data as $vs)
											<tr>
												<td>{{$i}}</td>
												<td>{{$vs->order->order_id}}</td>
												<td>
													{{date('d-m-Y',strtotime($vs->created_at))}}</td>
												<td class="text-capitalize">Return</td>
												
												<td><a href="{{'/seller/payment-order-details/'.$vs->order_id}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>
											</tr>
											<?php
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
											<th>View Details</th>
										</tr>
										</thead>
										<tbody>
										<?php
										$i = 1
										?>
										@foreach($exchange_data as $vs)
										<tr>
											<td>{{$i}}</td>
											<td>{{$vs->order->order_id}}</td>
											<td>
												{{date('d-m-Y',strtotime($vs->created_at))}}</td>
											<td class="text-capitalize">Exchange</td>
											<td class='error'>- ₹ {{$exchangePenalty}}</td>
											<td><a href="{{'/seller/payment-order-details/'.$vs->order_id}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>
										</tr>
										<?php
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

											<th>View Details</th>
										</tr>
										</thead>
										<tbody>
										<?php
										$i = 1
										?>
										@foreach($cancelled_data as $vs)
										<tr>
											<td>{{$i}}</td>
											<td><?=$vs->order_id?></td>
											<td>
												{{date('d-m-Y',strtotime($vs->updated_at))}}</td>
											<td class="text-capitalize">Cancelled</td>
											

											<td><a href="{{'/seller/payment-order-details/'.$vs->id}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>
										</tr>
										<?php
										$i++;
										?>
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