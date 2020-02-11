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
                        <h5>Cod Payment List</h5>
                    <div class="ibox-content">
                        <div class="table-responsive">
                                <table id="inActive-table" class="table table-striped table-bordered table-hover dataTables-example">
                                    <thead>
                                    <tr>
                                    <th>Sr.</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Amount</th>
                                    <th>Delivery Date</th>
                                    <th>Action</th>
                                    </tr>
                                    </thead>
									 <tbody>
									 @foreach($data as $ks=>$vs)
										<tr>
										<td>{{$ks+1}}</td>
										<td>{{$vs->user['unique_code']}}</td>
										<td>{{$vs->user['username']}}</td>
										<td>{{$vs->user['mobile']}}</td>
										<td>Rs.{{Helper::order_amount($vs->seller_id,$vs->order_id)+$vs->order->shipping_charge}}</td>
										<td>{{$vs->date}}</td>
										<td><a href="{{URL::to('admin/delivery-boy-accept-cod/'.$vs->id)}}">Accept</a></td>
										</tr>
									@endforeach	
                                    </tbody>
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
  <script>
		$('.input-sm').attr('placeholder',"username,email,mobile");
	</script>
@stop
