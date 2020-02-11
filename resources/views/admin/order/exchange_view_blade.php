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
                    <h5>Order Details</h5>
                      
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
                        <a href="{{URL::to('admin/order/order-list')}}"  class="btn btn-primary"> Back </a>
                        <a href="{{URL::to('admin/order/download-order-invoice')}}/<?=$order_data->id; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print Order </a>
                    </div>
                </div>
            </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="wrapper wrapper-content animated fadeInRight">
                    <div class="ibox-content p-xl">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5>Sender</h5>
                                    <address>
                                        <strong>Name: <?=$order_data->sender->name;?></strong><br>
                                        <abbr title="Phone">P:</abbr><?=$order_data->sender->mobile; ?>
                                    </address>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <h4>Order No.</h4>
                                    <h4 class="text-navy"><?=$order_data->order_id; ?></h4>
                                    <span>Shipping Address:</span>
                                    <address>
                                        Name:&nbsp;&nbsp; <strong><?=$order_data->address->name; ?></strong><br>
                                        House:&nbsp;&nbsp; <strong><?=$order_data->address->house; ?></strong><br>
                                        Street:&nbsp;&nbsp; <strong><?=$order_data->address->street; ?></strong><br>
                                        City: &nbsp;&nbsp;<strong><?=$order_data->address->city; ?></strong><br>
										State:&nbsp;&nbsp; <strong><?=$order_data->address->state; ?></strong><br>
                                        Landmark:&nbsp;&nbsp; <strong><?=$order_data->address->landmark; ?></strong><br>
                                        Pincode:&nbsp;&nbsp; <strong><?=$order_data->address->pincode; ?></strong><br>
                                        <abbr title="Mobile">M:</abbr> <?=$order_data->address->mobile; ?>
                                    </address>
                                    <p>
                                        <span><strong>Order Date: </strong> <?=date('d-M-Y,h:i a', strtotime($order_data->created_at))?></span><br/>
                                    </p> 
									<p>
                                        <span><strong>Payment Mode: </strong> <?=$order_data->payment_mode?></span><br/>
                                    </p> 
									<p>
                                        <span><strong>Payment Status: </strong> <?=$order_data->payment_status?></span><br/>
                                    </p>
                                </div>
                            </div>

                            <div class="table-responsive m-t">
                                <table class="table invoice-table">
                                    <thead>
                                    <tr>
                                        <th>Item List</th>
                                        <th>Image</th>
                                        <th>Seller Name</th>
                                        <th>O-Status</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Tax</th>
                                        <th>Total Price</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									
									@foreach($ordermeta_data as $vs)
									
                                    <tr>
                                        <td><div><strong>
										{{$vs->product_name}} {{$vs->size}} </strong></div></td>
										<td><img height="100" width="100" src="{{ URL::asset('/public/uploads/seller/catalog') }}/{{$vs->product_image}}"></td>
                                        <td><a href="{{URL::to('admin/user/view/')}}/{{!is_null($vs->seller)?$vs->seller->id:''}}">{{!is_null($vs->seller)?$vs->seller->username:''}}({{!is_null($vs->seller)?$vs->seller->mobile:''}})</a></td>
                                        <td>{{$vs->status}}</td>
                                        <td>{{$vs->qty}}</td>
                                        <td>Rs. {{$vs->price+$vs->shipping_free_amount}}</td>
                                        <td></td>
                                        <td>Rs. {{$vs->price+$vs->shipping_free_amount*$vs->qty}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div><!-- /table-responsive -->
							
							@if($return_video)
								<h2>Return/Echange</h2>
							 <div class="table-responsive m-t">
                                <table class="table invoice-table">
                                    <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Video</th>
                                        <th>Download</th>
                                        <th>O-Status</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Tax</th>
                                        <th>Total Price</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									
									
									@foreach($return_video as $vs1)
                                    <tr>
                                        <td><div><strong>
										{{$vs1->order_meta->product_name}} {{$vs1->order_meta->size}} </strong></div></td>
										<td>
										<video style="width:100px !important;height:100px"controls>
										  <source width="100" height="100" src="{{ URL::asset('/public/admin/uploads/order') }}/{{$vs1->video_name}}" type="video/mp4">
										</video>
										</td>
                                        <td><a href="{{ URL::asset('/public/admin/uploads/order') }}/{{$vs1->video_name}}" download><i class="fa fa-download"></i></a></td>
                                        <td>{{$vs1->order_meta->status}}</td>
                                        <td>{{$vs1->order_meta->qty}}</td>
                                        <td>Rs. {{$vs1->order_meta->price}}</td>
                                        <td></td>
                                        <td>Rs. {{$vs1->order_meta->price*$vs1->order_meta->qty}}</td>
                                        <td><a href="javascript:void(0)" onClick="get_reason({{$vs1->id}})">View Reason</a> &nbsp;&nbsp; | &nbsp;&nbsp;
										  <?PHP if($vs1->order_rma_details->is_approved): ?>
										<a href="javascript:void(0)"><i class="fa fa-toggle-on" style="font-size: x-large;color: green;" aria-hidden="true"></i></a>
										  <?PHP else: ?>
										<a href="{{URL::to('admin/order/approve-for-return/'.$vs1->order_rma_details->order_meta_id)}}"><i class="fa fa-toggle-off" style="font-size: x-large;color: black;" aria-hidden="true"></i></a>
										  <?PHP endif; ?>
										</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div><!-- /table-responsive -->
							 @endif

                            <table class="table invoice-total">
                                <tbody>
                                <tr>
                                    <td><strong>Sub Total :</strong></td>
                                    <td>Rs. <?=$order_data->payment_amount; ?></td>
                                </tr>
								<?php if($order_data->shipping_charge): ?>
                                <tr>
                                    <td><strong>Shipping Charge :</strong></td>
                                    <td>Rs. {{$order_data->shipping_charge}}</td>
                                </tr>
								<?PHP endif; ?>
								
								<?php if($order_data->extra_amount): ?>
                                <tr>
                                    <td><strong>COD Charge :</strong></td>
                                    <td>Rs. {{$order_data->extra_amount}}</td>
                                </tr>
								<?PHP endif; ?>
								
								<?php if($order_data->margin_amount): ?>
                                <tr>
                                    <td><strong>Margin Amount :</strong></td>
                                    <td>Rs. {{$order_data->margin_amount}}</td>
                                </tr>
								<?PHP endif; ?>
								
                                <tr>
                                    <td><strong>TOTAL :</strong></td>
                                    <td>Rs. <?=$order_data->payment_amount+$order_data->extra_amount+$order_data->shipping_charge+$order_data->margin_amount; ?></td>
                                </tr>
                                </tbody>
                            </table>
                           
						  
                           
                        </div>
                </div>
            </div>
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
        <h4 class="modal-title">Reason</h4>
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
@stop
