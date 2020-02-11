 @extends('front.layout.front')
@section('content')
<div class="container-fluid my-3">
  <ul class="breadcrumb justify-content-start">
    <li class="breadcrumb-item"><a href="{{URL::to('/')}}">Home</a></li>
    <li class="breadcrumb-item active">Order Detail</li>
  </ul>
</div>
<section class="Order-details my-5">
  <div class="container">
    <div class="row shoping-cart">
      <div class="col-10">
        <h2 class="shoping-cart-text">Order Details</h2>
          <?php if($userOtp){ ?>
        <h2 class="shoping-cart-text">Order Otp: {{$userOtp->delivery_code}}</h2>
          <?php } ?>
      </div>
      <div class="col-2 text-right">
      <a href="{{URL::to('/dashboard-order-invoice')}}/<?=$data->id; ?>" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print Order </a>
      </div>
    </div>
    <div class="Order-details-inner">
      <h4>Order Summary Of Order Id: <span>{{$data->order_id}}</span></h4>
      <div class="row">
        <div class="col-md-6 col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">Delivery Slot</div>
            <div class="panel-body">
                <?php
                $deliveryTime=0;
                $createdAt = $data->created_at;
                if($data->express_time and $data->delivery_type=='express'){
                    $Minutes = $data->express_time;
                    $deliveryTime = date("h:i:s a", strtotime($createdAt)+($Minutes*60));
                }
                ?>
              <p>Order Date: <span>{{$data->created_at}}</span></p>
              <p>Delivery Type: <span>{{$data->delivery_type}}</span></p>
              <p>Delivery Date: <span>{{$data->delivery_date}}</span></p>
              <p>Delivery Time: <span>{{$data->delivery_type=='standard'?$data->delivery_time:$deliveryTime}}</span></p>
              {{--<p>Order Status: <span>{{str_replace('_',' ',$data->status)}}</span></p>--}}
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">Address</div>
            <div class="panel-body">
              <p>{{$data->address ? $data->address->name : ''}}</p>
              <p>{{$data->address ? $data->address->house : ''}} {{$data->address ? $data->address->street :''}}  {{$data->address ?$data->address->city :''}}  {{$data->address ? $data->address->state :''}} ({{$data->address ? $data->address->pincode:''}})</p>
              <p>{{$data->address ? $data->address->type : ''}}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="panel panel-default my-3">
            <div class="panel-heading">Item Details</div>
            <div class="panel-body">
              <div class="table-responsive">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Item Image</th>
                      <th>Item Name</th>
                      <th>Item Weight</th>
                      <th>Price Per Item</th>
                      <th>Item Quantity</th>
                      <th>Seller Name</th>
                      {{--<th>Color</th>--}}
                      {{--<th>Sub Total Amount</th>--}}
                      {{--<th>Item Discount</th>--}}
                      <th>Delivery Tpye</th>
                      <th>Delivery Date</th>
                      <th>Delivery Time</th>
                      <th>Total Amount</th>
                      {{--<th>Status</th>--}}
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php $i = 1; ?>
                  @foreach($order_meta as $order_meta)
                    <tr>
                      <td>{{$i}}</td>
                      <td><img src="{{URL::asset('public/admin/uploads/product/'.$order_meta->product_image)}}" class="img-fluid"></td>
                      <td>{{$order_meta->product_name}}</td>
                      <td>{{$order_meta->weight}}</td>
                      <td><i class="fa fa-inr" aria-hidden="true"></i>{{$order_meta->price}}</td>
                      <td>{{$order_meta->qty}}</td>
                      <td>{{$order_meta->seller->username}}</td>

{{--                      <td><i class="fa fa-inr" aria-hidden="true"></i>{{$order_meta->qty * $order_meta->price}}</td>--}}
{{--                      <td>{{$order_meta->offer_amount}}</td>--}}
                      <td>{{$data->delivery_type}}</td>
                      <td>{{$data->delivery_date}}</td>
                        <?php
                         $createdAt = $data->created_at;
                            if($data->express_time){
                                $Minutes = $data->express_time;
                                $deliveryTime = date("h:i:s a", strtotime($createdAt)+($Minutes*60));
                            }

                        ?>
                      <td>{{$data->delivery_time?$data->delivery_time:$deliveryTime}}</td>
                      <td><i class="fa fa-inr" aria-hidden="true"></i>{{($order_meta->qty * $order_meta->price) - $order_meta->offer_amount}}</td>
{{--                      <td>{{str_replace("_"," ",$order_meta->status)}}--}}

                        <td><?php if($order_meta->status =='delivered'){
                            $getProductRating = Helper::getProductRating(Auth::user()->id,$order_meta->product_id);
                                if($getProductRating){?>
                            <i class="fa fa-star" aria-hidden="true"></i>{{$getProductRating->rating}}
                              <?php  }else{
                            ?>
                            <a href="{{URL::to('user/rating/'.$data->id.'/'.$order_meta->product_id)}}">Review & Rating </a>
                            <?php } }?>
                        </td>
                    </tr>
                      <?php $i++; ?>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 col-sm-12">
          <div class="panel panel-default">
            <div class="panel-heading">Payment Details</div>
            <div class="panel-body">
              <div class="row">
                <div class="col-6">
                  <div class="row"><span class="col-6">Order Id</span> <span class="col-6">{{$data->order_id}}</span></div>
                  <div class="row"><span class="col-6">Payment Option </span> <span class="col-6"> {{$data->payment_mode}} </span></div>
{{--                  <div class="row"><span class="col-6">Net Amount </span> <span class="col-6"><i class="fa fa-inr" aria-hidden="true"></i>{{$data->net_amount}}</span></div>--}}
{{--                  <div class="row"><span class="col-6">SGST Amount</span> <span class="col-6"><i class="fa fa-inr" aria-hidden="true"></i>{{$data->sgst_amount}}</span></div>--}}
{{--                  <div class="row"><span class="col-6">CGST Amount</span> <span class="col-6"><i class="fa fa-inr" aria-hidden="true"></i>{{$data->sgst_amount}}</span></div>--}}
                  <div class="row"><span class="col-6">Sub Total </span> <span class="col-6"><i class="fa fa-inr" aria-hidden="true"></i>{{$data->total_amount}}</span></div>
                  <div class="row"><span class="col-6">Delivery Charges </span> <span class="col-6"><i class="fa fa-inr" aria-hidden="true"></i>{{$data->shipping_charge}}</span></div>
                    <div class="row"><span class="col-6"><strong>Total</strong> </span> <span class="col-6"><strong><i class="fa fa-inr" aria-hidden="true"></i>{{($data->total_amount+$data->shipping_charge)}}</strong></span></div>

                <?php
                  $walletAmount = $data->wallet_amount;
                  ?>
                    <div class="row"><span class="col-6">Wallet Amount Use</span> <span class="col-6">- <i class="fa fa-inr" aria-hidden="true"></i>{{$walletAmount}}</span></div>
                    <div class="row"><span class="col-6"><strong>Payable Amount</strong> </span> <span class="col-6"><strong><i class="fa fa-inr" aria-hidden="true"></i>{{($data->total_amount+$data->shipping_charge) - $walletAmount}}</strong></span></div>
                </div>

              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
      <?php if($data->status !='cancelled'){ ?>
    <div class="order-tracking">
        <?php $trackingData = Helper::getOrderTrackingStatus($data->id);
            if($trackingData){
        $sellerAccept = 'Pending';
        $order_placed ='';
        $assign_to_rider ='';
        $ready_to_shiped = '';
        $assign_to_rider_to_deliverd = '';
        $delivered = '';
        if($trackingData->type =='pending'){
            $order_placed ='active';
        }else if($trackingData->type =='assign_to_rider'){
            $order_placed ='active';
            $assign_to_rider ='active';
            $sellerAccept = 'Accept';
        }else if($trackingData->type =='ready_to_shiped'){
            $order_placed ='active';
            $assign_to_rider ='active';
            $sellerAccept = 'Accept';
            $ready_to_shiped = 'active';

        }else if($trackingData->type =='assign_to_rider_to_deliverd'){
            $order_placed ='active';
            $assign_to_rider ='active';
            $sellerAccept = 'Accept';
            $ready_to_shiped = 'active';
            $assign_to_rider_to_deliverd = 'active';
        }else if($trackingData->type =='delivered'){
            $order_placed ='active';
            $assign_to_rider ='active';
            $sellerAccept = 'Accept';
            $ready_to_shiped = 'active';
            $assign_to_rider_to_deliverd = 'active';
            $delivered = 'active';
        }

        ?>
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
              <i class="fa fa-check-square-o"></i>
              <p>Order Accepted</p>
            </li>
            <li class="tracking-border {{$ready_to_shiped}}">
            <small></small>
            </li>

            <li class="tracking-point {{$ready_to_shiped}}">
            <i class="fa fa-archive"></i>
             <p>Ready to Shiped</p>
            </li>
            <li class="tracking-border {{$assign_to_rider_to_deliverd}}">
            <small></small>
            </li>

            <li class="tracking-point {{$assign_to_rider_to_deliverd}}">
            <i class="fa fa-truck"></i>
              <p>On the way to deliverd</p>
            </li>
            <li class="tracking-border {{$delivered}}">
            <small></small>
            </li>
            <li class="tracking-point {{$delivered}}">
            <i class="fa fa-dropbox"></i>
             <p>Deliverd</p>
            </li>
      </ul>
            <?php } ?>
  </div>
      <?php } ?>
</div>
</section>
<div id="myModalReturn" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div id="c_message"></div>
      <div class="modal-header">
        <h4 class="title-signup m-0">Return Sub Order <span id="order_id"></span></h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
          <label>Select Reason</label>
          <select name="reason" id="reason" class="form-control">
            <option value="">Select Reason</option>
            <option>Product is Missing</option>
            <option>Product is Expired</option>
            <option>Product Condition is not good</option>
            <option>Product is damaged</option>
            <option>Other Reason</option>
          </select>
          <span class="error" id="reason_msg"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="submit_return" class="btn btn-primary custom-btn">Submit</button>
      </div>
    </div>
  </div>
</div>
<!-- order exchange -->
<div id="myModalExchange" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div id="ex_message"></div>
            <div class="modal-header-custom">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="text-center title-signup">Exchange Order <span id="order_id"></span></h4>
            </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <label>Select Reason</label>
                    <select name="exchange_reason" id="exchange_reason" class="form-control">
                        <option value="">Select Reason</option>
                        <option>Product is Expired</option>
                        <option>Product is Damaged</option>
                        <option>Product Condition is not good</option>
                        <option>Product Size Issue</option>
                    </select>
                    <span class="error" id="exchange_reason_msg"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="submit_exchange" class="btn btn-primary custom-btn">Submit</button>
            </div>
        </div>
    </div>
</div>
@section('scripts')
  <script language="javascript" src="{{ URL::asset('public/front/developer/js/page_js/return_now.js') }}"></script>
  <script language="javascript" src="{{ URL::asset('public/front/developer/js/page_js/exchange_now.js') }}"></script>
  <script language="javascript" src="{{ URL::asset('public/js/jquery.star.rating.js') }}"></script>
  <script>
    $(document).ready(function(){
      $('.rating').addRating();
    })

</script>
@stop
@endSection