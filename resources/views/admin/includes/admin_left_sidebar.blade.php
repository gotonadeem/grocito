<?PHP
$url=Request::segment(3);
$userManagement=['user-list','seller-list','partner-list','unverified-seller-list','verified-seller-list','create-seller','/verified-seller-order','blocked-seller-list'];
$customerManagement=['customer-list','active-customer-list','inactive-customer-list','otp-customer-list'];
$merchantManagement=['inactive-merchant-list','active-merchant-list','merchant-list','merchant-commission'];
$agentManagement=['inactive-agent-list','active-agent-list','agent-list'];
$propertyManagement=['property-list','color','category-list','create-property','product-list','unverified-product-list','super-subcategory-list','subcategory-list','product-sponsor-list','size-list'];
$setting=['general-setting','faq-list','team-list','bank-details','add-agreement','joinus_cms'];
$front_management=['testimonials-list','news-list','news-create','testimonials-create'];
$home_management=['dip-list','slider-list'];
$orderManagement=['order-list','news-list','completed-order-list','cancelled-order-list','seller-manifest-list','return-exchange-order','incompleted-order-list'];
$giftManagement=['faq-list'];
$returnOrderManagement=['return_order'];
$exchangeOrderManagement=['exchange_order'];
$rating=['product/review-rating'];
$userData=Session::get('user_sdata');
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" src="{{ URL::asset('public/admin/img/logo.png') }}" />
                             </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">
							<?=(($userData->role==2)?"Subadmin":"Admin");
							?>
							</strong>
                             </span> <span class="text-muted text-xs block">Hi, <?=$userData->username;?><b class="caret"></b></span> </span> </a>
							 
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
					<?PHP if(Helper::check_permission($userData->id,'change_password')): ?>
					<li><a href="{{ URL::to('admin/change_password')}}">Change Password</a></li>
					<?PHP endif; ?>
						<?PHP if(Helper::check_permission($userData->id,'change_email')): ?>
						<li><a href="{{ URL::to('admin/change-email')}}">Change Email</a></li>
						<?PHP endif; ?>
                    <li><a href="{{ URL::to('admin/logout') }}">Logout</a></li>    
                    </ul>
                </div>
                <div class="logo-element">
                  CLICK
                </div>
            </li>
            <?PHP if(Helper::check_permission($userData->id,'dashboard')): ?> 
            <li class="active">
                <a href="{{ URL::to('admin/dashboard') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboards</span></a>
            
			</li>
			<?PHp endif; ?>
			
			<?PHP if(Helper::check_permission($userData->id,'subadmin')): ?> 
		   <li id="subadmin-menu">
			<a href="{{ URL::to('admin/subadmin/view-all-subadmin') }}"><i class="fa fa-users"></i> <span class="nav-label">Subadmin</span></a>
		   </li>
		   <?PHP endif; ?>
		   
			<?PHP if(Helper::check_permission($userData->id,'order')): ?> 
		    <li id="order1-menu" class="<?PHP if(in_array($url,$orderManagement)) { echo "active"; } ?>">
			<a href="javascript:void(0)"><i class="fa fa-first-order"></i> <span class="nav-label">Orders History</span> <span class="fa arrow"></span>  </a>
		       <ul class="nav nav-second-level collapse">
                     <?PHP if(Helper::check_permission($userData->id,'order')): ?>
					<li id="order-menu"><a href="{{ URL::to('admin/order/order-list') }}">Pending Orders</a></li>
					 <?php endif; ?>
					 <?PHP if(Helper::check_permission($userData->id,'completed_order')): ?>
                    <li id="completed-order-menu"><a href="{{ URL::to('admin/order/completed-order-list') }}">Completed Orders</a></li>         
                     <?php endif; ?>

					   <?PHP if(Helper::check_permission($userData->id,'cancelled_order')): ?>
					<li id="cancelled-order-menu"><a href="{{ URL::to('admin/order/cancelled-order-list') }}">Cancelled Orders</a></li>         
                       <?PHP endif; ?> 
					   
					   <?PHP if(Helper::check_permission($userData->id,'incompleted_order')): ?>
					<li id="incompleted-order-menu"><a href="{{ URL::to('admin/order/incompleted-order-list') }}">Incompleted Orders</a></li>         
                       <?PHP endif; ?>
					   

				</ul>
		   </li>
			<?Php endif; ?>
			@if(Helper::check_permission($userData->id,'order'))
				<li id="report-menu">
					<a href="javascript:void(0)"><i class="fa fa-file"></i> <span class="nav-label">Manage Order</span> <span class="fa arrow"></span> </a>
					<ul class="nav nav-second-level collapse">
						<li class="<?PHP if(in_array($url,$customerManagement)) { echo "active"; } ?>">
							<a href="{{URl::to('admin/order-management')}}"><i class="fa fa-bus"></i> <span class="nav-label">Pending</span></a>
						</li>
						<li class="<?PHP if(in_array($url,$customerManagement)) { echo "active"; } ?>">
							<a href="{{URl::to('admin/at-warehouse')}}"><i class="fa fa-bus"></i> <span class="nav-label">At Warehouse</span></a>
						</li>
						<li class="<?PHP if(in_array($url,$customerManagement)) { echo "active"; } ?>">
							<a href="{{URl::to('admin/assign-to-rider')}}"><i class="fa fa-bus"></i> <span class="nav-label">Assigned To Rider</span></a>
						</li>

						<li class="<?PHP if(in_array($url,$customerManagement)) { echo "active"; } ?>">
							<a href="{{URl::to('admin/delivered-order')}}"><i class="fa fa-bus"></i> <span class="nav-label">Delivered</span></a>
						</li>
					</ul>
				</li>
			@endif
			<li class="<?PHP if(in_array($url,$customerManagement)) { echo "active"; } ?>">
				<a href="{{URl::to('admin/order/return-approved')}}"><i class="fa fa-building-o"></i> <span class="nav-label">Return Orders</span></a>
			</li>

		
		   <?PHP if(Helper::check_permission($userData->id,'exchange_order')): ?>
			<li id="order1-menu" class="<?PHP if(in_array($url,$exchangeOrderManagement)) { echo "active"; } ?>">
				<a href="javascript:void(0)"><i class="fa fa-first-order"></i> <span class="nav-label">Exchange Orders</span> <span class="fa arrow"></span>  </a>
				<ul class="nav nav-second-level collapse">
					<?PHP if(Helper::check_permission($userData->id,'exchange_order')): ?>
					<li id="return-exchange-menu"><a href="{{ URL::to('admin/order/exchange-pending') }}">Pending</a></li>
					<?PHP endif; ?>
						<?PHP if(Helper::check_permission($userData->id,'exchange_order')): ?>
						<li id="return-exchange-menu"><a href="{{ URL::to('admin/order/exchange-approved') }}">Approved</a></li>
						<?PHP endif; ?>
				</ul>
			</li>
		   <?Php endif; ?>
		   
		    <li class="<?PHP if(in_array($url,$customerManagement)) { echo "active"; } ?>">
				   <a href="{{URl::to('admin/city/index')}}"><i class="fa fa-building-o"></i> <span class="nav-label">City</span></a>
			</li>


			
			  <?PHP if(Helper::check_permission($userData->id,'warehouse')): ?>	
					<li class="<?PHP if(in_array($url,$customerManagement)) { echo "active"; } ?>">
						   <a href="{{URl::to('admin/warehouse/warehouse-list')}}"><i class="fa fa-database"></i> <span class="nav-label">WareHouse</span></a>
					</li>
			  <?PHP endif; ?>
			<?PHP if(Helper::check_permission($userData->id,'brand')): ?>
			<li class="<?PHP if(in_array($url,$customerManagement)) { echo "active"; } ?>">
				<a href="{{URl::to('admin/brand/brand-list')}}"><i class="fa fa-bus"></i> <span class="nav-label">Brands</span></a>
			</li>
			<?PHP endif; ?>
	
			
		  
		    <?PHP if(Helper::check_permission($userData->id,'all_seller')): ?>
            <li class="<?PHP if(in_array($url,$userManagement)) { echo "active"; } ?>">
                <a href="#"><i class="fa fa-users"></i> <span class="nav-label">Seller Management</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                     <?PHP if(Helper::check_permission($userData->id,'all_seller')): ?>
					<li id="user-menu"><a href="{{ URL::to('admin/user/user-list') }}">All Seller</a></li>
					 <?php endif; ?>
					 <?PHP if(Helper::check_permission($userData->id,'new_request')): ?>
                    <li id="seller-menu"><a href="{{ URL::to('admin/seller/seller-list') }}">New Request</a></li>         
                     <?php endif;?>
					  <?PHP if(Helper::check_permission($userData->id,'unverified_seller')): ?>
					<li id="unverified-seller-menu"><a href="{{ URL::to('admin/seller/unverified-seller-list') }}">Unverified Sellers</a></li>         
				      <?PHP endif; ?> 
					   <?PHP if(Helper::check_permission($userData->id,'verified_seller')): ?>
					<li id="verified-seller-menu"><a href="{{ URL::to('admin/seller/verified-seller-list') }}">Verified Sellers</a></li>         
                       <?PHP endif; ?>

					   <?PHP if(Helper::check_permission($userData->id,'verified_seller')): ?>
					<li id="verified-seller-menu"><a href="{{ URL::to('admin/seller/blocked-seller-list') }}">Blocked Sellers</a></li>         
                       <?PHP endif; ?>

                      @if(Helper::check_permission($userData->id,'notice'))
					 <li id="notice-menu" class="has_sub">
						<a href="{{ URL::to('admin/notice/notice-list') }}" class="waves-effect">Notice</span> <span class="menu-arrow"></span></a>
					</li>
					@endif
					
					@if(Helper::check_permission($userData->id,'payment'))
                    <li id="delivery-menu"><a href="{{ URL::to('admin/payment/payment-report') }}">Payment</a></li>
                @endif 
					  
				</ul>
            </li>
			<?PHP endif;?>
			
			 @if(Helper::check_permission($userData->id,'delivery_boy'))
			   <li class="<?PHP if(in_array($url,$merchantManagement)) { echo "active"; } ?>">
				   <a href="javascript:void(0)"><i class="fa fa-users"></i> Delivery Boy <span class="fa arrow"></span></a>
					<ul class="nav nav-second-level collapse">
						 <?PHP if(Helper::check_permission($userData->id,'income_setting')): ?>
						<li id="delivery-boy-menu"><a href="{{ URL::to('admin/delivery-boy/income-setting') }}">Income Setting</a></li>
						 <?php endif; ?>
						 <?PHP if(Helper::check_permission($userData->id,'all_delivery_boy')): ?>
						<li id="delivery-boy-menu"><a href="{{ URL::to('admin/delivery-boy/delivery-boy-list') }}">All Delivery Boy</a></li>
						 <?php endif; ?>
						 <?PHP if(Helper::check_permission($userData->id,'active_delivery_boy')): ?>
						<li id="active-delivery-boy-menu"><a href="{{ URL::to('admin/delivery-boy/active-delivery-boy-list') }}">Active Delivery Boy</a></li>
						 <?php endif; ?>
						 <?PHP if(Helper::check_permission($userData->id,'inactive_delivery_boy')): ?>
						<li id="inactive-delivery-boy-menu"><a href="{{ URL::to('admin/delivery-boy/inactive-delivery-boy-list') }}">Inactive Delivery Boy</a></li>
						 <?php endif; ?>
						  @if(Helper::check_permission($userData->id,'delivery-boy-payment'))
								<li id="delivery-menu"><a href="{{ URL::to('admin/delivery-boy-payment') }}">Payment</a></li>
							@endif 
							@if(Helper::check_permission($userData->id,'delivery-boy-payment'))
								<li id="delivery-menu"><a href="{{ URL::to('admin/delivery-boy-cod-payment') }}">Cod Payment</a></li>
							@endif 
					</ul>	 
				</li>
			@endif
			
			@if(Helper::check_permission($userData->id,'customer'))
			   <li class="<?PHP if(in_array($url,$customerManagement)) { echo "active"; } ?>">
				   <a href="javascript:void(0)"><i class="fa fa-users"></i> <span class="nav-label">Customers</span> <span class="fa arrow"></span></a>
				   
					<ul class="nav nav-second-level collapse">
						 <?PHP if(Helper::check_permission($userData->id,'all_customers')): ?>
						<li id="customer-menu"><a href="{{ URL::to('admin/customer/customer-list') }}">All Customers</a></li>
						 <?php endif; ?>
						 <?PHP if(Helper::check_permission($userData->id,'active_customer')): ?>
						<li id="active-customer-menu"><a href="{{ URL::to('admin/customer/active-customer-list') }}">Active Customers</a></li>
						 <?php endif; ?>
						 <?PHP if(Helper::check_permission($userData->id,'inactive _customers')): ?>
						<li id="inactive-customer-menu"><a href="{{ URL::to('admin/customer/inactive-customer-list') }}">Inactive Customers</a></li>
						 <?php endif; ?>

						 <?PHP if(Helper::check_permission($userData->id,'notification')): ?>
						<li id="inactive-customer-menu"><a href="{{ URL::to('admin/customer/notification') }}">Notification</a></li>
						 <?php endif; ?>
						
						 
					</ul>	 
				</li>
			@endif
			
			
			@if(Helper::check_permission($userData->id,'category'))
            <li class="<?PHP if(in_array($url,$propertyManagement)) { echo "active"; } ?>">
                <a href="javascript:void(0)"><i class="fa fa-cube"></i> <span class="nav-label">Product Management</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">
                    @if(Helper::check_permission($userData->id,'category'))
					<li id="category-menu"><a href="{{URL::to('admin/category/category-list')}}">Category</a></li>
                     @endif
					 @if(Helper::check_permission($userData->id,'sub_category'))
					<li id="subcategory-menu"><a href="{{URL::to('admin/subcategory/subcategory-list')}}">Sub Category</a></li>
                     @endif
                     
                    {{-- @if(Helper::check_permission($userData->id,'sub_category'))
					<li id="subcategory-menu"><a href="{{URL::to('admin/supersubcategory/super-subcategory-list')}}">Super Sub Category</a></li>
                     @endif--}}
					 
                      {{--@if(Helper::check_permission($userData->id,'size'))
                    <li id="size-menu"><a href="{{URL::to('admin/product/size-list')}}">Size</a></li>
                     @endif--}}
					 {{--@if(Helper::check_permission($userData->id,'color'))
                     <li id="color-menu"><a href="{{URL::to('admin/product/color-list')}}">Color</a></li>
                      @endif--}}

					 @if(Helper::check_permission($userData->id,'verified_product'))
					<li id="product-menu"><a href="{{URL::to('admin/product/product-list')}}">Active Products</a></li>
					 @endif
					@if(Helper::check_permission($userData->id,'unverified_product'))
                      <li id="unverified-product-menu"><a href="{{URL::to('admin/product/unverified-product-list')}}">Inactive Products</a></li>
                     @endif
                   
				</ul>
            </li>
            @endif 
			
			@if(Helper::check_permission($userData->id,'slider'))
            <li id="report-menu">
                <a href="javascript:void(0)"><i class="fa fa-file"></i> <span class="nav-label">Homepage Mangement</span> <span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level collapse">
				 @if(Helper::check_permission($userData->id,'slider'))
				 <li id="slider-menu" class="has_sub">
	                <a href="{{ URL::to('admin/slider/slider-list') }}" class="waves-effect"><i class="fa fa-book"></i><span>Slider</span> <span class="menu-arrow"></span></a>
	            </li>
	            @endif
				@if(Helper::check_permission($userData->id,'banner'))
				 <li id="cms-menu" class="has_sub">
					 <a href="{{ URL::to('admin/banner/banner-list') }}" class="waves-effect"><i class="fa fa-book"></i><span>Banner</span> <span class="menu-arrow"></span></a>
				 </li>
				@endif
				<!-- @if(Helper::check_permission($userData->id,'banner'))
				 <li id="cms-menu" class="has_sub">
					 <a href="{{ URL::to('admin/banner/banner-list') }}" class="waves-effect"><i class="fa fa-book"></i><span>New_Banner</span> <span class="menu-arrow"></span></a>
				 </li> -->
				@endif
				@if(Helper::check_permission($userData->id,'cms'))
			 <li id="cms-menu" class="has_sub">
                <a href="{{ URL::to('admin/cms/cms-list') }}" class="waves-effect"><i class="fa fa-book"></i><span>CMS</span> <span class="menu-arrow"></span></a>
            </li>
            @endif
			
                 @if(Helper::check_permission($userData->id,'faq'))
			 <li id="cms-menu" class="has_sub">
                <a href="{{ URL::to('admin/faq/faq-list') }}" class="waves-effect"><i class="fa fa-book"></i><span>Faq</span> <span class="menu-arrow"></span></a>
            </li>
            @endif
                </ul>
			</li>
			@endif

			<li class="<?PHP if(in_array($url,$customerManagement)) { echo "active"; } ?>">
				<a href="{{URl::to('admin/delivery-time')}}"><i class="fa fa-bus"></i> <span class="nav-label">Delivery Time & Charges</span></a>
			</li>
			

			<?PHP if(Helper::check_permission($userData->id,'review_and_rating')): ?>
			<li class="<?PHP if(in_array($url,$rating)) { echo "active"; } ?>">
				<a href="{{URl::to('admin/product/review-rating')}}"><i class="fa fa-star"></i> <span class="nav-label">Review & Rating</span></a>
			</li>
			<?PHP endif; ?>
			@if(Helper::check_permission($userData->id,'delivery'))
            <li id="report-menu">
                <a href="javascript:void(0)"><i class="fa fa-file"></i> <span class="nav-label">Report</span> <span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level collapse">
				@if(Helper::check_permission($userData->id,'delivery'))
                    <li id="delivery-menu"><a href="{{ URL::to('admin/report/delivery-report') }}">Delivery</a></li>
                @endif 
				
                </ul>
			</li>
			@endif
			
			@if(Helper::check_permission($userData->id,'delivery'))
            <li id="report-menu">
                <a href="javascript:void(0)"><i class="fa fa-file"></i> <span class="nav-label">Delivery Boy Payment</span> <span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level collapse">
				@if(Helper::check_permission($userData->id,'delivery-boy-payment'))
                    <li id="delivery-menu"><a href="{{ URL::to('admin/delivery-boy-payment') }}">Payment</a></li>
                @endif 
                </ul>
			</li>
			@endif
			@if(Helper::check_permission($userData->id,'call_request'))
				<li id="report-menu">
					<a href="javascript:void(0)"><i class="fa fa-file"></i> <span class="nav-label">Support & Complaint</span> <span class="fa arrow"></span> </a>
					<ul class="nav nav-second-level collapse">
						{{--@if(Helper::check_permission($userData->id,'contact_us'))
							<li id="general-menu"><a href="{{ URL::to('admin/site-setting/user-complaints') }}">Support</a></li>
						@endif--}}
						@if(Helper::check_permission($userData->id,'contact_us'))
							<li id="general-menu"><a href="{{ URL::to('admin/raising-complain') }}">Raising Complaint</a></li>
						@endif
							@if(Helper::check_permission($userData->id,'call_request'))
								<li id="general-menu"><a href="{{ URL::to('admin/call-request') }}">Call Request</a></li>
							@endif
					</ul>
				</li>
			@endif
			
			<?PHP if(Helper::check_permission($userData->id,'setting')): ?>   
            <li class="<?PHP if(in_array($url,$setting)) { echo "active"; } ?>">
                <a href="#"><i class="fa fa-bar-chart-o"></i> <span class="nav-label">Setting</span><span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse">

                    
                    @if(Helper::check_permission($userData->id,'contact_us'))   
                    <li id="general-menu"><a href="{{ URL::to('admin/site-setting/user-notification') }}">User Notification</a></li>
				    @endif

					@if(Helper::check_permission($userData->id,'contact_us'))   
                    <li id="general-menu"><a href="{{ URL::to('admin/site-setting/contact-us') }}">Contact Us</a></li>
				    @endif

				    @if(Helper::check_permission($userData->id,'cashback'))   
                    <li id="general-menu"><a href="{{ URL::to('admin/site-setting/cashback') }}">Cashback</a></li>
				    @endif					
					@if(Helper::check_permission($userData->id,'referal'))   
                    <li id="general-menu"><a href="{{ URL::to('admin/site-setting/refernearn') }}">Reffer & Earn</a></li>
				    @endif
					
					@if(Helper::check_permission($userData->id,'bank_details')) 
					<li id="bank-details-menu"><a href="{{ URL::to('admin/site-setting/add-agreement') }}">Agreement</a></li>
                    @endif
					
					{{--@if(Helper::check_permission($userData->id,'delivery'))
					   <li id="standard-menu"><a href="{{ URL::to('admin/delivery/standard') }}">Delivery Charge</a></li>
					@endif--}}
					{{--@if(Helper::check_permission($userData->id,'rider-find-radius'))--}}
                        {{--<li id="standard-menu"><a href="{{ URL::to('admin/setting/rider-find-radius') }}">Rider Find Radius</a></li>--}}
                    {{--@endif--}}
					@if(Helper::check_permission($userData->id,'joinus_cms'))
						<li id="general-menu"><a href="{{ URL::to('admin/site-setting/seller-joinus-cms') }}">Seller Joinus Cms</a></li>
					@endif
				</ul>
            </li>
			<?PHP endif; ?>
        </ul>

    </div>
</nav>
<script>
    var url = "<?=$url;?>";
    var data = url.split('-');
	if(data.length==2)
	{
    $("#"+data[0]+'-menu').addClass('active');
    $("#"+data[1]+'-menu').addClass('active');
	}
	else if(data.length==3)
	{
     $("#"+data[0]+'-'+data[1]+'-menu').addClass('active');
	}
</script>