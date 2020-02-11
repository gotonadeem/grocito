<?php
/**
 * Created by PhpStorm.
 * User: wingstud
 * Date: 10/8/17
 * Time: 12:49 PM
 */
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
@extends('admin.layout.admin')
@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>Add Subadmin</h3>
                    <div class="text-right">
                        <div class="ibox-tools">
                        </div>
                    </div>
							<div class="text-right"><a href="{{ URL::to('admin/subadmin/view-all-subadmin') }}" class="btn btn-info">Back</a>
								<div class="ibox-tools">
								</div>
							</div>
                                 {{ Form::open(array('url' => 'admin/subadmin/store-subadmin','class'=>'form-horizontal','id'=>'add_subadmin','name'=>'add_subadmin')) }}
                                 <div class="form-group row">
                                     <label for="inputEmail3" class="col-sm-4 form-control-label">Username<span class="text-danger">*</span></label>
                                     <div class="col-sm-7">
                                         <input type="text" class="form-control" name="name" id="name" value="{!! old('name') !!}" placeholder="Username">
                                         <div class="error-message">{{ $errors->first('name') }}</div>
                                     </div>
                                 </div>
                                 <div class="form-group row">
                                         <label for="inputEmail3" class="col-sm-4 form-control-label">Email<span class="text-danger">*</span></label>
                                         <div class="col-sm-7">
                                             <input type="email" class="form-control" name="email" id="email" value="{!! old('email') !!}" placeholder="Email">
                                             <div class="error-message">{{ $errors->first('email') }}</div>

                                         </div>
                                     </div>
                                     <div class="form-group row">
                                         <label for="hori-pass1" class="col-sm-4 form-control-label">Password<span class="text-danger">*</span></label>
                                         <div class="col-sm-7">
                                             <input name="password" type="password" id="password" placeholder="Password" class="form-control">
                                             <div class="error-message">{{ $errors->first('password') }}</div>
                                         </div>
                                     </div>

                                     <div class="form-group row">
                                         <label for="hori-pass2" class="col-sm-4 form-control-label">Confirm Password
                                             <span class="text-danger">*</span></label>
                                         <div class="col-sm-7">
                                             <input type="password"  name="password_confirmation" placeholder="Enter Confirm Password" class="form-control" id="password_confirmation">
                                             <div class="error-message">{{ $errors->first('password_confirmation') }}</div>
                                         </div>
                                     </div>
                                      <div class="form-group row">
									  <label for="hori-pass2" class="col-sm-4 form-control-label">Select Permission
                                             <span class="text-danger">*</span></label>
									  </div>
									  
                                     <div class="form-group row">
                                         <div class="col-md-12">
                                                <p>
											      <div class="col-md-6"><input type="checkbox">Orders
												  <ul>
												     <li><input type="checkbox" name="permission[]" value="order">Pending Order <input type="checkbox" name="action[order][]" value="edit">Edit | <input type="checkbox" name="action[order][]" value="view">View |<input type="checkbox" name="action['order'][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="completed_order">Completed Order <input type="checkbox" name="action[completed_order][]" value="edit">Edit | <input type="checkbox" name="action[completed_order][]" value="view">View |<input type="checkbox" name="action[completed_order][]" value="delete">Delete</li></li>
												     <li><input type="checkbox" name="permission[]" value="return_exchange_order">Return Exchange Order <input type="checkbox" name="action[return_exchange_order][]" value="edit">Edit | <input type="checkbox" name="action[return_exchange_order][]" value="view">View |<input type="checkbox" name="action[return_exchange_order][]" value="delete">Delete</li></li>
												     <li><input type="checkbox" name="permission[]" value="cancelled_order">Cancelled Order <input type="checkbox" name="action[cancelled_order][]" value="edit">Edit | <input type="checkbox" name="action[cancelled_order][]" value="view">View |<input type="checkbox" name="action[cancelled_order][]" value="delete">Delete</li></li>
												     <li><input type="checkbox" name="permission[]" value="incompleted_order">Incompleted Order <input type="checkbox" name="action[incompleted_order][]" value="edit">Edit | <input type="checkbox" name="action[incompleted_order][]" value="view">View |<input type="checkbox" name="action[incompleted_order][]" value="delete">Delete</li></li>
												   </ul>
												   
												  </div>
												</p>
									  </div>						
									  <div class="col-sm-12">
									  
                                               <p>
											     <div class="col-md-6"><input type="checkbox" name="permission[]" value="subadmin">Subadmin <input type="checkbox" name="action[subadmin][]" value="edit">Edit | <input type="checkbox" name="action[subadmin][]" value="view">View |<input type="checkbox" name="action[subadmin][]" value="delete">Delete</li></div>
											     <div class="col-md-6"><input type="checkbox" name="permission[]" value="city">City <input type="checkbox" name="action[city][]" value="edit">Edit | <input type="checkbox" name="action[city][]" value="view">View |<input type="checkbox" name="action[city][]" value="delete">Delete</li> </div>
											     <div class="col-md-6"><input type="checkbox" name="permission[]" value="warehouse">WareHouse <input type="checkbox" name="action[warehouse][]" value="edit">Edit | <input type="checkbox" name="action[warehouse][]" value="view">View |<input type="checkbox" name="action[warehouse][]" value="delete">Delete</li> </div>
											     <div class="col-md-6"><input type="checkbox" name="permission[]" value="brand">Brands <input type="checkbox" name="action[brand][]" value="edit">Edit | <input type="checkbox" name="action[brand][]" value="view">View |<input type="checkbox" name="action[brand][]" value="delete">Delete</li> </div>
											    <div class="col-md-6"><input type="checkbox">Seller Management 
												   <ul>
												     <li><input type="checkbox" name="permission[]" value="all_seller">All Seller <input type="checkbox" name="action[all_seller][]" value="edit">Edit | <input type="checkbox" name="action[all_seller][]" value="view">View |<input type="checkbox" name="action[all_seller][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="new_request_seller">New Request <input type="checkbox" name="action[new_request_seller][]" value="edit">Edit | <input type="checkbox" name="action[new_request_seller][]" value="view">View |<input type="checkbox" name="action[new_request_seller][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="unverified_seller">Unverified Sellers <input type="checkbox" name="action[unverified_seller][]" value="edit">Edit | <input type="checkbox" name="action[unverified_seller][]" value="view">View |<input type="checkbox" name="action[unverified_seller][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="verified_seller">Verified Seller <input type="checkbox" name="action[verified_seller][]" value="edit">Edit | <input type="checkbox" name="action[verified_seller][]" value="view">View |<input type="checkbox" name="action[verified_seller][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="blocked_seller">Blocked Sellers <input type="checkbox" name="action[blocked_seller][]" value="edit">Edit | <input type="checkbox" name="action[blocked_seller][]" value="view">View |<input type="checkbox" name="action[blocked_seller][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="notice">Blocked Sellers <input type="checkbox" name="action[notice][]" value="edit">Edit | <input type="checkbox" name="action[notice][]" value="view">View |<input type="checkbox" name="action[notice][]" value="delete">Delete</li>
												   </ul>
												   </div>
												</p>
                                               <p>
											   
											   <div class="col-md-6"> 
											   <input type="checkbox">Customers 
												  <ul>
												     <li><input type="checkbox" name="permission[]" value="all_customers">All Customers <input type="checkbox" name="action[all_customers][]" value="edit">Edit | <input type="checkbox" name="action[all_customers][]" value="view">View |<input type="checkbox" name="action[all_customers][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="active_customer">Active Customers <input type="checkbox" name="action[active_customer][]" value="edit">Edit | <input type="checkbox" name="action[active_customer][]" value="view">View |<input type="checkbox" name="action[active_customer][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="inactive_customer">Inactive Customers <input type="checkbox" name="action[inactive_customer][]" value="edit">Edit | <input type="checkbox" name="action[inactive_customer][]" value="view">View |<input type="checkbox" name="action[inactive_customer][]" value="delete">Delete</li>
												     
												   </ul>
											   </div>

											 
											   
											   <div class="col-md-6"> <input type="checkbox">Product Management 
											   
											        <ul>
												     <li><input type="checkbox" name="permission[]" value="category">Category  <input type="checkbox" name="action[category][]" value="edit">Edit | <input type="checkbox" name="action[category][]" value="view">View |<input type="checkbox" name="action['category'][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="sub_category">Sub Category <input type="checkbox" name="action[sub_category][]" value="edit">Edit | <input type="checkbox" name="action[sub_category][]" value="view">View |<input type="checkbox" name="action[sub_category][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="unverified_product">Unverified Product <input type="checkbox" name="action[unverified_product][]" value="edit">Edit | <input type="checkbox" name="action[unverified_product][]" value="view">View |<input type="checkbox" name="action[unverified_product][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="verified_product">Verified Product <input type="checkbox" name="action[verified_product][]" value="edit">Edit | <input type="checkbox" name="action[verified_product][]" value="view">View |<input type="checkbox" name="action[verified_product][]" value="delete">Delete</li>
												   </ul>
												   
											   </div>

											   <div class="col-md-6"> <input type="checkbox">Delivery Boy
											        <ul>
												     <li><input type="checkbox" name="permission[]" value="all_delivery_boy">All Delivery Boy  <input type="checkbox" name="action[all_delivery_boy][]" value="edit">Edit | <input type="checkbox" name="action[all_delivery_boy][]" value="view">View |<input type="checkbox" name="action[all_delivery_boy][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="active_delivery_boy">Active Delivery Boy <input type="checkbox" name="action[active_delivery_boy][]" value="edit">Edit | <input type="checkbox" name="action[active_delivery_boy][]" value="view">View |<input type="checkbox" name="action[active_delivery_boy][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="inative_delivery_boy">Inactive Delivery Boy <input type="checkbox" name="action[inative_delivery_boy][]" value="edit">Edit | <input type="checkbox" name="action[inative_delivery_boy][]" value="view">View |<input type="checkbox" name="action[inative_delivery_boy][]" value="delete">Delete</li>
												   </ul>
											   </div>

											   <div class="col-md-6"> <input type="checkbox">Homepage Management
											        <ul>
												     <li><input type="checkbox" name="permission[]" value="slider">Slider <input type="checkbox" name="action[slider][]" value="edit">Edit | <input type="checkbox" name="action[slider][]" value="view">View |<input type="checkbox" name="action[slider][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="banner">Banner <input type="checkbox" name="action[banner][]" value="edit">Edit | <input type="checkbox" name="action[banner][]" value="view">View |<input type="checkbox" name="action[banner][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="cms">CMS <input type="checkbox" name="action[cms][]" value="edit">Edit | <input type="checkbox" name="action[cms][]" value="view">View |<input type="checkbox" name="action[cms][]" value="delete">Delete</li>
												   </ul>
											   </div>
											   </p>
                                               <p><div class="col-md-6"><input type="checkbox" name="permission[]" value="delivery_time">Delivery Time <input type="checkbox" name="action[delivery_time][]" value="edit">Edit | <input type="checkbox" name="action[delivery_time][]" value="view">View |<input type="checkbox" name="action[delivery_time][]" value="delete">Delete</div></p>
                                               
											   <p><div class="col-md-6"><input type="checkbox">Report
											       <ul>
												     <li><input type="checkbox" name="permission[]" value="delivery">Delivery <input type="checkbox" name="action[delivery][]" value="edit">Edit | <input type="checkbox" name="action[delivery][]" value="view">View |<input type="checkbox" name="action[delivery][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="payment">Payment <input type="checkbox" name="action[payment][]" value="payment">Edit | <input type="checkbox" name="action[payment][]" value="view">View |<input type="checkbox" name="action[payment][]" value="delete">Delete</li>
												   
												   </ul>
											   
											   </div></p>

											   <p><div class="col-md-6"><input type="checkbox">Support & Complaint
											       <ul>
												     <li><input type="checkbox" name="permission[]" value="call_request">Call Request <input type="checkbox" name="action[call_request][]" value="edit">Edit | <input type="checkbox" name="action[call_request][]" value="view">View |<input type="checkbox" name="action[call_request][]" value="delete">Delete</li>
												     <li><input type="checkbox" name="permission[]" value="raising_complaint">Raising Complaint <input type="checkbox" name="action[raising_complaint][]" value="payment">Edit | <input type="checkbox" name="action[raising_complaint][]" value="view">View |<input type="checkbox" name="action[raising_complaint][]" value="delete">Delete</li>
												   </ul>
											   </div></p>
					
                                         </div>
                                     </div>


                                     <div class="form-group row">
                                         <div class="col-sm-8 col-sm-offset-4">
                                             <button type="submit" name="add_subadmin" value="add_subadmin"  class="btn btn-primary waves-effect waves-light">
                                                 Register
                                             </button>
                                             <button type="reset"
                                                     class="btn btn-default waves-effect m-l-5">
                                                 Cancel
                                             </button>
                                         </div>
                                     </div>
                                 {{ Form::close() }}
               </div>
            </div>
        </div>
    </div>
</div>
	
	 @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- Custom and plugin javascript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    <script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>
	 <script src="https://cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <!-- Page-Level Scripts -->
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
    </script>
	   <script>
      $('#seller').select2({
                placeholder : 'Please select Seller',
                tags: true
            });    
    CKEDITOR.replace( 'description');
        CKEDITOR.replace( 'extra_config_details');
        var delete_img="{{ URL::asset('public/admin/images/delete-btn.png') }}";
        var loading_img="{{ URL::asset('public/front/image/index.rotating-balls-spinner.svg') }}";
    </script>
    @include('admin.includes.admin_right_sidebar')
    @include('admin.includes.admin_footer')
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/sub_admin.js') }}"></script>
    <script>
	$('input[type=checkbox]').click(function(){
    // children checkboxes depend on current checkbox
    $(this).next().find('input[type=checkbox]').prop('checked',this.checked);
    // go up the hierarchy - and check/uncheck depending on number of children checked/unchecked
    $(this).parents('ul').prev('input[type=checkbox]').prop('checked',function(){
        return $(this).next().find(':checked').length;
    });
});
	</script>
 @stop
     

