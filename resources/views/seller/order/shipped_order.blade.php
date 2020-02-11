@extends('seller.layouts.seller')
@section('content')
<div id="rightSidenav" class="right_side_bar">
    <div class="tab">
       
		<a  href="{{URL::to('seller/order')}}"><button class="tablinks"  id="defaultOpen">Pending</button></a>
        <a  href="{{URL::to('seller/assign-to-rider')}}"><button class="tablinks" >Assign To Rider</button></a>
        <a  class="focus" href="{{URL::to('seller/delivered-order')}}"><button class="tablinks">Delivered</button></a>
        <a  href="{{URL::to('seller/cancelled-order')}}"><button class="tablinks">Cancelled</button></a>
        <a  href="{{URL::to('seller/return-order')}}"><button class="tablinks">Return</button></a>
        <a  href="{{URL::to('seller/exchange-order')}}"><button class="tablinks">Exchange</button></a>
		
    </div>
	
  
    <div id="shipped" style="display:block" class="tabcontent">
        <div class="download_exel">
            <div class="padding-bottom-10 text-center">
                   <div>
                    <!--<button class="gst-file-download-button">Download Invoice Details Excel</button>-->
                    <a href="{{URL::to('seller/notice')}}" class="notice-link"><span class="title">Notices</span></a>
					</div>
            </div>
        </div>
        <div class="order-list clearfix">
             @foreach($orders as $vs)
            <div class="group-view relative">
                <div class="group-view-button clearfix">
                    <!--<a href="{{URL::to('/seller/download-invoice-shipment/')}}/<?=$vs->order->id?>" target="_blank" class="printLabelLink button-shadow">
                        <i class="fa fa-download" aria-hidden="true"></i>Label
                    </a> -->
                    <div class="carrier-name pull-right"><?=$vs->order->shipped_by?></div>
                    <div class="carrier-name pull-left"><?=($vs->status=="delivered")?"<span style='color:green'>Delivered</span>":"<span style='color:green'>Delivered</span>";?></div>
                </div>
                <div class="group-order">
                    <div class="order-view-order-info">              
                        <div class="group-suborders">
                            <div class="group-suborder">
                                <div class="flex-container clearfix border relative">
                                    
                                    <div class="order-product-image cursor-pointer">
                                    
                                    <?PHP 
                                    foreach(Helper::get_shipped_order_image($vs->order_id,Auth::user()->id,array('delivered','shipped')) as $ks=>$image):
                                    ?>
                                    <span class="img-inline-img"><img id="myImg_<?=$ks?>_<?=$vs->order->id; ?>" onclick="zoom_image(this.id)" src="{{ URL::asset('public/admin/uploads/product') }}/{{$image}}" alt="{{$image}}" class="image-zoom"></span>
                                <?PHP endforeach; ?> 
                                    </div>
                                    
                                    <div class="order-product-info">
                                        <div class="order-div"><label>Order id :</label><span>{{$vs->order->order_id}}</span></div>
                                        <div class="order-div"><label>Sub Order id :</label><span><?php echo $vs->sub_order_id; ?></span></div>
                                        <div class="order-div"><label>Order Date :</label> <span><?=date('d-M-Y,h:i a', strtotime($vs->created_at))?></span></div>
                                        <div class="order-div"><label>Number Of Products :</label> <span><?=Helper::get_number_of_product_by($vs->order->id,Auth::user()->id,'delivered');?></span></div>
                                        <div class="order-div"><label>Total Amount :</label> <span>Rs. <?=$vs->amount?></span></div>
                                        <div class="qty"><label>Qty:</label> <span><?=Helper::get_number_of_qty($vs->order->id,Auth::user()->id,'delivered');?></span></div>
                                        <div class="inline-block product-view-address positon-inhrit"><button class="address-view-button box-shadow" onclick="display_address(<?=$vs->order->id;?>)">View Address</button></div>
                                        <div class="inline-block product-view positon-inhrit"><a href="{{URL::to('seller/order-details')}}/{{$vs->order->id}}">View Details</a></div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
                                    </div>
			
			 <div class="payment-pagination text-center">

                            <div class="pagination-testing text-center">

							{{$orders->links()}}
                               

                            </div>

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
	
function get_order_id(value)
{
	$("#order_id").val(value);
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
    $( function() {
        $( "#datepicker" ).datepicker();
    } );
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="javascript" src="{{ URL::asset('public/front/developer/js/page_js/order.js') }}"></script>
<script type="javascript" src="{{ URL::asset('public/front/developer/js/validation_js/order.js') }}"></script>
@include('seller.order.zoom_image')
@endsection