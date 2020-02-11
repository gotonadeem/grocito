@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar">
   

    <div id="pending" style="display:block;" class="tabcontent">
        <div class="download_exel">
            <div class="padding-bottom-10 text-center">
                <div>
                    <!--<button class="notice-link" id="accept_all_order">Accept All Order</button>-->
                    <button class="gst-file-download-button">Download Invoice Details Excel</button>
                    <a class="notice-link" href="{{URL::to('seller/notice')}}"><span class="title">Notices</span></a></div>
            </div>
        </div>
         <!--<div class="check-box-big">
            <label>
                <input type="checkbox" name="selAllPending" id="selAllPending" value="on">
                <span class="">Select All Orders</span></label>
        </div>-->
		<div> @if(Session::has('success_message'))
        <p class="alert alert-info">{{ Session::get('success_message') }}</p>
        @endif</div>
        @foreach($orders as $vs)
        <div class="order-view">
            <div>
                <div class="order-view-products">
                    <div class="order-product relative checkbox-present">
                        <div class="suborder-checkbox">
                            <div class="check-box-big">
                                <label>
                                     <!--<input type="checkbox" name="isSelected" class="isSelected" value="{{$vs->order_id}}">-->
                                </label>
                            </div>
                        </div>
                        <div class="border relative">
                            <div class="flex-container clearfix">
                            
                                <div class="order-product-image cursor-pointer">
								<?PHP 
								foreach(Helper::get_order_image($vs->order_id,Auth::user()->id,'pending') as $ks=>$image):
								?>
                                <span class="img-inline-img"><img id="myImg_<?=$ks?>_<?=$vs->order->id; ?>" onclick="zoom_image(this.id)" src="{{ URL::asset('public/uploads/seller/catalog') }}/{{$image}}" alt="{{$image}}" class="image-zoom"></span>
                               <?PHP endforeach; ?>   
								</div>
                                <div class="order-product-info">
                                    <div class="order-div"><label>Order id :</label><span><?php echo !is_null($vs->order)?$vs->order->order_id:''; ?></span></div>
                                    <div class="order-div"><label>Order Date :</label> <span><?=date('d-M-Y,h:i a', strtotime($vs->created_at))?></span></div>
                                    <div class="order-div"><label>Number Of Products :</label> <span><?=Helper::get_number_of_product((!is_null($vs->order)?$vs->order->id:''),Auth::user()->id,'pending');?></span></div>
                                    <div class="order-div"><label>Total Amount :</label> <span>Rs. <?=!is_null($vs->order)?$vs->order->payment_amount+$vs->order->shipping_charge+$vs->order->extra_amount:''?> </span></div>
                                    <div class="order-div product-quantity"><label>Qty:</label> <span><?=Helper::get_number_of_qty((!is_null($vs->order)?$vs->order->id:''),Auth::user()->id,'pending');?></span></div> 
                                      <div class="inline-block product-view-address"><button class="address-view-button box-shadow" onclick="display_address(<?=(!is_null($vs->order)?$vs->order->id:'')?>)">View Address</button></div> 
									  <div class="inline-block product-view"><a href="{{URL::to('seller/order-details')}}/{{!is_null($vs->order)?$vs->order->id:''}}">View Details</a></div>
                                        
                                </div>
                                
                            </div>
                            <div class="order-product-cta-block relative clearfix">
                                <div>
                                    <button class="order-product-cta order-ship-product button-shadow button_width_50" type="button" onclick="get_order_id(this.id)" id="{{!is_null($vs->order)?$vs->order->id:''}},{{!is_null($vs->order)?date('d-m-Y',strtotime('+10 days', strtotime(date('d-M-Y,h:i a', strtotime($vs->order->created_at))))):''}}" data-toggle="modal" data-target="#myModal"> Accept Order </button>
                                    <button class="order-product-cta order-cancel-product button-shadow button_width_50" type="button" onclick="cancel_order_id(<?=!is_null($vs->order)?$vs->order->id:''?>)" > Cancel Order </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		@endforeach
    </div>
</div>
<!--Modal -->
@include('seller.order.expected_date_popup')
@include('seller.order.expected_date_popup_all_order')
@include('seller.order.cancel_popup')
@include('seller.order.address_popup')
<!-- Modal -->
<script>
$("#expected_date").click(function()
{
	var date= $(".expected_date").val();
	var order_id= $("#order_id").val();
	 $(".loader_div").show();
	   $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/order-estimate-date',
        type: 'POST',
        data: {date: date,order_id: order_id},
        success: function (data) {
			var response= JSON.parse(data);
			  $(".loader_div").hide();
			if(response.not_login)
			{
				location.replace(BASE_URL+"/login");
			}
			if(response.status)
			{
				location.replace(BASE_URL+"/seller/order");
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
	 
function order_cancel()
{
	var id=$("#order_value").val();
	   $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/cancel-order',
        type: 'POST',
        data: {id: id },
        success: function (data) {
          var response= JSON.parse(data);
			if(response.not_login)
			{
				location.replace(BASE_URL+"/login");
			}
			if(response.status)
			{
				location.replace(BASE_URL+"/seller/order");
			}
        },
        error: function () {
            console.log('There is some error in user deleting. Please try again.');
        }
       });
}	
	
function get_order_id(value)
{
	value1= value.split(",");
	//alert(value1[1]);
	$("#datepicker").datepicker("destroy");
	 $( "#datepicker" ).datepicker({
			minDate: 0, maxDate: value1[1],
			altFormat: "dd-mm-yy", 
			dateFormat: 'dd-mm-yy'
		});
	$("#order_id").val(value1[0]);
}
function cancel_order_id(value)
{
	$("#order_value").val(value);
	$("#cencel_model").modal('show');
}
	
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
    //document.getElementById("defaultOpen").click();
</script>
<script>
	$(document).ready(function(){
        $('input[name="selAllPending"]').click(function(){
            if($(this).prop("checked") == true){
                $('.isSelected').prop("checked", true);
            }
            else if($(this).prop("checked") == false){
               $('.isSelected').prop("checked", false);
            }
        });
    });
	$("#accept_all_order").click(function()
	{
	
		var value = [];
        $('.isSelected:checked').each(function(i){
          value[i] = $(this).val();
        });
		
		console.log(value);
		if(value.length>0)
		{
		$("#all_order_id").val(value);
		$("#myModal_all_order").modal("show");
		}		
	});
	
	$("#expected_date_all").click(function()
	{
		var date= $(".expected_date2").val();
	    var order_id= $("#all_order_id").val();
	   $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: BASE_URL+'/seller/order-all-estimate-date',
        type: 'POST',
        data: {date: date,order_id: order_id},
        success: function (data) {
			//console.log(data);
			
			var response= JSON.parse(data);
			if(response.not_login)
			{
				location.replace(BASE_URL+"/login");
			}
			if(response.status)
			{
				location.replace(BASE_URL+"/seller/order");
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
	   
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="javascript" src="{{ URL::asset('public/front/developer/js/validation_js/order.js') }}"></script>
<script type="javascript" src="{{ URL::asset('public/front/developer/js/page_js/order.js') }}"></script>
@include('seller.cancellation_risk.zoom_image')
@endsection