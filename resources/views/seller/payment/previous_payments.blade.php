@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="payment-content payment-view">
        <div>
            <div class="header-section clearfix">
                <div class="breadcrumbs-container"><a class="nav-content" href="/1j6mk/payment">Payments</a><span class="icon-separator fa fa-angle-right"></span><a class="nav-content disabled">Next Payment</a></div>
                <div class="header-container row">

                    <div class="header-title col-sm-4 text-capitalize"><!-- react-text: 268 -->Previous Payment<!-- /react-text --><!-- react-text: 269 --> : <!-- /react-text --><!-- react-text: 270 -->pending<!-- /react-text --></div>

                    <div class="header-desc col-sm-8 padding-0 text-right row">

                        <div class="payments-info-container download-csv-container">

                            <button class="download-csv-button blue-button box-shadow">Download Payment Excel</button>

                        </div>


                    </div>

                </div>

            </div>

            <div class="paymenttypes-container">

                <div class="composite-container">

                    <div class="row row-container">

                        <div class="col-md-10 title-container">Payment Amount : <span class="total-amount">₹ {{$total}}</span></div>

                    </div>

                    <div class="listview-container">
                        <div class="payment-grid">
                            <div class="table-responsive">
                                <table class="payment-grid-view table table-striped">
                                    <thead>
                                    <tr style="background-color: rgb(234, 234, 234);">

                                          <th>No</th>
                                          <th>Week Number</th>
                                          <th>From Date</th>
                                          <th>To Date</th>
                                          <th>Amount</th> 
                                          <th>Transaction Id</th> 
                                          <th>Payment Date</th> 
                                        <th>View Orders</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@php
									$i = 1
									@endphp
                                    @foreach($payment_list as $vs)
                                    <tr>

                                        <td>{{$i}}</td>

                                       
                                        <td>{{$vs->week_number}}</td>

                                        <td>{{$vs->from_date}}</td>
                                        <td>{{$vs->to_date}}</td>
                                        <td class="text-capitalize">{{$vs->amount}}</td>
                                        <td class="text-capitalize">{{$vs->transaction_id}}</td>
                                        <td>₹{{date('d-m-Y',strtotime($vs->created_at))}}</td>
                                        <td><a href="" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>

                                    </tr>
									<?php
									$i++;
									?>
									@endforeach

                                 </tbody>

                                </table>

                            </div>

                        </div>

                        <div class="payment-pagination text-center">

                            <div class="pagination-testing text-center">

							{{$order_list->links()}}
                               

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