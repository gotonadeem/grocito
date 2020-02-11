@extends('front.layout.front')
@section('content')
<section class="order-sucess order-fail my-5">
  <div class="container">
  <div class="order-div text-center">
    <div class="success-img"><img src="{{ URL::asset('public/images/cancel-cross.png') }}" class="img-fluid"></div>
    <h1>Order Fail</h1>
    <h5>Your order is unsuccesfully</h5>
    <h5>for more details check my order page</h5>
    <h5>your Order ID is</h5>
    <h5>{{$order_details->order_id}}</h5>
    <div class="continue-shop my-5">
      <a href="{{URL::to('/')}}">
    <button class="btn custom-btn">Continue Shopping <span><img src="{{ URL::asset('public/images/left-right.png') }}" class="img-fluid"></span></button>
    </a> 
	</div>
    </div>
  </div>
</section>
@endSection