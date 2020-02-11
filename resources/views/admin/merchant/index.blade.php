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
                        <h5>Delivery Boy List</h5>
					<div class="text-right"><a href="{{ URL::to('admin/delivery-boy/create-delivery-boy') }}" class="btn btn-info">Add New</a>
                        <div class="ibox-tools">
						 
                         
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                                <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                    <th>Sr.</th>
                                    <th>Name</th>
                                    <th>City</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Mobile</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                    </tr>
                                    </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.includes.admin_right_sidebar')
    <!-- Mainly scripts -->
    <script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/dataTables/datatables.min.js') }}"></script>
    <!-- Custom and plugin javascript -->
    <script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>
    <script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>
    <!-- Page-Level Scripts -->
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
    </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/delivery_boy.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"username,email,mobile");
	</script>
	
	<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send email To Seller</h4>
      </div>
      <div class="modal-body">
	   <div id="msg"></div>
       <form id="mail_form" name="mail_form">
	       <div class="form-group">
		      <label for='email'>To</label>
			  <input type="text" name="email" class="form-control" id="email">
		   </div>
		   <div class="form-group">
		      <label for='subject'>Subject</label>
			  <input type="text" name="subject" class="form-control" id="subject">
		   </div>
		   
		   <div class="form-group">
		      <label for='subject'>Message</label>
			  <textarea rows="5"  name="message" class="form-control" id="message"></textarea>
		   </div>  
		   
	   </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" onclick="send_email_to_seller()" class="btn btn-primary">Send</button>
      </div>
    </div>
  </div>
</div>
@stop
