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
                        <h5>List Of Coupon Code</h5>
                        <div class="text-right">
                            <a href="{{ URL::to('admin/coupon/create') }}" class="btn btn-info">Add New</a>  
                        </div>
                        <div class="ibox-content">
                            <div class="table-responsive">
                                <table id="coupon-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                         <th>Sr.</th>
                                         <th>Code</th>
                                         <th>Start Date</th>
                                         <th>End Date</th>
                                         <th>Discount Amount</th>
                                         <th>Discount Unit</th>
                                         <th>No of usage</th>
                                         <th>Usage per user</th>
                                         <th>Min Order Amount</th>
                                         <th>Status</th>
                                         <th>Created at</th>
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
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/coupon.js') }}"></script>
	<script>
		$('.input-sm').attr('placeholder',"name");
	</script>
@stop
