@extends('seller.layouts.seller')

@section('content')

<div id="rightSidenav" class="right_side_bar right_side_bar_new">

    <div class="payment-content payment-view">

        <div>

            <div class="header-section clearfix">

                <div class="breadcrumbs-container"><a class="nav-content" href="{{'/seller/payment'}}">Payments</a><span class="icon-separator fa fa-angle-right"></span><a class="nav-content disabled"> Payment</a></div>

                <div class="header-container row">

                    <div class="header-title col-sm-4 text-capitalize"><!-- react-text: 268 --> Payment<!-- /react-text --><!-- react-text: 269 --> : <!-- /react-text --><!-- react-text: 270 -->pending<!-- /react-text --></div>

                    <div class="header-desc col-sm-8 padding-0 text-right row">

                        <div class="payments-info-container download-csv-container">

                            <!-- <button class="download-csv-button blue-button box-shadow">Download Payment Excel</button>-->

                        </div>

                        <div class="payments-info-container paymentdate-container text-left"><span class="payments-info-title">Payment Due Date</span>

                            <div class="truncate"></div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="paymenttypes-container">

                <div class="composite-container">

                    <div class="row row-container">

                        <div class="col-md-10 title-container">Payment Amount : <span class="total-amount">₹ </span></div>

                    </div>

                    <div class="listview-container">

                        <div class="payment-grid">

                            <div class="table-responsive">

                                <table class="payment-grid-view table table-striped">

                                    <thead>

                                    <tr style="background-color: rgb(234, 234, 234);">

                                        <th>No</th>

                                          <th>Order Num</th>

                                        <th>Dispatch Date</th>

                                        <th>Description</th>

                                        <th>Amount</th>

                                        <th>View Details</th>

                                    </tr>

                                    </thead>

                                    <tbody>
									

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
@endsection