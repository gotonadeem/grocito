<?PHP $page=array("dashboard","payment","catalog","payment-details"); ?>
<div class="header col-md-12 <?PHP if(!in_array(Request::segment(2),$page)): echo "show_only_mob";  endif; ?>">
    <div class="col-md-3 col-sm-3 col-xs-4">
        <div class="saleplus-logo-seller">
<img class="logo" src="{{ URL::asset('public/admin/img/logo.png') }}">
        	<i class="fa fa-bars" aria-hidden="true" onclick="myFunction_nav()"></i> </div>
    </div>
    <div class="col-md-9 col-sm-9 col-xs-8">
        <div class="pull-right">
            <div class="col-xs-4"> 
			@if(Auth::user())
				@if($user_info['seller_image'])	
				<img class="img-responsive dropbtn" src="{{ URL::asset('public/admin/uploads/seller/'.$user_info['seller_image']) }}" onclick="myFunction_drop()"> 
				 @else
				 <img class="img-responsive dropbtn" src="{{ URL::asset('public/front/image/user.png') }}" onclick="myFunction_drop()">
				 @endif
			@else
		    <img class="img-responsive dropbtn" src="{{ URL::asset('public/front/image/user.png') }}" onclick="myFunction_drop()">
			@endif
			</div>
			<div class="text_profile col-xs-8">
                <p class="title-bar dropbtn" onclick="myFunction_drop()">{{Auth::user()->username}}</p>
                <p class="text-info dropbtn" onclick="myFunction_drop()">{{Auth::user()->email}}</p>
            </div>
            <div id="myDropdown" class="dropdown-content">
			    @if(Auth::user()->verify_status=="verified")
                <!--<a href="{{ URL::to('/seller/setting') }}">Setting</a>-->
                <a href="{{ URL::to('/seller/change-password') }}">Change Password</a>
				 <a href="{{ URL::to('/seller/aggreement') }}">Agreement</a>
               @else
			    <a href="{{ URL::to('/seller/complete-profile') }}">Profile</a>               
				@endif
				 <a href="{{ URL::to('/seller/logout') }}">Logout</a>
            </div>
        </div>
		<div class="pull-right">
			<div class="col-xs-2">
			<div class="dropdown notifaction">
				<button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" onclick="update_notify_view_status('{{Auth::user()->id}}')">
					<i class="fa fa-bell" ></i>
					<?php if($notify_count > 0){?>
				<span class="notify_count">{{$notify_count}}</span>
					<?php }?>
				</button>
				<div class="dropdown-menu dropdown-menu-form">
					<?php
					if(!$sellerNotification->isEmpty()){

					?>
					@foreach($sellerNotification as $vs)
						<?php
							$title = '';
							if($vs->type =='product_verify'){
							$productData = Helper::getProductById($vs->int_val);
								if($productData){
									$title = $productData->name;
								}else{
									$title = '';
								}

							}
							if($vs->type =='order_placed'){
								$orderData = Helper::getOrderById($vs->int_val);
								if($orderData){
									$title = $orderData->order_id;
								}else{
									$title ='';
								}

							}?>


					<p class="dropdown-item">{{$title}} {{$vs->message}}
						<span><b>Date:</b>{{date('d-m-Y h:m:i',strtotime($vs->created_at))}}</span>
					</p>
					@endforeach
					<?php }else {  ?>
						<p class="dropdown-item">No data available</p>
				<?php		}?>

				</div>
				</div>
			</div>
		</div>
    </div>
</div>
<script>

	function update_notify_view_status(sellerId) {

				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					url: BASE_URL+'/seller/update-notify-view-status',
					type: 'POST',
					data: {sellerId: sellerId},
					success: function (data) {
						response= JSON.parse(data);
						if(response.status)
						{
							$('.notify_count').hide();
						}
						else
						{
							//alert("Please Try Again");
						}
					},
					error: function () {
						console.log('There is some error. Please try again.');
					}
				});


	}
	$("document").ready(function() {

	$('.dropdown-menu').on('click', function(e) {
		if($(this).hasClass('dropdown-menu-form')) {
			e.stopPropagation();
		}
	});
	});
</script>
