@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar right_side_bar_new">
    <div class="payment-content payment-view">
        <div>
            <div class="header-section clearfix">
                <div class="breadcrumbs-container"><a class="nav-content" href="{{'/seller/payment'}}">Payments</a><span class="icon-separator fa fa-angle-right"></span><a class="nav-content disabled">Paid Payment</a></div>
                <div class="header-container row">

                    <div class="header-title col-sm-4 text-capitalize"><!-- react-text: 268 -->Paid Payment<!-- /react-text --><!-- react-text: 269 --> : <!-- /react-text --><!-- react-text: 270 -->Completed<!-- /react-text --></div>

                    <div class="header-desc col-sm-8 padding-0 text-right row">

                        <div class="payments-info-container download-csv-container">

                            <!--<button class="download-csv-button blue-button box-shadow">Download Payment Excel</button>-->

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
                                          <th>Date</th>
                                          <th>Amount</th>
                                          {{--<th>Admin Commission</th>--}}

                                          <th>Transaction Id</th> 
                                          <th>Payment Date</th> 
                                        <th>View Orders</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									$i = 1
									?>
                                    @foreach($payment_list as $vs)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$vs->order_date}}</td>
                                       {{-- <td>{{$vs->total_commission}}</td>--}}
                                        <td class="text-capitalize">₹ {{$vs->total_amount}}</td>
                                        <td class="text-capitalize">{{$vs->transaction_id}}</td>
                                        <td>{{date('d-m-Y',strtotime($vs->created_at))}}</td>
                                        <td><a href="{{'/seller/all-order/'.$vs->order_date}}" id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</a></td>

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
						 	  </div>

                       

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@endsection