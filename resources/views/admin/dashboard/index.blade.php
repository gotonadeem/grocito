@extends('admin.layout.admin')
@section('content')
<?PHP
$userData=Session::get('user_sdata');
 ?>
<div class="wrapper wrapper-content">
	<!-- Order Row -->
	<div class="row">
	@if(Helper::check_permission($userData->id,'orders'))
        <div class="col-lg-4">
		<a href="{{URL::to('admin/order/order-list')}}">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">Total Orders</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$totalOrder}}</h1>
                </div>
            </div>
		</a>	
        </div>
		  @endif

			@if(Helper::check_permission($userData->id,'verified_product'))
			<div class="col-lg-4">
			<a href="{{URL::to('admin/order/order-list')}}">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<span class="label label-primary pull-right">Pending Orders</span>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$totalPendingOrder}}</h1>
					</div>
				</div>
			</a>	
			</div>
			@endif
          @if(Helper::check_permission($userData->id,'unverified_product'))
            <div class="col-lg-4">
            <a href="{{URL::to('admin/product/unverified-product-list')}}">
			
			<div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">Complete Order</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$totalDeliveredOrder}}</h1>
                </div>
            </div>
			</a>
        </div>
		@endif
    </div>
    
<!-- Customer Row -->
    <div class="row">
	@if(Helper::check_permission($userData->id,'customer'))
        <div class="col-lg-4">
		<a href="{{URL::to('admin/customer/customer-list')}}">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">Total Customers</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$total_users}}</h1>
                </div>
            </div>
		</a>	
        </div>
		  @endif

			@if(Helper::check_permission($userData->id,'verified_product'))
			<div class="col-lg-4">
			<a href="{{URL::to('admin/customer/active-customer-list')}}">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<span class="label label-primary pull-right">Active Customer</span>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$totalActiveUser}}</h1>
					</div>
				</div>
			</a>	
			</div>
			@endif
          @if(Helper::check_permission($userData->id,'unverified_product'))
            <div class="col-lg-4">
            <a href="{{URL::to('admin/customer/inactive-customer-list')}}">
			
			<div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">Block Customer</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$totalInActiveUser}}</h1>
                </div>
            </div>
			</a>
        </div>
		@endif
    </div>
	<!-- Seller Row -->
    <div class="row">
	@if(Helper::check_permission($userData->id,'seller'))
        <div class="col-lg-4">
		<a href="{{URL::to('admin/user/user-list')}}">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">Total Seller</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$total_seller}}</h1>
                </div>
            </div>
		</a>	
        </div>
		  @endif

			@if(Helper::check_permission($userData->id,'verified_product'))
			<div class="col-lg-4">
			<a href="{{URL::to('admin/seller/verified-seller-list')}}">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<span class="label label-primary pull-right">Varified Seller</span>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$total_active_seller}}</h1>
					</div>
				</div>
			</a>	
			</div>
			@endif
          @if(Helper::check_permission($userData->id,'unverified_product'))
            <div class="col-lg-4">
            <a href="{{URL::to('admin/seller/seller-list')}}">
			
			<div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">Unvarified Seller</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$new_request_seller}}</h1>
                </div>
            </div>
			</a>
        </div>
		@endif
		
    </div>
	<!-- Product Row -->
    <div class="row">
	@if(Helper::check_permission($userData->id,'customer'))
        <div class="col-lg-4">
		<a href="{{URL::to('admin/product/product-list')}}">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">Total Products</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$total_product}}</h1>
                </div>
            </div>
		</a>	
        </div>
		  @endif

			@if(Helper::check_permission($userData->id,'verified_product'))
			<div class="col-lg-4">
			<a href="{{URL::to('admin/product/product-list')}}">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<span class="label label-primary pull-right">Varified Products</span>
					</div>
					<div class="ibox-content">
						<h1 class="no-margins">{{$total_varified_product}}</h1>
					</div>
				</div>
			</a>	
			</div>
			@endif
          @if(Helper::check_permission($userData->id,'unverified_product'))
            <div class="col-lg-4">
            <a href="{{URL::to('admin/product/unverified-product-list')}}">
			
			<div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-danger pull-right">Unvarified Products</span>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{$total_unVarified_product}}</h1>
                </div>
            </div>
			</a>
        </div>
		@endif
		

    </div>
    </div>
@include('admin.includes.admin_right_sidebar')
@include('admin.includes.admin_footer')
@stop