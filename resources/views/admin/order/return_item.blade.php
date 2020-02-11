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
                    <h5>Item Details</h5>
                      
                </div>
                <div class="ibox-content">
                            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-8">
                    <h2>Order Details</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li>
                            Order
                        </li>
                        <li class="active">
                            <strong>Order View</strong>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-4">
                    <div class="title-action"> 
                        <a href="{{URL::previous()}}"  class="btn btn-primary"> Back </a>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="ibox-content p-xl">


                            <div class="table-responsive m-t">
                                <table class="table invoice-table">
                                    <thead>
                                    <tr>
                                        <th>Item Id</th>
                                        <th>Item List</th>
                                        <th>Image</th>
                                        <th>Seller Name</th>
                                        <th>O-Status</th>

                                        <th>Qty</th>
                                        <th>Unit Price</th>

                                        <th>Weight</th>
                                        <!-- <th>Color</th> -->

                                        <th>Total Price</th>
                                        
                                    </tr>
                                    </thead>
                                    <tbody>
									

									
                                    <tr>
                                        <td><div><strong>
										{{$order_item->id}}</strong></div></td>
										
										<td><div><strong>
										
										{{$order_item->product_name}} {{$order_item->size}} </strong></div></td>
										<td><img height="100" width="100" src="{{ URL::asset('/public/admin/uploads/product') }}/{{$order_item->product_image}}"></td>
                                        <td><a href="{{URL::to('admin/user/view/')}}/{{!is_null($order_item->seller)?$order_item->seller->id:''}}">{{!is_null($order_item->seller)?$order_item->seller->username:''}}({{!is_null($order_item->seller)?$order_item->seller->mobile:''}})</a></td>
                                        <td>{{$order_item->status}}</td>

                                        <td>{{$order_item->qty}}</td>
                                        <td>Rs. {{$order_item->price+$order_item->shipping_free_amount}}</td>
                                        <td>{{$order_item->weight}}</td>
                                        <!-- <td><?PHP
                                            $data=json_decode($order_item->attributes);
                                            if($data)
                                            {
                                                foreach($data as $ks=>$vs1)
                                                {
                                                    foreach($vs1 as $ks1=>$vs2)
                                                    {
                                                        echo $ks1.":&nbsp;&nbsp;<span style='background-color:".$vs2."'>&nbsp;&nbsp;&nbsp;&nbsp;</span><br>";
                                                    }
                                                }
                                            }
                                            ?></td> -->

                                        <td>Rs. {{$order_item->price+$order_item->shipping_free_amount*$order_item->qty}}</td>
                                    </tr>

                                    </tbody>
                                </table>
                            </div><!-- /table-responsive -->
							
							



                           
                        </div>
                </div>
            </div>
			
        </div>
		  <div class="row">
		  
		              {{-- @include('common.track_order') --}}
						
		  </div>



                </div>
            </div>
        </div>
    </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Details</h4>
      </div>
      <div class="modal-body">
	   <div id="msg"></div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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
<script language="JavaScript" type="text/javascript" src="{{ URL::asset('public/admin/developer/js/order.js') }}"></script>
<style>
.banned{ color: white; background: red;font-size: large;border-radius: 8px; }
.ban{ color: white; background:black;font-size: large;border-radius: 8px; }
</style>
@stop
