<?php
/**
 * Created by PhpStorm.
<<?php
/**
 * Created by PhpStorm.
 * User: wingstud
 * Date: 10/8/17
 * Time: 12:49 PM
 */
?>
@extends('admin.layout.admin')
@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Edit Subadmin</h5>
                        <div class="text-right"><a href="{{ URL::to('admin/subadmin/view-all-subadmin') }}" class="btn btn-info">Back</a>
                            
                        </div>
                        <div class="ibox-content">
                            <div class="p-70">
                                {{ Form::model($users,array('url' => 'admin/subadmin/edit/'.$users->id,'class'=>'form-horizontal')) }}
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 form-control-label">Username<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {{Form::text('username',NULL,['class'=>'form-control','id'=>'name','placeholder'=>'Enter username'])}}
                                        <div class="error-message">{{ $errors->first('name') }}</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail3" class="col-sm-4 form-control-label">Email<span class="text-danger">*</span></label>
                                    <div class="col-sm-7">
                                        {{Form::text('email',$users->email,['class'=>'form-control','id'=>'email','placeholder'=>'Enter email'])}}
                                        <div class="error-message">{{ $errors->first('email') }}</div>

                                    </div>
                                </div>
								 <div class="form-group row">
                                         <label for="hori-pass2" class="col-sm-4 form-control-label">Select Permission
                                             <span class="text-danger">*</span></label>
                                         <div class="col-sm-7">
										         <?PHP 
												 $permission=array();
												 if(!is_null($users->subadmin_access))
												 {
												 $permission= explode(",",$users->subadmin_access->access_permission);
												 }
												 ?>
											   <p>
											    <div class="col-md-6">
											    <input type="checkbox" <?PHP if(in_array('dashboard',$permission)): echo 'checked'; endif; ?> name="permission[]" value="dashboard">Dashboard 
											    </div>
											    <div class="col-md-6">
												<input type="checkbox" <?PHP if(in_array('orders',$permission)): echo 'checked'; endif; ?> name="permission[]" value="orders">Orders
												  <ul>
												     <li><input type="checkbox" name="permission[]"  <?PHP if(in_array('order',$permission)): echo 'checked'; endif; ?> value="order">Pending Order</li>
												     <li><input type="checkbox" name="permission[]"  <?PHP if(in_array('completed_order',$permission)): echo 'checked'; endif; ?> value="completed_order">Completed Order</li>
												     <li><input type="checkbox" name="permission[]"  <?PHP if(in_array('return_exchange_order',$permission)): echo 'checked'; endif; ?> value="return_exchange_order">Return Exchange Order</li>
												     <li><input type="checkbox" name="permission[]"  <?PHP if(in_array('cancelled_order',$permission)): echo 'checked'; endif; ?> value="cancelled_order">Cancelled Order</li>
												     <li><input type="checkbox" name="permission[]"  <?PHP if(in_array('incompleted_order',$permission)): echo 'checked'; endif; ?> value="incompleted_order">Incompleted Order</li>
												   </ul>
												</div>
												</p>
												
                                               <p>
											   <div class="col-md-6">
											   <input type="checkbox" <?PHP if(in_array('subadmin',$permission)): echo 'checked'; endif; ?>  name="permission[]" value="subadmin">Subadmin 
											   </div>
											   <div class="col-md-6">
											   <input type="checkbox" <?PHP if(in_array('seller_management',$permission)): echo 'checked'; endif; ?> name="permission[]" value="seller_management">Seller Management
											     <ul>
												     <li><input type="checkbox" <?PHP if(in_array('all_seller',$permission)): echo 'checked'; endif; ?> name="permission[]" value="all_seller">All Seller</li>
												     <li><input type="checkbox" <?PHP if(in_array('new_request_seller',$permission)): echo 'checked'; endif; ?> name="permission[]" value="new_request_seller">New Request</li>
												     <li><input type="checkbox" <?PHP if(in_array('unverified_seller',$permission)): echo 'checked'; endif; ?> name="permission[]" value="unverified_seller">Unverified Sellers</li>
												     <li><input type="checkbox" <?PHP if(in_array('verified_seller',$permission)): echo 'checked'; endif; ?> name="permission[]" value="verified_seller">Verified Seller</li>
												   </ul>
											   
											   
											   </div>
											   </p>
                                               <p>
											    
												
												 <div class="col-md-6"> 
											   <input type="checkbox" name="permission[]"  <?PHP if(in_array('customer',$permission)): echo 'checked'; endif; ?> value="customer">Customers 
											     
												  <ul>
												     <li><input type="checkbox" name="permission[]" <?PHP if(in_array('all_customers',$permission)): echo 'checked'; endif; ?> value="all_customers">All Customers</li>
												     <li><input type="checkbox"  <?PHP if(in_array('active_customer',$permission)): echo 'checked'; endif; ?> name="permission[]" value="active_customer">Active Customers</li>
												     <li><input type="checkbox" <?PHP if(in_array('inactive _customer',$permission)): echo 'checked'; endif; ?> name="permission[]" value="inactive _customer">Inactive Customers</li>
												     
												   </ul>
											   </div>
											   
                                                <div class="col-md-6">												
												<input type="checkbox" <?PHP if(in_array('product_management',$permission)): echo 'checked'; endif; ?> name="permission[]" value="product_management">Product Management
												    <ul>
												     <li><input type="checkbox" <?PHP if(in_array('category',$permission)): echo 'checked'; endif; ?> name="permission[]" value="category">Category</li>
												     <li><input type="checkbox" <?PHP if(in_array('sub_category',$permission)): echo 'checked'; endif; ?> name="permission[]" value="sub_category">Sub Category</li>
												     <li><input type="checkbox" <?PHP if(in_array('unverified_product',$permission)): echo 'checked'; endif; ?> name="permission[]" value="unverified_product">Unverified Product</li>
												     <li><input type="checkbox" <?PHP if(in_array('verified_product',$permission)): echo 'checked'; endif; ?> name="permission[]" value="verified_product">Verified Product</li>
												     <li><input type="checkbox" <?PHP if(in_array('size',$permission)): echo 'checked'; endif; ?> name="permission[]" value="size">Size</li>
												     <li><input type="checkbox" <?PHP if(in_array('product_sponsor',$permission)): echo 'checked'; endif; ?> name="permission[]" value="product_sponsor">Product Sponsor</li>
												   </ul>
												   
												</div>
												</p>
                                               <p>
											   <div class="col-md-6">
											    <input type="checkbox" <?PHP if(in_array('slider',$permission)): echo 'checked'; endif; ?> name="permission[]" value="slider">Slider 
											   </div>	
												<div class="col-md-6">
												<input type="checkbox"  name="permission[]" <?PHP if(in_array('seller_notice',$permission)): echo 'checked'; endif; ?> value="seller_notice">Seller Notice
												</div>  
												</p>
                                               <p>
											   <div class="col-md-6">
											   <input type="checkbox" <?PHP if(in_array('plan',$permission)): echo 'checked'; endif; ?> name="permission[]" value="plan">Plan
											   </div>
											   </p>
                                               <p>
											   <div class="col-md-6">
											   <input type="checkbox" <?PHP if(in_array('report',$permission)): echo 'checked'; endif; ?> name="permission[]" value="report">Report
											      <ul>
												     <li><input type="checkbox" <?PHP if(in_array('delivery',$permission)): echo 'checked'; endif; ?> name="permission[]" value="delivery">Delivery</li>
												     <li><input type="checkbox" <?PHP if(in_array('payment',$permission)): echo 'checked'; endif; ?> name="permission[]" value="payment">Payment</li>
												   </ul>
											   </div>
											   </p>
											   <div class="col-md-6">
											   <input type="checkbox" <?PHP if(in_array('help',$permission)): echo 'checked'; endif; ?> name="permission[]" value="plan">Help
											      <ul>
												     <li><input  <?PHP if(in_array('how_to',$permission)): echo 'checked'; endif; ?> type="checkbox" name="permission[]" value="how_to">How To</li>
												     <li><input  <?PHP if(in_array('must_see',$permission)): echo 'checked'; endif; ?> type="checkbox" name="permission[]" value="must_see">Must See</li>
												     <li><input  <?PHP if(in_array('contact_us',$permission)): echo 'checked'; endif; ?> type="checkbox" name="permission[]" value="contact_us">Contact Us</li>
												   </ul> 
											   </div>
											   </p>
                                               <p>
											   <div class="col-md-6">
											   <input type="checkbox" <?PHP if(in_array('setting',$permission)): echo 'checked'; endif; ?> name="permission[]" value="report">Setting
											      <ul>
												     <li><input <?PHP if(in_array('general_setting',$permission)): echo 'checked'; endif; ?> type="checkbox" name="permission[]" value="general_setting">General Setting</li>
												     <li><input <?PHP if(in_array('bank_details',$permission)): echo 'checked'; endif; ?> type="checkbox" name="permission[]" value="bank_details">Bank Details</li>
												     <li><input <?PHP if(in_array('agreement',$permission)): echo 'checked'; endif; ?> type="checkbox" name="permission[]" value="agreement">Agreement</li>
												     <li><input <?PHP if(in_array('app_video',$permission)): echo 'checked'; endif; ?> type="checkbox" name="permission[]" value="app_video">App Video</li>
												     <li><input <?PHP if(in_array('app_update',$permission)): echo 'checked'; endif; ?> type="checkbox" name="permission[]" value="app_update">App Update</li>
												     <li><input <?PHP if(in_array('popular_thumbnail',$permission)): echo 'checked'; endif; ?> type="checkbox" name="permission[]" value="popular_thumbnail">Popular Thumbnail</li>
												   </ul> 
											   </div>
											   </p>
                                             
                                         </div>
                                     </div>

                                <div class="form-group row">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <button type="submit" name="add_subadmin" value="add_subadmin"  class="btn btn-primary waves-effect waves-light">
                                            Update
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
                </div><!-- end col -->
            
            <!-- end row -->

        </div> <!-- container -->
    </div> <!-- content -->
    </div>
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

