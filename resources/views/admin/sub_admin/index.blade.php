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
                      <h5 class="pull-left">Subadmin List</h5>
					 <a href="{{ URL::to('admin/subadmin/add-subadmin') }}" title="Add sub-admin"  class="btn btn-info pull-right" style="margin-top: -1%;">Add New</a>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">

                             <div class="ibox-content">
                            <table id="datatable-fixed-header" class="table table-striped  table-colored table-info">
                                <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->
    </div>
   @include('admin.includes.admin_right_sidebar')
   @include('admin.sub_admin.popups.change_password')
	<!-- Mainly scripts -->
	<script src="{{ URL::asset('public/admin/js/jquery-3.1.1.min.js') }}"></script>
	<script src="{{ URL::asset('public/admin/js/bootstrap.min.js') }}"></script>
	<script src="{{ URL::asset('public/admin/js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
	<script src="{{ URL::asset('public/admin/js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>
	<script src="{{ URL::asset('public/admin/js/plugins/dataTables/datatables.min.js') }}"></script>
	<!-- Custom and plugin javascript -->
	<script src="{{ URL::asset('public/admin/js/inspinia.js') }}"></script>
	<script src="{{ URL::asset('public/admin/js/plugins/pace/pace.min.js') }}"></script>
    <script>
        ASSET_URL = '{{ URL::asset('public') }}/';
        BASE_URL='{{ URL::to('/') }}';
    </script>
    <script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
    <script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/sub_admin.js') }}"></script>
@stop
