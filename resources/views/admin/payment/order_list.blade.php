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
                    <h5 class="pull-left">Order List &nbsp;&nbsp;(<?=$data->email?>) &nbsp;&nbsp; Date  <?=$id1?> </h5>
                     <a class="btn btn-primary pull-right" href="{{URL::to('admin/payment/view-payment')}}/<?=Request::segment(4);?>">Back</a>
                </div>
                <div class="ibox-content">
                    <div class="table-responsive">
                        <table id="order-table" class="table table-striped table-bordered table-hover dataTables-example">
                            <thead>
                            <tr>
                                <th>Sr.</th>
                                <th>O-Id</th>
                                <th>C-Name</th>
                                <th>C-Mob</th>

                                <th>Amt</th>
                                <th>O-Status</th>
                                <th>Items</th>
                                <th>Qty</th>
                                <th>P-mode</th>

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
    shipped_date='{{$id1}}';
    seller_id='{{$id3}}';
</script>
<script type="text/javascript" src="{{ URL::asset('public/admin/plugins/jquery-validation/js/jquery.validate.min.js') }}"></script>
<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/order_list.js') }}"></script>
<script>
		$('.input-sm').attr('placeholder',"order id,c-name,c-mobile");
	</script>
@stop
