@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar">
    <div class="tab">
        <a  href="{{URL::to('seller/order')}}"><button class="tablinks"  id="defaultOpen">Pending</button></a>
        <a class="focus" href="{{URL::to('seller/assign-to-rider')}}"><button class="tablinks" >Assign To Rider</button></a>
        <a href="{{URL::to('seller/delivered-order')}}"><button class="tablinks">Delivered</button></a>
        <a href="{{URL::to('seller/cancelled-order')}}"><button class="tablinks">Cancelled</button></a>
        <a href="{{URL::to('seller/return-order')}}"><button class="tablinks">Return</button></a>
        <a href="{{URL::to('seller/exchange-order')}}"><button class="tablinks">Exchange</button></a>
    </div>

    <div id="tobe" style="display:block;" class="tabcontent">
        <div class="download_exel">
            <div class="padding-bottom-10 text-center">
                <div>
				    <!--<button class="notice-link" id="ready_to_ship_all">Ready To Ship All</button>-->
                    <!--<button class="gst-file-download-button">Download Invoice Details Excel</button>-->
                    <a href="{{URL::to('seller/notice')}}" class="notice-link"><span class="title">Notices</span></a></div>
            </div>
        </div>

        {{--<div class="check-box-big">
            <label>
                <input type="checkbox" name="selAllToBeDispatched" value="on">
                <span class="">Select All Orders</span></label>
        </div>--}}
		<div> @if(Session::has('success_message'))
        <p class="alert alert-info">{{ Session::get('success_message') }}</p>
        @endif</div>
        <div class="order-list clearfix">
        @foreach($orders as $vs)
        <div class="order-view">
            <div>
            
                <div class="order-view-products">
                    <div class="order-product relative today-shipping checkbox-present ">
                       {{-- <div class="suborder-checkbox">
                            <div class="check-box-big">
                                <label>
                                    <input type="checkbox" class="isSelected" name="isSelected" value="{{$vs->order_id}}">
                                </label>
                            </div>
                        </div>--}}
                        <div class="border relative">

                            <div class="flex-container clearfix">

                                <div class="order-product-image cursor-pointer">
								<?PHP 
								foreach(Helper::get_order_image($vs->order_id,Auth::user()->id,'assign_to_rider') as $ks=>$image):
								?>
                                <span class="img-inline-img"><img id="myImg_<?=$ks?>_<?=$vs->order->id; ?>" onclick="zoom_image(this.id)" src="{{ URL::asset('public/admin/uploads/product') }}/{{$image}}" alt="{{$image}}" class="image-zoom"></span>
                               <?PHP endforeach; ?>  
                                </div>                               
							   <div class="order-product-info">
                                    
									<div class="order-div"><label>Order id :</label><span>{{$vs->order->order_id}}</span></div>
									<div class="order-div"><label>Sub Order id :</label><span><?php echo $vs->sub_order_id; ?></span></div>
                                    <div class="order-div"><label>Order Date :</label> <span><?=date('d-M-Y,h:i a', strtotime($vs->order['created_at']))?></span></div>
                                    <div class="order-div"><label>Number Of Products :</label> <span><?=Helper::get_number_of_product($vs->order->id,Auth::user()->id);?></span></div>
                                    <div class="order-div"><label>Total Amount :</label> <span>Rs. <?=$vs->amount?></span></div>
                                    <div class="qty"><label>Qty:</label> <span><?=Helper::get_number_of_qty($vs->order->id,Auth::user()->id,'assign_to_rider');?></span></div> 
                                    <div class="inline-block product-view-address positon-inhrit"><button class="address-view-button box-shadow" onclick="display_address(<?=$vs->order->id;?>)">View Address</button></div>
									<div class="inline-block product-view positon-inhrit"><a href="{{URL::to('seller/order-details')}}/{{$vs->order->id}}">View Details</a></div>
                                     
									
                                </div>
                            </div>
                            <!--<div class="order-product-cta-block relative clearfix">
                                <div>
                                    <button onclick="ready_to_ship(<?=$vs->order->id;?>)" class="order-product-cta order-ship-product button-shadow button_width_50">Ready to Ship</button>
                                    <button class="order-product-cta order-cancel-product button-shadow button_width_50" type="button" class="btn btn-info btn-lg" onclick="get_order_id(<?=$vs->order->id;?>)" data-toggle="modal" data-target="#myModal">
                                        Change Dispatch Date
                                    </button>
                                </div>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        </div>
    </div>
	
</div>
<!--Modal -->
@include('seller.order.expected_date_popup')
@include('seller.order.cancel_popup')
@include('seller.order.address_popup')
   
<!-- Modal -->
<script>
$("#expected_date").click(function()
{
	var date= $(".expected_date").val();
	var order_id= $("#order_id").val();
	   $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/order-estimate-date',
        type: 'POST',
        data: {date: date,order_id: order_id},
        success: function (data) {
			var response= JSON.parse(data);
			if(response.not_login)
			{
				location.replace(BASE_URL+"/login");
				;
			}
			if(response.status)
			{
				location.replace(BASE_URL+"/seller/to-be-dispatched");
			}
			else
			{
               $("#order_date_msg").html("Please try again");
			}
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
       });
	   
});

 function display_address(id)
	 {
		   $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/get-order-address',
        type: 'POST',
        data: {id: id },
        success: function (data) {
           $("#address_details").html(data);
           $("#view_address").modal('show');
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
       });
	 }
	
function get_order_id(value)
{
	$("#order_id").val(value);
}	

function ready_to_ship(value)
{
	$(".loader_div").show();
	   $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/order-ready-to-ship',
        type: 'POST',
        data: {order_id: value},
        success: function (data) {
			$(".loader_div").hide();
			var response= JSON.parse(data);
			if(response.not_login)
			{
				location.replace(BASE_URL+"/login");
			}
			if(!response.status)
			{
					$(".loader_div").hide();
					bootbox.alert(response.message).find('.modal-content').css({'background-color': '#f99', 'font-weight' : 'bold', color: '#F00', 'font-size': '1em', 'font-weight' : 'bold'} );;
					
			}
			if(response.status)
			{
				//alert("done")
				location.replace(BASE_URL+"/seller/to-be-dispatched");
			}
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
       });
}
       $(document).ready(function(){
        $('input[name="selAllToBeDispatched"]').click(function(){
			
            if($(this).prop("checked") == true){
                $('.isSelected').prop("checked", true);
            }
            else if($(this).prop("checked") == false){
               $('.isSelected').prop("checked", false);
            }
        });
    });

$("#ready_to_ship_all").click(function()
	{
		var value = [];
        $('.isSelected').each(function(i){
          value[i] = $(this).val();
        });
		
		if(value.length>0)
		{
		         $.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					url: BASE_URL+'/seller/order-all-ready-to-ship',
					type: 'POST',
					data: {order_id: value},
					success: function (data) {
						var response= JSON.parse(data);
						if(response.not_login)
						{
							location.replace(BASE_URL+"/login");
						}
						if(response.status)
						{
							location.replace(BASE_URL+"/seller/to-be-dispatched");
						}
					},
					error: function () {
						console.log('There is some error in user deleting. Please try again.');
					}
				   });
		}
	   
	});
    
</script>
<script>
    $( function() {
        $( "#datepicker" ).datepicker();
    } );
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>

<script type="javascript" src="{{ URL::asset('public/front/developer/js/page_js/order.js') }}"></script>
<script type="javascript" src="{{ URL::asset('public/front/developer/js/validation_js/order.js') }}"></script>
@include('seller.order.zoom_image')
@endsection