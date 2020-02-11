@extends('seller.layouts.seller')

@section('content')

<div id="rightSidenav" class="right_side_bar">


<div class="container-fluid">
  
  <div class="panel panel-default custom-margin">
    <div class="panel-body">
    <div class="pull-left">
	<A href="{{url()->previous()}}" class="btn btn-primary">Back</a>
    Order ID : <span>{{$order_info->order_id}}</span>
    </div>
    <div class="pull-right">
    Total Item : <span><?=Helper::get_number_of_product($order_info->	id,Auth::user()->id);?></span>
    </div>
    </div>
  </div>
</div>

<div class="container-fluid">
	  <?PHP $sum=0; ?>
	  @foreach($orders as $vs)
	  <div class="panel panel-default">
	  <div class="page-header page-header-custom">
		<h5 class="prodct-text">Product</h5>      
	  </div>
		<div class="panel-body">
		<div class="col-md-1">
		<span class="img-inline-img odr-details"><img id="myImg_<?=$vs->id?>" onclick="zoom_image(this.id)" class="img-responsive image-zoom" src="{{ URL::asset('public/admin/uploads/product') }}/{{$vs->product_image}}" alt="{{$vs->product_image}}"></span>
		</div>
		<div class="col-md-7">
		<h5 class="title-product">{{$vs->product_name}}</h5>
		<ul class="list-group">
		<li class="itemd price">Price : <span><i class="fa fa-inr" aria-hidden="true"></i> {{$vs->price}} </span></li>
		<li class="itemd qty">QTY : <span>{{$vs->qty}}</span></li>
		<li class="itemd size">Weight : <span>{{$vs->weight}}</span></li>
		<li class="itemd size">Status : <span>{{$vs->status}}</span></li>
	  </ul>
		</div>
		<div class="col-md-4">
		<div class="order-btn">
		<!--<p><button type="button" class="btn trck">Order Track</button></p>
		<p><button type="button" class="btn cancel-odr">Cancel Order</button></p>-->
		</div>
		</div>
		
		</div>
	  </div>
	  <?PHP
	  $sum= $sum+ ($vs->price * $vs->qty);
	  ?>
	  @endforeach
</div>

<div class="container-fluid">
<div class="row">
<div class="col-md-6">
<div class="panel panel-default">
  <div class="page-header page-header-custom">
    <h5 class="prodct-text">Price</h5>      
  </div>
    <div class="panel-body">
    <div class="col-md-12">
    <div class="table-responsive">
     <table class="table table-custom">
    
    <tbody>
      <tr>
        <td>Product Charges</td>
        <td><i class="fa fa-inr" aria-hidden="true"></i><span>{{$sum}}</span></td>
        
      </tr>
      <tr>
        <td>Shipping Charges</td>
        <td><i class="fa fa-inr" aria-hidden="true"></i><span>{{$order_info->shipping_charge}}</span></td>
        
      </tr>
       <tr>
        <td class="border-top"><b>Total Amount</b></td>
        <td class="border-top"><i class="fa fa-inr" aria-hidden="true"></i><span><b><?=$sum+$order_info->shipping_charge?></b></span></td>
        
      </tr>
    </tbody>
  </table>
  </div>
    </div>
 
    </div>
  </div>
</div>
<div class="col-md-6">
<div class="panel panel-default">
  <div class="page-header page-header-custom">
    <h5 class="prodct-text">Shipping</h5>      
  </div>
    <div class="panel-body">
    <div class="col-md-12">
    <div class="shipping-filed">
        <?php

        $createdAt = $order_info->created_at;
        if($order_info->express_time){
            $Minutes = $order_info->express_time;
            $deliveryTime = date("h:i:s a", strtotime($createdAt)+($Minutes*60));
        }
        ?>
    <p>Delivery Type: <span>{{$order_info->delivery_type }} </span></p>
    <p>Delivery Date: <span>{{$order_info->delivery_date }} </span></p>
    <p>Delivery Time: <span>{{$order_info->delivery_time ? $order_info->delivery_time: $deliveryTime}} </span></p>
    <p>Payment Mode : <span>{{$order_info->payment_mode}}</span></p>
    </div>
    </div>
 
    </div>
  </div>
</div>
</div>
<div class="row">
<div class="col-md-6">
<div class="panel panel-default">
  <div class="page-header page-header-custom">
    <h5 class="prodct-text">Shipping Details</h5>      
  </div>
    <div class="panel-body">
    <div class="col-md-12">
    <div class="shipping-details">
    <h5 class="customer-name">{{$order_info->address->name}}</h5>
    <p>{{$order_info->address->type}}</p>
    <p>{{$order_info->address->house}}, {{$order_info->address->street}} ,{{$order_info->address->address}}</p>
    <p>{{$order_info->address->city}}</p>
    <p>{{$order_info->address->state}}<b> {{$order_info->address->pincode}}</b></p>
    </div>
    </div>
 
    </div>
  </div>
</div>
</div>
</div>

</div>

<script>

    function openTab(evt, tabName) {

        var i, tabcontent, tablinks;

        tabcontent = document.getElementsByClassName("tabcontent");

        for (i = 0; i < tabcontent.length; i++) {

            tabcontent[i].style.display = "none";

        }

        tablinks = document.getElementsByClassName("tablinks");

        for (i = 0; i < tablinks.length; i++) {

            tablinks[i].className = tablinks[i].className.replace(" active", "");

        }

        document.getElementById(tabName).style.display = "block";

        evt.currentTarget.className += " active";

    }



    // Get the element with id="defaultOpen" and click on it

    document.getElementById("defaultOpen").click();

</script>
<script>

    $( function() {

        $( "#datepicker" ).datepicker();

    } );

</script>
@include('seller.order.zoom_image')
@endsection