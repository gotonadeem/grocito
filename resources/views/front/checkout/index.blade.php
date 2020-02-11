@extends('front.layout.front')
@section('content')
<?php
if(Auth::user()){
  $userMobile=Auth::user()->mobile;
}else{
    $userMobile='';
}

$cityStateName = Helper::getCityStateByPincode(); ?>
<link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
<section class="breadcrumbs-custum">
    <div class="container">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Checkout</li>
        </ul>
    </div>
</section>
<section class="checkout cart-page my-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="adddress-field">
                    <div class="phone-number-filed">
                        <div class="checkout-step__body">
                            <section class="checkout-login">
                                <div class="checkout-login__msg">We need your phone number so that we can update you about your order.</div>
                                <div class="login__body">
                                    @if(Auth::check())
                                    Mobile: {{Auth::user()->mobile}}
                                    <input type="hidden" id="lid" value="1">
                                    @else
                                    <a  data-toggle="modal" data-target="#mySignup" href="#" class="btn weight--semibold login-button btn--gray">Login/Signup</a>
                                    <input type="hidden" id="lid" value="0">
                                    @endif
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="checkout-step"><span class="checkout-step__number active">1</span><span class="checkout-step__name">Delivery Address</span><!--<a class="btn btn--inverted checkout-step__change-btn" id="deliver_add_change">Change</a>-->
                        <div class="checkout-step__body" id="deliver_here">

                            <div>
                                @if(Auth::check())
                                <a href="java:void(0);" class="new-delivery-address-btn" data-toggle="modal" data-target="#myAddress">Add New Delivery Address</a>
                                @else
                                <a href="java:void(0);" class="new-delivery-address-btn show-toast" >Add New Delivery Address</a>
                                @endif
                                <section class="delivery-addr" id="address_list">
                                    <div class="error address-error" style="display:none">Please select delivery address.</div>
                                    @foreach($address_list as $vs)
                                    <div class="delivery-addr__label">
                                    <span class="delete-address" onclick="delete_address(this.id)" id="<?=$vs->id?>"><i class="fa fa-trash-o" aria-hidden="true"></i></span>
                                        <div class="checkout-address-actions__edit" onclick="edit_address(this.id)" id="{{$vs->id}}"></div>
                                        <div class="checkout-address-item addr-label weight--bold">{{$vs->type}}</div>
                                        <div class="checkout-address-item weight--normal"><span class="capitalize">{{$vs->title}}</span>{{$vs->name}}</div>
                                        <div class="checkout-address-item addr-lines">{{$vs->street}},{{$vs->state}} {{$vs->city}}, {{$vs->pincode}}</div>
                                        <div class="checkout-address-item addr-landmark">{{$vs->house}}</div>
                                        <label class="container-radio">
							  <input class="radioput" type="radio" name="d_address"  id="{{$vs->id}}" value="{{$vs->id}}" onclick="deliver_here('{{$cityId}}',this.id)">
							  <span class="checkmark">Deliver Here</span>
							</label>
                                        <!--<button class="btn btn--full btn-select-address" type="button">Deliver Here</button>-->
                                    </div> 
                                    @endforeach
                                </section>
                            </div>


                        </div>
                    </div>

                    <div class="deliverSlot"> <span class="checkout-step__number active">2</span> <span class="checkout-step__name">Delivery Date &amp; Time</span><!--<a class="btn btn--inverted checkout-step__change-btn" id="deliver_date_change">Change</a>-->
                        
                        <div class="time-setup">
                        
                        <ul class="nav nav-tabs" role="tablist">
						<li class="nav-item">
						  <a class="nav-link active delivery_type" data-id="standard" id="standard" data-toggle="pill" href="#Standard" onclick="get_delivery_type(this.id)">Standard</a>
						</li>
						  <?php 
						  $endTime = $deliveryTime ? $deliveryTime->end_time : '08:00PM'; //default value 08:00PM.
                          $strEndTime= strtotime($endTime); echo '<br>';
                          $currentTime= strtotime(date('h:i A'));
                      
                        ?>
                        <?php  if($strEndTime > $currentTime){ ?>
						<li class="nav-item">
						  <a class="nav-link delivery_type" data-id="express" id="express" data-toggle="pill" href="#Express" onclick="get_delivery_type(this.id)">Express</a>
						</li>
						<?php } ?>
					  </ul>
                        
                        </div>

                      <div class="tab-content">  
                       <div id="Standard" class="container tab-pane active"> 
                        <div class="delivery-day-div" id="time_slot">

                            <ul class="nav nav-pills justify-content-between" role="tablist">

                                <?PHP
                                $m= date("m");
                                $de= date("d");
                                $y= date("Y");
                                for($i=0; $i<6; $i++){
                                    ?>
                                    <li class="nav-item"> 
                                        <a class="nav-link <?=($i==0)?"active":""?>" onclick="get_date(this.id)" id="<?=date('d-m-Y',mktime(0,0,0,$m,($de+$i),$y))?>" data-delivery-date="<?=date('d-m-Y',mktime(0,0,0,$m,($de+$i),$y))?>" data-toggle="pill" href="#menu{{$i+1}}">
                                            <span class="delivery-day"> <?=date('d M',mktime(0,0,0,$m,($de+$i),$y))?> </span>
                                        </a> </li>

                                    <?PHP
                                }
                                ?>


                            </ul>
                            <div class="tab-content">
                                <div id="menu1" class="tab-pane active">
                                    <ul class="nav flex-column">
                                        <?PHP
                                        $timeInterval = $deliveryTime ? $deliveryTime->time_interval * 60 : 120;//default value 2 hrs.
                                        $startTime = $deliveryTime ? $deliveryTime->start_time :'10:00AM'; //defauly value 10:00AM.
                                        $endTime = $deliveryTime ? $deliveryTime->end_time : '08:00PM'; //default value 08:00PM.
                                        $expressTime = $deliveryTime ? $deliveryTime->express_time : '45'; //default value 45 minute.
                                        $timeslot= Helper::getServiceScheduleSlots($timeInterval, $startTime, $endTime);
                                        foreach($timeslot as $ks=>$vs){
											 if(strtotime(date('h:m a')) < strtotime($vs['start']))
					                          {
                                            ?>
                                            <li class="nav-item">
                                                <div class="nav-radio">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="timeInput{{$ks}}"  value="{{$vs['start']}} - {{$vs['end']}}" name="time">
                                                        <label class="custom-control-label" for="timeInput{{$ks}}"><span class="slot-time">{{$vs['start']}} - {{$vs['end']}}</span></label>
                                                    </div>
                                                </div>
                                            </li>
                                            <?PHP
					                           }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div id="menu2" class="tab-pane fade">
                                    <ul class="nav flex-column">
                                        <?PHP
                                        foreach($timeslot as $ks=>$vs){
                                            ?>
                                            <li class="nav-item">
                                                <div class="nav-radio">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="timeInput4{{$ks+1}}" value="{{$vs['start']}} - {{$vs['end']}}" name="time">
                                                        <label class="custom-control-label" for="timeInput4{{$ks+1}}"><span class="slot-time">{{$vs['start']}} - {{$vs['end']}}</span></label>
                                                    </div>
                                                </div>
                                            </li>
                                            <?PHP
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div id="menu3" class="tab-pane fade">
                                    <ul class="nav flex-column">
                                        <?PHP
                                        foreach($timeslot as $ks=>$vs){
                                            ?>
                                            <li class="nav-item">
                                                <div class="nav-radio">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="timeInput7{{$ks+1}}"  value="{{$vs['start']}} - {{$vs['end']}}" name="time">
                                                        <label class="custom-control-label" for="timeInput7{{$ks+1}}"><span class="slot-time">{{$vs['start']}} - {{$vs['end']}}</span></label>
                                                    </div>
                                                </div>
                                            </li>
                                            <?PHP
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div id="menu4" class="tab-pane fade">
                                    <ul class="nav flex-column">
                                        <?PHP
                                        foreach($timeslot as $ks=>$vs){
                                            ?>
                                            <li class="nav-item">
                                                <div class="nav-radio">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="timeInput10{{$ks+1}}"  value="{{$vs['start']}} - {{$vs['end']}}" name="time">
                                                        <label class="custom-control-label" for="timeInput10{{$ks+1}}"><span class="slot-time">{{$vs['start']}} - {{$vs['end']}}</span></label>
                                                    </div>
                                                </div>
                                            </li>
                                            <?PHP
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div id="menu5" class="tab-pane fade">
                                    <ul class="nav flex-column">
                                        <?PHP
                                        foreach($timeslot as $ks=>$vs){
                                            ?>
                                            <li class="nav-item">
                                                <div class="nav-radio">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="timeInput13{{$ks+1}}"  value="{{$vs['start']}} - {{$vs['end']}}" name="time">
                                                        <label class="custom-control-label" for="timeInput13{{$ks+1}}"><span class="slot-time">{{$vs['start']}} - {{$vs['end']}}</span></label>
                                                    </div>
                                                </div>
                                            </li>
                                            <?PHP
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div id="menu6" class="tab-pane fade">
                                    <ul class="nav flex-column">
                                        <?PHP
                                        foreach($timeslot as $ks=>$vs){
                                            ?>
                                            <li class="nav-item">
                                                <div class="nav-radio">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" id="timeInput16{{$ks+1}}"  value="{{$vs['start']}} - {{$vs['end']}}" name="time">
                                                        <label class="custom-control-label" for="timeInput16{{$ks+1}}"><span class="slot-time">{{$vs['start']}} - {{$vs['end']}}</span></label>
                                                    </div>
                                                </div>
                                            </li>
                                            <?PHP
                                        }
                                        ?>

                                    </ul>
                                </div>
                            </div>
                           <!-- <div class="proceed-to-pay">
                                <button class="btn custom-btn" id="process_to_payment">Proceed to Payment</button>
                            </div>-->


                        </div>
                        </div>
                      
                        <input type="hidden" name="express_time" id="express_time" value="{{$expressTime}}">
                        <div id="Express" class="container tab-pane fade"><div class="delivery-day-div" id="time_slot">Order will be delivered within {{$expressTime}} minutes  </div></div>
                        
                        </div>

                    </div>
                    <div class="error date-time-error" style="display:none">Please select delivery time.</div>
                    <div class="deliverSlot payment-sec"> <span class="checkout-step__number active">3</span> <span class="checkout-step__name">Payment</span>
                        <div class="saleplus-wallet w-100 pt-3 pl-5">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="wallet-check" value="use_wallet" name="wallet-check">
                                <label class="custom-control-label" for="wallet-check">Use Grocito Wallet (<i class="fa fa-inr" aria-hidden="true"></i><span>{{$my_wallet_amount}}</span>)</label>
                            </div>
                        </div>
                        <?PHP
                        $sum=0;
                        $netAmount=0;
                        $totalGst=0;
                        $totalNetAmount=0;
                        $gst=0;
                        foreach($cart_data as $vs):

                            if($vs->sprice!=0)
                            {
                                $sum= $sum+($vs->sprice*$vs->qty);
                            }
                            else
                            {
                                $sum= $sum+($vs->price*$vs->qty);
                            }

                                $gstCal = $vs->gst_percentage + 100;
                                $netAmount = round((($vs->sprice * $vs->qty) * 100) / $gstCal,2);
                                $totalGst += ($vs->sprice * $vs->qty) - $netAmount;
                                $totalNetAmount += $netAmount;


                        endforeach;
                        if($totalGst > 0){
                            $gst = round($totalGst/2,2);
                        }

                        ?>


                        <div class="payment-invoice">
                           <!-- <div class="payment-invoice__row">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="total-amont-text">Net Amount</span>
                                    <span class="total-amont"><img src="{{ URL::asset('public/images/icons8-rupee-32.png') }}" class="img-fluid"><span class="net_amount">{{round($totalNetAmount,2)}}</span></span>
                                </div>
                            </div>
                            <div class="payment-invoice__row">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="total-amont-text">CGST</span>
                                    <span class="total-amont"><img src="{{ URL::asset('public/images/icons8-rupee-32.png') }}" class="img-fluid"><span class="cgst_amount">{{$gst}}</span></span>
                                </div>
                            </div>
                            <div class="payment-invoice__row">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="total-amont-text">SGST</span>
                                    <span class="total-amont"><img src="{{ URL::asset('public/images/icons8-rupee-32.png') }}" class="img-fluid"><span class="sgst_amount">{{$gst}}</span></span>
                                </div>
                            </div>-->
                            <div class="payment-invoice__row">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="total-amont-text">Total Payable</span>
                                    <span class="total-amont"><img src="{{ URL::asset('public/images/icons8-rupee-32.png') }}" class="img-fluid"><span class="total_payable">{{$sum}}</span></span>
                                </div>
                            </div>

                            <div class="payment-invoice__row payment-delivery-charge">

                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="total-amont-text">Delivery Charges</span>
										<span class="total-amont">
										
										   +<img src="{{ URL::asset('public/images/icons8-rupee-32.png') }}" class="img-fluid"> <span class="d_charge"></span>
									  
										</span>
                                </div>

                            </div>
                            <div class="payment-invoice__row grocito-wallet " style="display: none; border-bottom: 1px solid #ccc; ">

                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="total-amont-text">Grocito Wallet</span>
										<span class="total-amont">
										   -<img src="{{ URL::asset('public/images/icons8-rupee-32.png') }}" class="img-fluid"> <span class="apply_wallet"></span>

										</span>
                                </div>

                            </div>

                            <div class="payment-invoice__row">

                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="total-amont-text">Amount Payable <small>(incl. of all taxes)</small></span>
                                    <span class="total-amont"><img src="{{ URL::asset('public/images/icons8-rupee-32.png') }}" class="img-fluid"><span class="sub_total">{{$sum}}</span></span>
                                </div>

                            </div>

                        </div>

                        <input type="hidden" id="total_sum" value="{{$sum}}">
                        <input type="hidden" id="shipping_charge" value="">
                        <input type="hidden" id="wallet_amount" value="{{$my_wallet_amount}}">
                        <input type="hidden" id="withdraw_wallet_amount" value="0">
                        <input type="hidden" id="net_amount" value="{{round($totalNetAmount,2)}}">
                        <input type="hidden" id="sgst_amount" value="{{$gst}}">

                        <div class="delivery-day-div payment-div">
                            <ul class="nav nav-pills justify-content-between" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="pill" href="#Wallet"><span>Payment</span></a> </li>
                            </ul>
                            <div class="tab-content">
                                <div id="Wallet" class="tab-pane active">
                                    <ul class="nav flex-column">
                                        <li class="nav-item paytm-option">
                                            <div class="nav-radio">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="paytmradio1" value="razorpay" name="payment_mode">
                                                    <label class="custom-control-label" for="paytmradio1"><span><img src="{{ URL::asset('public/images/razorpay.png') }}" class="img-fluid" style="max-width:120px;"></span></label>
                                                </div>
                                            </div>
                                        </li>
                                       {{-- <li class="nav-item paytm-option">
                                            <div class="nav-radio">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="paytmradio1" value="paytm" name="payment_mode">
                                                    <label class="custom-control-label" for="paytmradio1"><span><img src="{{ URL::asset('public/images/paytm.png') }}" class="img-fluid"> (Paytm)</span></label>
                                                </div>
                                            </div>
                                        </li>--}}
                                        <li class="nav-item">
                                            <div class="nav-radio">
                                                <div class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input" id="paytmradio2" value="cod" name="payment_mode">
                                                    <label class="custom-control-label" for="paytmradio2"><span> <img src="{{ URL::asset('public/images/cod.png') }}" class="img-fluid"> Cash on delivery</span></label>
                                                    <div class="error payment-mode" style="display:none">Please select payment method.</div>
                                                </div>
                                            </div>
                                        </li>

                                    </ul>
                                    <div class="proceed-to-pay">
                                        <button class="btn custom-btn" id="place_order">Pay Now</button>
                                        <p class="my-3"><small>You will be redirected to wallet’s website to authorize payment</small>
                                        <p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="cart-div">
                    <div class="cart-header">
                        <div class="d-flex justify-content-between"><span>My Cart</span> <span><?php Helper::getCartCount();?></span></div>
                    </div>
                    <div class="cart-inner">
                        <?php $sn = 1; ?>
                        @foreach($cart_data as $vs)
                        <div class="d-flex justify-content-around align-items-center border-bottom py-3">
                            <span class="number-item">{{$sn}}</span>
                            <span class="product-cart-img"><img src="{{ URL::asset('public/admin/uploads/product/'.$vs->product_image) }}" class="img-fluid"></span>
                            <div class="cart-content">
                                <div class="d-flex flex-column">
                                   <!-- <p class="discont-cart"><span>20% off</span></p> -->
                                    <p class="checkout-cart__item-name">{{$vs->product_name}} ({{$vs->weight}})</p>
                                    <p class="item-cart"><span>{{$vs->qty}}x{{$vs->sprice}}</span></p>
                                    <p class="cart-total"><span><img src="" class="img-fluid">{{$vs->qty*$vs->sprice}}</span></p>

                                </div>
                            </div>
                        </div>
                        <?php $sn++;?>
                        @endforeach




                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- The Modal -->
<div class="modal" id="myAddress">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="p-3 text-center address-header">
                <h5 class="modal-title">Add New Delivery Address</h5>
                <p class="m-0">Please enter the accurate address, it will help us to serve you better</p>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form name="address_form" action="#" id="address_form">
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="new-address-row">
                        <div class="form-group d-flex justify-content-between align-items-center">
                            <div class="col-md-6">
                                <p class="m-0">State</p>

                                <input type="text" class="form-control" id="state" readonly name="state" value="{{$cityStateName->state_name}}"></div>
                            <div class="col-md-6">
                                <p class="m-0">City</p>
                                <input type="text" class="form-control" id="city" readonly name="city" value="{{$cityStateName->city_name}}"></div>
                        </div>
                    </div>

                    <div class="new-address-row">
                        <div class="form-group d-flex justify-content-between align-items-center">
                            <div class="col-md-12">
                                <p class="m-0">Pincode</p>
                                <input type="text" class="form-control" id="pincode" name="pincode" readonly value="{{session('pincode')}}"></div>
                        </div>
                    </div>
					<div class="new-address-form-row">
                            <p>Area / Locality</p>
                            <div class="form-group d-flex justify-content-start align-items-center">
                                <input type="text" class="form-control address_detect"  name="address" id="address" placeholder="E.g. Sector 34 or Park View Residency">
								<span class="detect" onclick="showPosition()">Detect</span>
                            </div>
                        </div>

                    <div class="new-address-inner">
                        <div class="new-address-form-row">
                            <p>Name</p>
                            <div class="form-group d-flex justify-content-start align-items-center">
                                <select name="title" class="form-control" id="title">
                                    <option value="Mr.">Mr.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Miss">Miss</option>
                                </select>
                                <input type="text" class="form-control" name="name" id="name" placeholder="First & Last Name">
                            </div>
                        </div>
						
                        <div class="new-address-form-row">
                            <p>Flat / House / Office No.</p>
                            <div class="form-group d-flex justify-content-start align-items-center">
                                <input type="text" class="form-control" name="house" id="house" placeholder="Flat / House / Office No">
                            </div>
                        </div>
                        <div class="new-address-form-row">
                            <p>Street / Society / Office Name</p>
                            <div class="form-group d-flex justify-content-start align-items-center">
                                <input type="text" class="form-control"  name="street" id="street" placeholder="Street / Society / Office Name">
                            </div>
                        </div>
                        <div class="new-address-form-row">
                            <p>Mobile Number</p>
                            <div class="form-group d-flex justify-content-start align-items-center">
                                <input type="number" class="form-control"  name="mobile" id="mobile" placeholder="mobile number">
                            </div>
                        </div>
                        <div class="new-address-form-row">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="customHome" name="type" value="home">
                                <label class="custom-control-label" for="customHome">Home</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="customOffice" name="type" value="office">
                                <label class="custom-control-label" for="customOffice">Office</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" class="custom-control-input" id="customOthers" name="type" value="other">
                                <label class="custom-control-label" for="customOthers">Others</label>
                            </div>
                        </div>
                        <div class="mt-3">
                            <button id="address_add" class="btn new-delivery-address__btn flush--left" type="button">Continue</button>
                            <button class="btn btn--inverted-gray new-delivery-address__btn ml-3" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>
<div class="modal" id="myAddressEdit">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="p-3 text-center address-header">
                <h5 class="modal-title">Edit Delivery Address</h5>
                <p class="m-0">Please enter the accurate address, it will help us to serve you better</p>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form name="address_edit_form" action="#" id="address_edit_form">
                <!-- Modal body -->
                <div class="modal-body" id="address_body">

                </div>
            </form>

        </div>
    </div>
</div>


</div>
@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBXeEpNyvOxirxB38hoys2_U7lTvQllS9g"></script>
 <script>
   
		initialize();
		
	var autocomplete;
	function initialize() {
	 var options = {
					  types: ['geocode'],
					  componentRestrictions: {country: "ind"}
					 };
              autocomplete = new google.maps.places.Autocomplete((document.getElementById('address')),options);
	  google.maps.event.addListener(autocomplete, 'place_changed', function() {
	                    var place = autocomplete.getPlace();
						localStorage.setItem('longitude',place.geometry.location.lng());
						localStorage.setItem('lattitude',place.geometry.location.lat());
	  });
	}
</script>

<script src="{{ URL::asset('public/js/developer/checkout.js') }}"></script>
<script>

    $(document).ready(function(){
        $('input[name="wallet-check"]').click(function(){
            var delivery_date=localStorage.getItem('date');
            var delivery_type=localStorage.getItem('delivery_type');
            var time=$("input[name='time']:checked").val();
            if($(this).prop("checked") == true){
                var address=localStorage.getItem('address');
                if(address==null){
                    $('.address-error').show();
                    $('html, body').animate({
                        scrollTop: $(".checkout-step").offset().top
                    }, 1000);
                    return false;
                }
                $('.address-error').hide();
                if(delivery_type == 'standard'){
                    if(delivery_date==null || time == null){
                        $('.address-error').hide();
                        $('.date-time-error').show();
                        $('html, body').animate({
                            scrollTop: $(".tab-content").offset().top
                        }, 1000);
                        return false;
                    }
                }
                $('.date-time-error').hide();
                $(".grocito-wallet").css("display","block");
                var total_sum=parseInt($("#total_sum").val());
                var wallet_amount= parseInt($("#wallet_amount").val());

                if(wallet_amount >= total_sum)
                {
                    var value= wallet_amount - total_sum;
                    $(".sub_total").text(0);
                    $(".apply_wallet").text(total_sum);
                    $("#withdraw_wallet_amount").val(total_sum);
                    $('.paytm-option').hide();
                }
                if(wallet_amount < total_sum){
                    var value= total_sum-wallet_amount;
                    $(".sub_total").text(value);
                    $(".apply_wallet").text(wallet_amount);
                    $("#withdraw_wallet_amount").val(wallet_amount);
                }

            }
            else if($(this).prop("checked") == false){
                $(".grocito-wallet").css("display","none");
                var item_total=$("#total_sum").val();
                $(".sub_total").text(item_total);
                $("#withdraw_wallet_amount").val(0);
                $('.paytm-option').show();
            }
        });
    });


    $('#gallery-box').hide();
    // fetch the id name of checked delivery
    var idName = $('input.address:checked').attr('id');
    // display the metabox if the fetched value matched
    if ( idName === 'customOthers' ) {
        $('#gallery-box').show();
    }
    // show or hide
    $('.address').click(function(){
        idName = $(this).attr('id');
        if ( $(this).is(':checked') ) {
            if ( idName === 'customOthers' ) {
                $('#gallery-box').show();
            } else {
                $('#gallery-box').hide();
            }
        }
    });
    $(function() {
        $('input[name=address]').on('click init-address', function() {
            $('#gallery-box').toggle($('#customOthers').prop('checked'));
        }).trigger('init-address');
    });
</script>
<!--<script>
    $(function() {
        var $radioButtons = $('input.radioput');
        $radioButtons.click(function() {
            $radioButtons.each(function() {
                $(this).parent().parent().toggleClass('checked11', this.checked);
            });
        });
    });
</script>-->
<script type="text/javascript">
    $(function () {
        $("#shipCart").click(function () {
            if ($(this).is(":checked")) {
                $("#shippBlock").hide();

            } else {
                $("#shippBlock").show();

            }
        });
    });
</script>

<script>
        $(function() {
    var $radioButtons = $('input.radioput');
    $radioButtons.click(function() {
        $radioButtons.each(function() {
            $(this).parent().parent().toggleClass('checked11', this.checked);
        });
    });
});
</script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    function razorpayPayment(amount,order_id) {

        var SITEURL = '{{URL::to('')}}';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

            var totalAmount = amount;
            var order_id = order_id;
            var options = {
                "key": "rzp_live_3Psac1T1qEddGl",
                "amount": (totalAmount * 100), // 2000 paise = INR 20
                "name": "Grocito",
                "description": "Payment",
                "image": '<?php echo asset('public/images/logo2.svg')?>',
                "handler": function (response) {

                    $(".loader-div").show();
                    $.ajax({
                        url: SITEURL + '/razorpay-success',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            razorpay_payment_id: response.razorpay_payment_id,
                            totalAmount: totalAmount, order_id: order_id,
                        },
                        success: function (data) {
                            if(data.success)
                            {
                                if(data.success_code== 1)
                                {
                                    jQuery('.loader').hide();
                                    location.replace(SITEURL+'/razorpay-success');
                                }



                            }else
                            {
                                if(data.success_code== 0)
                                {
                                    jQuery('.loader').hide();
                                    location.replace(SITEURL+'/razorpay-faild');
                                }
                            }
                            $(".loader-div").hide();
                           /// window.location.href = SITEURL + '/razorpay-success';
                        }
                    });

                },
                "modal": {
                    "ondismiss": function(){
                        window.location.replace(SITEURL+'/razorpay-faild?orderId='+order_id);
                    }
                },
                "prefill": {
                    "contact": '<?php echo $userMobile?>',
                    "email": 'tutsmake@gmail.com',
                },
                "theme": {
                    "color": "#528FF0"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();

    }
   /* $("#modal-close").click(function(){
        $(this).removeClass("close");
        alert("The paragraph was clicked.");
        return false;
    });*/
    /*document.getElementsClass('buy_plan1').onclick = function(e){
     rzp1.open();
     e.preventDefault();
     }*/
</script>
<style>
    .new-address-form-row label.error {
        position: absolute;
        font-size: 12px;
        bottom: -10px;
        margin: 0;
    }
    #myAddress .form-group {
        position: relative;
        padding-bottom: 10px;
    }
    .new-address-form-row .custom-radio label.error {
        width: 200px;
        left: 0;
        bottom: -15px;
    }


</style>

@stop
@endSection