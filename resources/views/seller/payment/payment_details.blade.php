@extends('seller.layouts.seller')

@section('content')

<div id="rightSidenav" class="right_side_bar right_side_bar_new">

    <div class="payment-content payment-view">

        <div>

            <div class="header-section clearfix">

                <div class="breadcrumbs-container"><a class="nav-content" href="/1j6mk/payment">Payments</a><span class="icon-separator fa fa-angle-right"></span><a class="nav-content disabled">Next Payment</a></div>

                <div class="header-container row">

                    <div class="header-title col-sm-4 text-capitalize"><!-- react-text: 268 -->Next Payment<!-- /react-text --><!-- react-text: 269 --> : <!-- /react-text --><!-- react-text: 270 -->pending<!-- /react-text --></div>

                    <div class="header-desc col-sm-8 padding-0 text-right row">

                        <div class="payments-info-container download-csv-container">

                            <button class="download-csv-button blue-button box-shadow">Download Payment Excel</button>

                        </div>

                        <div class="payments-info-container paymentdate-container text-left"><span class="payments-info-title">Payment Due Date</span>

                            <div class="truncate">23 Jul, 2018</div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="paymenttypes-container">

                <div class="composite-container">

                    <div class="row row-container">

                        <div class="col-md-10 title-container">Payment Amount : <span class="total-amount"><!-- react-text: 283 -->₹ <!-- /react-text --><!-- react-text: 284 -->32525.92<!-- /react-text --></span></div>

                    </div>

                    <div class="listview-container">

                        <div class="payment-grid">

                            <div class="table-responsive">

                                <table class="payment-grid-view table table-striped">

                                    <thead>

                                    <tr style="background-color: rgb(234, 234, 234);">

                                        <th>No</th>

                                        <th>Sub Order Num</th>

                                        <th>Order Num</th>

                                        <th>Dispatch Date</th>

                                        <th>Description</th>

                                        <th>Amount</th>

                                        <th>View Details</th>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    <tr>

                                        <td>1</td>

                                        <td>4124839_1</td>

                                        <td>4124839</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Shipped</td>

                                        <td><!-- react-text: 306 -->₹ <!-- /react-text --><!-- react-text: 307 -->987.78<!-- /react-text --></td>

                                        <td><button id="myBtn" class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>2</td>

                                        <td>2831634_1</td>

                                        <td>2831634</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Return</td>

                                        <td><!-- react-text: 317 -->₹ <!-- /react-text --><!-- react-text: 318 -->-140<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>3</td>

                                        <td>1319271_1</td>

                                        <td>1319271</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Delivered</td>

                                        <td><!-- react-text: 328 -->₹ <!-- /react-text --><!-- react-text: 329 -->1728.48<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>4</td>

                                        <td>3481563_1</td>

                                        <td>3481563</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Shipped</td>

                                        <td><!-- react-text: 339 -->₹ <!-- /react-text --><!-- react-text: 340 -->1070.08<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>5</td>

                                        <td>6668125_1</td>

                                        <td>6668125</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">RTO</td>

                                        <td><!-- react-text: 350 -->₹ <!-- /react-text --><!-- react-text: 351 -->0<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>6</td>

                                        <td>8502052_1</td>

                                        <td>8502052</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">RTO</td>

                                        <td><!-- react-text: 361 -->₹ <!-- /react-text --><!-- react-text: 362 -->0<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>7</td>

                                        <td>4109203_1</td>

                                        <td>4109203</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Return</td>

                                        <td><!-- react-text: 372 -->₹ <!-- /react-text --><!-- react-text: 373 -->0<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>8</td>

                                        <td>6591505_1</td>

                                        <td>6591505</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Return</td>

                                        <td><!-- react-text: 383 -->₹ <!-- /react-text --><!-- react-text: 384 -->0<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>9</td>

                                        <td>5132531_1</td>

                                        <td>5132531</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Shipped</td>

                                        <td><!-- react-text: 394 -->₹ <!-- /react-text --><!-- react-text: 395 -->1070.08<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>10</td>

                                        <td>7332260_1</td>

                                        <td>7332260</td>

                                        <td>06 Jul, 2018</td>

                                        <td class="text-capitalize">Delivered</td>

                                        <td><!-- react-text: 405 -->₹ <!-- /react-text --><!-- react-text: 406 -->1152.38<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>11</td>

                                        <td>4749100_1</td>

                                        <td>4749100</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Delivered</td>

                                        <td><!-- react-text: 416 -->₹ <!-- /react-text --><!-- react-text: 417 -->1070.08<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>12</td>

                                        <td>5613554_1</td>

                                        <td>5613554</td>

                                        <td>06 Jul, 2018</td>

                                        <td class="text-capitalize">Delivered</td>

                                        <td><!-- react-text: 427 -->₹ <!-- /react-text --><!-- react-text: 428 -->1070.08<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>13</td>

                                        <td>4508538_1</td>

                                        <td>4508538</td>

                                        <td>06 Jul, 2018</td>

                                        <td class="text-capitalize">Delivered</td>

                                        <td><!-- react-text: 438 -->₹ <!-- /react-text --><!-- react-text: 439 -->1070.08<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>14</td>

                                        <td>7366708_1</td>

                                        <td>7366708</td>

                                        <td>06 Jul, 2018</td>

                                        <td class="text-capitalize">Delivered</td>

                                        <td><!-- react-text: 449 -->₹ <!-- /react-text --><!-- react-text: 450 -->1152.38<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>15</td>

                                        <td>4644375_1</td>

                                        <td>4644375</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Delivered</td>

                                        <td><!-- react-text: 460 -->₹ <!-- /react-text --><!-- react-text: 461 -->1070.08<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>16</td>

                                        <td>6100591_1</td>

                                        <td>6100591</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Return</td>

                                        <td><!-- react-text: 471 -->₹ <!-- /react-text --><!-- react-text: 472 -->0<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>17</td>

                                        <td>5120147_1</td>

                                        <td>5120147</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Return</td>

                                        <td><!-- react-text: 482 -->₹ <!-- /react-text --><!-- react-text: 483 -->-140<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>18</td>

                                        <td>9741950_1</td>

                                        <td>9741950</td>

                                        <td>07 Jul, 2018</td>

                                        <td class="text-capitalize">Delivered</td>

                                        <td><!-- react-text: 493 -->₹ <!-- /react-text --><!-- react-text: 494 -->369.94<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>19</td>

                                        <td>3676007_1</td>

                                        <td>3676007</td>

                                        <td>06 Jul, 2018</td>

                                        <td class="text-capitalize">Delivered</td>

                                        <td><!-- react-text: 504 -->₹ <!-- /react-text --><!-- react-text: 505 -->369.94<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    <tr>

                                        <td>20</td>

                                        <td>4921522_1</td>

                                        <td>4921522</td>

                                        <td>06 Jul, 2018</td>

                                        <td class="text-capitalize">Shipped</td>

                                        <td><!-- react-text: 515 -->₹ <!-- /react-text --><!-- react-text: 516 -->369.94<!-- /react-text --></td>

                                        <td><button class="blue-button box-shadow" style="text-transform: capitalize;">View Details</button></td>

                                    </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                        <div class="payment-pagination text-center">

                            <div class="pagination-testing text-center">

                                <ul class="pagination pagination-md">

                                    <li class="disabled"><a role="button" href="#" tabindex="-1" style="pointer-events: none;"><span aria-label="First">«</span></a></li>

                                    <li class="active"><a role="button" href="#">1</a></li>

                                    <li class=""><a role="button" href="#">2</a></li>

                                    <li class=""><a role="button" href="#">3</a></li>

                                    <li class=""><a role="button" href="#"><span aria-label="Last">»</span></a></li>

                                </ul>

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