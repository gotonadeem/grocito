<?php
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
                        <h5>List of Unverified Product</h5>
                        <div class="text-right"><a href="{{ URL::to('admin/product/create-product') }}" class="btn btn-info">Add New</a>
                            
                        </div>
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table id="unverified-products-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>Sr.</th>
                                        <th>Category</th>
                                        <th>Product Id</th>
                                        <th>Product Name</th>
                                        <th>Seller</th>

                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    @include('admin.includes.admin_footer_inner')
    <!-- Page-Level Scripts -->
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/product.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"Id,P-Name,S-name,Category");
	</script>
	
	<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
	  <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Enter wallet deduction and verify the product</h4>
      </div>
      <div class="modal-body">
	   <div id="msg"></div>
       <form id="mail_form" name="mail_form">
	       <div class="form-group">
             <input type="hidden" name="p_id" id="p_id">			 
             <input type="hidden" name="seller_id" id="seller_id">
			 <input type="text" name="w_commission" placeholder="Admin Commission" class="form-control" id="w_commission">
			 <span id="w_msg"></span>
		   </div>
           <div class=" row">
           <div class="col-sm-6">
               <div class="form-group row">
                   <label class="col-sm-4 form-control-label">Product Return :</label>
                   <div class="col-sm-8">
                       <input type="radio"  name="is_return" id="is_return" value='1' checked>Yes
                       <input type="radio"  name="is_return" id="is_return" value='0' >No
                   </div>
               </div>
           </div>
           <div class="col-sm-6">
               <div class="form-group row">
                   <label class="col-sm-4 form-control-label">Product Exchange :</label>
                   <div class="col-sm-8">
                       <input type="radio"  name="is_exchange" id="is_exchange" value='1' >Yes
                       <input type="radio"  name="is_exchange" id="is_exchange" value='0' checked>No
                   </div>
               </div>
           </div>
           </div>
	   </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="verify_now" class="btn btn-primary">Verify Now</button>
      </div>
    </div>
   </div>
</div>

@stop