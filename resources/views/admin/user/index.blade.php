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
                        <h5>All Seller List</h5>
						<!--<div class="text-right"><a href="{{ URL::to('admin/user/add-user') }}" class="btn btn-info">Add New</a>-->
                        <div class="ibox-tools"> 
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                                <table id="users-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                    <th>Sr.</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>City</th>
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
     @include('admin.includes.admin_footer_inner')
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
	  </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/user.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"username,email,mobile");
	</script>
@stop
