@extends('seller.layouts.seller')

@section('content')

<div id="rightSidenav" class="right_side_bar right_side_bar_new">

    <div class="payment-content payment-view">

        <div>

            <div class="header-section clearfix">

                <div class="breadcrumbs-container"><a class="nav-content" href="{{'/seller/payment'}}">Payments</a><span class="icon-separator fa fa-angle-right"></span><a class="nav-content disabled">Last Payment</a></div>

                <div class="header-container row">

                    <div class="header-title col-sm-4 text-capitalize">Last Payment:Completed</div>

                    <div class="header-desc col-sm-8 padding-0 text-right row">

                        <div class="payments-info-container download-csv-container">

                            <button class="download-csv-button blue-button box-shadow">Download Payment Excel</button>

                        </div>

                        <div class="payments-info-container paymentdate-container text-left"><span class="payments-info-title">Payment Due Date</span>

                            <div class="truncate">{{$last_payment_date}}</div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="paymenttypes-container">

                <div class="composite-container last-payment-div">

                    <div class="row row-container">
                        <div class="col-md-12">
                        <div class="payment-row">
						 <div class="col-md-12 title-container"><label>Last Hold Amount :</label> <span class="total-amount">₹{{round($hold_amount,2)}}</span></div>
						 
                         <div class="col-md-12 title-container"><label>Payable Amount :</label> <span class="total-amount">₹ {{round($last_payment_amount1-$commission-$gst,2)}}</span></div>
                         
						 <div class="col-md-12 title-container"><label>Total Amount(Payable Amount+Hold Amount) :</label> <span class="total-amount">₹ @php 
						    $payable= $last_payment_amount1-$commission-$gst;
						    $total=$hold_amount+$payable;
							
						 @endphp {{round($total,2)}}</span></div>
						 
                        <div class="col-md-12 title-container"><label>Return/Exchange/Cancelled Charge :</label> <span class="total-amount error">-₹ {{$penalty_amount}}</span></div>
						
						<div class="col-md-12 title-container"><label>Product Sponsor Charge :</label> <span class="total-amount error">-₹ {{$sponsor_amount}}</span></div>
						
						<div class="col-md-12 title-container"><label>Total :</label> <span class="total-amount error">-₹ {{round($total-$penalty_amount-$sponsor_amount,2)}}</span></div>
						
						
						 <?php
						 $actual=$total-$penalty_amount-$sponsor_amount;
						 ?>
						
						<div class="col-md-12 title-container"><label>Net Payable Amount :</label> <span class="total-amount">₹ {{round($actual/2,2)}}</span></div>
						<div class="col-md-12 title-container"><label>Next Hold Amount :</label> <span class="total-amount">₹ {{round($actual/2,2)}}</span></div>
						
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

                                        <th>Description</th>

                                        <th>Amount</th>

                                        <th>View Details</th>

                                    </tr>

                                    </thead>

                                    <tbody>
									@php
									$i = 1;
									$sum=0;
									@endphp
                                    @foreach($order_list as $vs)
                                    <tr>

                                        <td>{{$i}}</td>

                                       
                                        <td>{{$vs->order->order_id}}</td>

                                        <td>{{date('d M Y',strtotime($vs->order->shipped_date))}}</td>
                                        <td class="text-capitalize">{{$vs->status}}</td>

                                        <td> 
										₹  @php
										         
										        $amount=$vs->order->payment_amount;
												$sum= $sum+$amount;
												
										        $comm= $amount*$comm/100;
												echo $amount- $comm- ($comm*18/100); 
										    @endphp
										
										</td>

                                        <td><a href="{{'payment-order-details/'.$vs->order_id}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>

                                    </tr>
									@php
									$i++;
									@endphp
									@endforeach
                                         <?PHP //cho $sum; ?>
                                 </tbody>

                                </table>

                            </div>
								<div  class="tab-pane fade">
									<div class="payment-pagination text-center">
										<div class="pagination-testing text-center">
										
										</div>
									</div>
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
												{{date('d-m-Y',strtotime($vs->created_at))}}</td>
											<td class="text-capitalize">Return</td>
											<td class="error"> -₹											
											<?PHP
											if($vs->order->payment_amount==$vs->reseller_payment->return_amount)
												   {
													echo $vs->reseller_payment->return_amount+$vs->reseller_payment->extra_amount+($vs->reseller_payment->shipping_charge*2);   
												   }
												   else
												   {
													   echo $vs->reseller_payment->return_amount+$vs->reseller_payment->extra_amount+$vs->reseller_payment->shipping_charge; 
													 
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







<div id="myModal" class="modal">

    <!-- Modal content -->

    <div class="modal-content">

        <div class="modal-body_custom">

            <div id="payment-detail-popup" class="popup" style="display: block;">

                <div class="popup-inner">

                    <header class="modalbox-header border-bottom">

                        <div class="col-md-12 bold text-center">Order Item History</div>

                        <span class="close">&times;</span>

                    </header>

                    <div id="payment-details-popup-body">

                        <div class="modal-popup-content">

                            <div class="product-details-container">

                                <div class="payment-product-container">

                                    <div class="col-md-2 product-image-container">

                                        <div class="product-img-holder"><img src="https://s3-ap-southeast-1.amazonaws.com/meesho-supply-v2/images/products/300461/1_512.jpg"></div>

                                    </div>

                                    <div class="col-md-10 details-holder">

                                        <div class="product-title bold">Beautiful Silk Women's Suit</div>

                                        <div class="product-sub-heading">

                                            <div class="product-order-details">

                                                <div class="order-details-container">

                                                    <table class="order-detail-info">

                                                        <thead>

                                                        <tr>

                                                            <th colspan="2" class="text-center grey-background">Order Details</th>

                                                        </tr>

                                                        </thead>

                                                        <tbody>

                                                        <tr>

                                                            <th>Sub Order No.</th>

                                                            <td>4124839_1</td>

                                                        </tr>

                                                        <tr>

                                                            <th>Quantity</th>

                                                            <td>1</td>

                                                        </tr>

                                                        <tr>

                                                            <th>SKU Code</th>

                                                            <td>IMG_8935</td>

                                                        </tr>

                                                        </tbody>

                                                    </table>

                                                    <table class="order-detail-price">

                                                        <thead>

                                                        <tr>

                                                            <th colspan="2" class="text-center grey-background">Sale Details</th>

                                                        </tr>

                                                        </thead>

                                                        <tbody>

                                                        <tr>

                                                            <th>Sale Amount (Incl. GST)</th>

                                                            <td>₹ 1199</td>

                                                        </tr>

                                                        <tr>

                                                            <th>Meesho Commission</th>

                                                            <td>₹ -179</td>

                                                        </tr>

                                                        <tr class="">

                                                            <th>Meesho Commission GST (18%) (Input Credits for Seller)</th>

                                                            <td>₹ -32.22</td>

                                                        </tr>

                                                        <tr class="last-child">

                                                            <th>Return Refund Amount</th>

                                                            <td>NA</td>

                                                        </tr>

                                                        <tr class="border-top maroon-row">

                                                            <th style="padding-top: 10px;">Settlement Amount</th>

                                                            <td class="bold" style="padding-top: 10px;"><!-- react-text: 650 -->₹ <!-- /react-text --><!-- react-text: 651 -->987.78<!-- /react-text --></td>

                                                        </tr>

                                                        <tr class="last-child">

                                                            <th>GST Paid by Seller</th>

                                                            <td>₹ -24.88</td>

                                                        </tr>

                                                        <tr class="payment-total-price border-top">

                                                            <th>Effective Payment to Seller</th>

                                                            <td><!-- react-text: 658 -->₹ <!-- /react-text --><!-- react-text: 659 -->962.9<!-- /react-text --></td>

                                                        </tr>

                                                        </tbody>

                                                    </table>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="address-details-container">

                                <div class="address-heading">Address</div>

                                <div class="payment-order-address">

                                    <address>

                                        <div class="add-name">Syed</div>

                                        <div class="add-line-1">175/5A </div>

                                        <div class="add-line-2">Barkath Manzil</div>

                                        <div class="add-landmark">Opposite Fair Mount apartment</div>

                                        <div class="add-city">Banglore</div>

                                        <div class="add-state"><!-- react-text: 670 -->Karnataka<!-- /react-text --><!-- react-text: 671 -->(<!-- /react-text --><!-- react-text: 672 -->560045<!-- /react-text --><!-- react-text: 673 -->)<!-- /react-text --></div>

                                    </address>

                                </div>

                            </div>



                            <div class="timeline-overflow">

                                <div class="timeline-div timeline-default" style="width: 495px;">

                                    <div class="timeline-container">

                                        <div class="timeline-tooltip-container">

                                            <div class="title top"><span class="text-capitalize">ordered</span></div>

                                            <div class="timeline-event">

                                                <div class="outer-circle"><span class="inner-circle"></span><span class="connector"></span></div>

                                            </div>

                                            <div class="date-container">

                                                <div class="date">07 Jul, 2018</div>

                                                <div class="day">Saturday</div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="timeline-container active">

                                        <div class="timeline-tooltip-container">

                                            <div class="title top"><span class="text-capitalize">shipped</span></div>

                                            <div class="timeline-event">

                                                <div class="outer-circle"><span class="inner-circle"></span><span class="connector"></span></div>

                                            </div>

                                            <div class="date-container">

                                                <div class="date">07 Jul, 2018</div>

                                                <div class="day">Saturday</div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="timeline-container">

                                        <div class="timeline-tooltip-container">

                                            <div class="title top"><span class="text-capitalize">payment</span></div>

                                            <div class="timeline-event">

                                                <div class="outer-circle"><span class="inner-circle"></span><!-- react-text: 705 --><!-- /react-text --></div>

                                            </div>

                                            <div class="date-container">

                                                <div class="date">23 Jul, 2018</div>

                                                <div class="day">Monday</div>

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

@endsection