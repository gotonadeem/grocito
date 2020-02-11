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
                        <a href="{{URL::previous()}}"  class="btn btn-primary"> Back </a>
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
                                </div>
                                <div class="col-sm-6 text-right">
                                    <h4>Order No.</h4>
                                    <h4 class="text-navy"><?=$order_data->order_id; ?></h4>
                                    <span>Shipping Address:</span>
                                    <address>
                                        Name:&nbsp;&nbsp; <strong><?=$order_data->address ?$order_data->address->name :''; ?></strong><br>
                                        House:&nbsp;&nbsp; <strong><?=$order_data->address ?$order_data->address->house :''; ?></strong><br>
                                        Street:&nbsp;&nbsp; <strong><?=$order_data->address ? $order_data->address->street:''; ?></strong><br>
                                        City: &nbsp;&nbsp;<strong><?=$order_data->address ?$order_data->address->city:''; ?></strong><br>
										State:&nbsp;&nbsp; <strong><?=$order_data->address ?$order_data->address->state :''; ?></strong><br>
                                        Pincode:&nbsp;&nbsp; <strong><?=$order_data->address ? $order_data->address->pincode :''; ?></strong><br>

                                    </address>
                                    <p>
                                        <span><strong>Order Date: </strong> <?=date('d-M-Y,h:i a', strtotime($order_data->created_at))?></span><br/>
                                    </p>
                                    <p>
                                        <span><strong>Expected Delivery Date: </strong> <?= date('d-M-Y', strtotime($order_data->delivery_date)).' '.$order_data->delivery_time?></span><br/>
                                    </p>
                                    <p>
                                        <span><strong>Delivery Type: </strong> <?=$order_data->delivery_type?></span><br/>
                                    </p>
                                    <p>
                                        <span><strong>Payment Mode: </strong> <?=$order_data->payment_mode?></span><br/>
                                    </p> 
									<p>

                                        <span><strong>Payment Status: </strong> <?=$order_data->payment_status =='faild'?'failed':$order_data->payment_status ?></span><br/>
                                    </p>
                                </div>
                            </div>

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
                                        <th>Admin Commission</th>
                                        <th>Total Price</th>
                                        
                                    </tr>
                                    </thead>
                                    <tbody>
									
									@foreach($ordermeta_data as $vs)
									
                                    <tr>
                                        <td><div><strong>
										{{$vs->id}}</strong></div></td>
										
										<td><div><strong>
										
										{{$vs->product_name}} {{$vs->size}} </strong></div></td>
										<td><img height="100" width="100" src="{{ URL::asset('/public/admin/uploads/product') }}/{{$vs->product_image}}"></td>
                                        <td><a href="{{URL::to('admin/user/view/')}}/{{!is_null($vs->seller)?$vs->seller->id:''}}">{{!is_null($vs->seller)?$vs->seller->username:''}}({{!is_null($vs->seller)?$vs->seller->mobile:''}})</a></td>
                                        <td>{{$vs->status}}</td>

                                        <td>{{$vs->qty}}</td>
                                        <td>Rs. {{$vs->price+$vs->shipping_free_amount}}</td>
                                        <td>{{$vs->weight}}</td>
                                        <!-- <td><?PHP
                                            $data=json_decode($vs->attributes);
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
                                        <td>{{$vs->product_commission}}</td>
                                        <td>Rs. {{$vs->price+$vs->shipping_free_amount*$vs->qty}}</td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div><!-- /table-responsive -->
							
							
							@if(count($return_video)>0)
								<h2>Return/Exchange</h2>
							 <div class="table-responsive m-t">
                                <table class="table invoice-table">
                                    <thead>
                                    <tr>
                                        <th>Item Id</th>
                                        <th>Product Name</th>
                                        <th>Video</th>
                                        <th>Download</th>
                                        <th>O-Status</th>
                                        <th>Qty</th>
                                        <th>Unit Price</th>
                                        <th>Weight(gm)</th>
                                        <th>AWB No</th>
                                        <th>Total Price</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									@foreach($return_video as $vs1)
                                    <tr>
									    <td>{{$vs1->order_meta->id}}</td>
                                        <td><div><strong>
										{{$vs1->order_meta->product_name}} {{$vs1->order_meta->size}} </strong></div></td>
										<td>
										<video style="width:100px !important;height:100px" controls>
										  <source width="100" height="100" src="{{ URL::asset('/public/admin/uploads/order') }}/{{$vs1->video_name}}" type="video/mp4">
										</video>
										</td>
                                        <td><a href="{{ URL::asset('/public/admin/uploads/order') }}/{{$vs1->video_name}}" download><i class="fa fa-download"></i></a></td>
                                        <td>{{$vs1->order_meta->status}}</td>
                                        <td>{{$vs1->order_meta->qty}}</td>
                                        <td>Rs. {{$vs1->order_meta->price}}</td>
                                        <td>{{$vs1->order_meta->weight}}</td>
                                        <td>
										   
										</td>
                                        <td>Rs. {{$vs1->order_meta->price*$vs1->order_meta->qty}}</td>
                                        <td>
										<a href="javascript:void(0)" title="View Reason" onClick="get_reason({{$vs1->order_meta->id}})"><i class="fa fa-eye" style="font-size:large" aria-hidden="true"></i></a> &nbsp;&nbsp; | &nbsp;&nbsp;
										
										  <?PHP 
										  if(!is_null($vs1->order_rma_details)):
										     
										  if($vs1->order_rma_details->is_approved==1): ?>
										<a href="javascript:void(0)"><i class="fa fa-toggle-on" style="font-size:large;color: green;" aria-hidden="true"></i></a>
										  <?PHP else: ?>
										<a title="Click To Approve" onClick="return confirm('Are you sure to approve?')" href="{{URL::to('admin/order/approve-for-return/'.$vs1->order_rma_details->order_meta_id.'/'.$vs1->order_rma_details->order_id)}}"><i class="fa fa-toggle-off" style="font-size:large;color: black;" aria-hidden="true"></i></a> &nbsp; | &nbsp;
                                    <?PHP if($vs1->order_rma_details->is_approved==0): ?>     
                                  <a title="Click To UnApprove" title="UnApprove" onClick="return confirm('Are you sure to unapprove?')" href="{{URL::to('admin/order/unapprove-for-return/'.$vs1->order_rma_details->order_meta_id.'/'.$vs1->order_rma_details->order_id)}}"><i class="fa fa-ban ban" aria-hidden="true"></i></a>       
									 <?PHP elseif($vs1->order_rma_details->is_approved==2): ?>
                                     <a title="UnApproved" href="javascript:void(0)"><i class="fa fa-ban banned" aria-hidden="true"></i></a>       
									  <?PHP endif; ?>
                                      	 	  
										  <?PHP 
										  endif;
										  endif; ?>
										  
										  <?PHP 
										  if(!is_null($vs1->order_exchanges)):
										     
										  if($vs1->order_exchanges->status=="completed"): ?>
										  <a href="javascript:void(0)" onClick="get_reason({{$vs1->order_exchanges->order_meta_id}})">View Reason</a> &nbsp;&nbsp; | &nbsp;&nbsp;
										<a href="javascript:void(0)"><i class="fa fa-toggle-on" style="font-size:large;color: green;" aria-hidden="true"></i></a>
										  <?PHP else: ?>
										<a  title="Click To Approve" onClick="return confirm('Are you sure to approve?')" href="{{URL::to('admin/order/approve-for-exchange/'.$vs1->order_exchanges->order_meta_id.'/'.$vs1->order_exchanges->order_id)}}"><i class="fa fa-toggle-off" style="font-size:large;color: black;" aria-hidden="true"></i></a> |
                                           <?PHP if($vs1->order_exchanges->status=='pending'): ?>     
                                  <a title="Click To UnApprove" onClick="return confirm('Are you sure to uapprove?')" title="UnApprove" href="{{URL::to('admin/order/unapprove-for-exchange/'.$vs1->order_exchanges->order_meta_id.'/'.$vs1->order_exchanges->order_id)}}"><i class="fa fa-ban ban" aria-hidden="true"></i></a>       
									 <?PHP elseif($vs1->order_exchanges->status=='unapproved'): ?>
                                     <a title="UnApproved" href="javascript:void(0)"><i class="fa fa-ban banned" aria-hidden="true"></i></a>       
									  <?PHP endif; ?>
                                      
										  <?PHP 
										  endif;
										   ?> |   <A href="javascript:void(0)" onClick="get_exchange_details({{$vs1->order_exchanges->order_meta_id}})">Exchange With</a>
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
                                    <td>Rs. <?=$order_data->total_amount; ?></td>
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
								
								<!-- <tr>
                                    <td><strong>Net Payable To Seller :</strong></td>
                                    <td>Rs. 
									
									<?php $p_amount=$order_data->total_amount;
									  echo $p_amount;
									?></td>
                                </tr> -->
								
								<?php if($order_data->margin_amount): ?>
                                <tr>
                                    <td><strong>Margin Amount :</strong></td>
                                    <td>Rs. {{$order_data->margin_amount}}</td>
                                </tr>
								<?PHP endif; ?>
								
                                <tr>
                                    <td><strong>TOTAL :</strong></td>
                                    <td>Rs. <?=$total=$order_data->total_amount+$order_data->shipping_charge; ?></td>
                                </tr>
								
								
								
                                </tbody>
                            </table>
                           
						  
                           
                        </div>
                </div>
            </div>
			
        </div>
		  <div class="row">
		  
		              {{-- @include('common.track_order') --}}
						
		  </div>
                    <?php $trackingData = Helper::getOrderTrackingStatus($order_data->id);
                        if($trackingData){
                    $sellerAccept = 'Pending';
                    $order_placed ='';
                    $assign_to_rider ='';
                    $delivered ='';
                    $assign_to_rider_to_deliverd ='';
                    $assign_to_warehouse ='';
                    if($trackingData->type =='pending'){
                        $order_placed ='active';
                    }else if($trackingData->type =='assign_to_rider'){
                        $order_placed ='active';
                        $assign_to_rider ='active';
                        $sellerAccept = 'Accept';
                    }
                    else if($trackingData->type =='assign_to_warehouse'){
                        $order_placed ='active';
                        $assign_to_rider ='active';
                        $sellerAccept = 'Accept';
                        $assign_to_warehouse ='active';
                    }
                    else if($trackingData->type =='assign_to_rider_to_deliverd'){
                        $order_placed ='active';
                        $assign_to_rider ='active';
                        $sellerAccept = 'Accept';
                        $assign_to_warehouse ='active';
                        $assign_to_rider_to_deliverd ='active';
                    }
                    else if($trackingData->type =='delivered'){
                        $order_placed ='active';
                        $assign_to_rider ='active';
                        $sellerAccept = 'Accept';
                        $assign_to_warehouse ='active';
                        $assign_to_rider_to_deliverd ='active';
                        $delivered ='active';
                    }
                    ?>
                   <div class="admin-order-tracking">
                   <ul class="clearfix">
                        <li class="tracking-point {{$order_placed}}">
                            <i class="fa fa-calendar-check-o"></i>
                            <p>Order Placed</p>
                            <span>{{$trackingData->date}}</span>
                        </li>
                        <li class="tracking-border {{$assign_to_rider}}">
                            <small></small>
                        </li>
                        <li class="tracking-point {{$assign_to_rider}}">
                            <i class="fa fa-user"></i>
                            <p>Assign to vendor</p>
                            <span>{{$sellerAccept}}</span>
                        </li>
                        <li class="tracking-border {{$assign_to_rider}}">
                            <small></small>
                        </li>
                        <li class="tracking-point {{$assign_to_rider}}">
                            <i class="fa fa-truck"></i>
                            <p>Assign to rider</p>
                        </li>
                        <li class="tracking-border {{$assign_to_warehouse}}">
                            <small></small>
                        </li>
                        <li class="tracking-point {{$assign_to_warehouse}}">
                            <i class="fa fa-shopping-basket"></i>
                            <p>Assign to warehouse</p>
                        </li>
                        <li class="tracking-border {{$assign_to_rider_to_deliverd}}">
                            <small></small>
                        </li>
                        <li class="tracking-point {{$assign_to_rider_to_deliverd}}">
                            <i class="fa fa-archive"></i>
                            <p>Assign to rider to deliverd</p>
                        </li>
                        <li class="tracking-border {{$delivered}}">
                            <small></small>
                        </li>
                        <li class="tracking-point {{$delivered}}">
                            <i class="fa fa-dropbox"></i>
                            <p>Deliverd</p>
                        </li>
                    </ul>
                   </div>
                    <?php } ?>
                    <div class="text-center mt-3 ">
                        <p style="color: #000" ><b>Warehouse Name: {{$order_data->get_warehouse['name']}}</b></p>

                        <p style="color: #000"><b>Delivery Boy Name: {{$deliveryBoy?$deliveryBoy->get_delivery_boy['username']:''}}</b></p>
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
