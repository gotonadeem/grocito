@extends('front.layout.front')
@section('content')
<section class="customer-services mb-5">
  <div class="container my-3">
    <ul class="breadcrumb justify-content-start px-0">
      <li class="breadcrumb-item"><a href="#">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Clothing</a></li>
      <li class="breadcrumb-item active">Shirt</li>
    </ul>
  </div>
  <div class="container">
    <h2 class="title-heading">Customer Service</h2>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-3 col-sm-4 col-6">
        <div class="box-services">
          <h4>Return &amp; Exchange</h4>
          <div class="service-icon"> <img src="{{ URL::asset('public/front/images/service-icon/return.png') }}" class="img-fluid"> </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-4 col-6">
        <div class="box-services">
          <h4>Shipping</h4>
          <div class="service-icon"> <img src="{{ URL::asset('public/front/images/service-icon/trolley.png') }}" class="img-fluid"> </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-4 col-6">
        <div class="box-services">
          <h4>Order &amp; Payment</h4>
          <div class="service-icon"> <img src="{{ URL::asset('public/front/images/service-icon/order.png') }}" class="img-fluid"> </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-4 col-6">
        <div class="box-services">
          <h4>Cancellation</h4>
          <div class="service-icon"> <img src="{{ URL::asset('public/front/images/service-icon/return.png') }}" class="img-fluid"> </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-4 col-6">
        <div class="box-services">
          <h4>Sale / Discount </br>
            Product Policy</h4>
          <div class="service-icon"> <img src="{{ URL::asset('public/front/images/service-icon/bargain.png') }}" class="img-fluid"> </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-4 col-6">
        <div class="box-services">
          <h4>FAQS</h4>
          <div class="service-icon"> <img src="{{ URL::asset('public/front/images/service-icon/support.png') }}" class="img-fluid"> </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-4 col-6">
        <div class="box-services">
          <h4>Coupons &amp; Gift Cards</h4>
          <div class="service-icon"> <img src="{{ URL::asset('public/front/images/service-icon/voucher.png') }}" class="img-fluid"> </div>
        </div>
      </div>
      <div class="col-md-3 col-sm-4 col-6">
        <div class="box-services">
          <h4>Garmets</h4>
          <div class="service-icon"> <img src="{{ URL::asset('public/front/images/service-icon/dress.png') }}" class="img-fluid"> </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection