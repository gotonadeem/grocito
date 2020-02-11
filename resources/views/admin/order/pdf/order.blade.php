<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Bill</title>
</head>
<body>
<table style="font-family: sans-serif;border-collapse: collapse;">
  <tr style="border-top:1px solid #666;">
    <td></td>
    <td style="width:300px"><img  style="width:300px" width="300" src="{{ URL::asset('/public/images/logo.png') }}"></td>

  </tr>
  <tr>
    <td style="padding:15px 5px; width:300px;vertical-align: text-top;">
      <div>
        <h4>Order No.  <?=$order->order->order_id; ?></h4>
        <span>Shipping Address:</span>
        <address>
          Name:&nbsp;&nbsp; <strong><?=$order->order->address->name; ?></strong><br>
          House:&nbsp;&nbsp; <strong><?=$order->order->address->house; ?></strong><br>
          Street:&nbsp;&nbsp; <strong><?=$order->order->address->street; ?></strong><br>
          City: &nbsp;&nbsp;<strong><?=$order->order->address->city; ?></strong><br>
          State:&nbsp;&nbsp; <strong><?=$order->order->address->state; ?></strong><br>
          Landmark:&nbsp;&nbsp; <strong><?=$order->order->address->landmark; ?></strong><br>
          Pincode:&nbsp;&nbsp; <strong><?=$order->order->address->pincode; ?></strong><br>
          <abbr title="Mobile">M:</abbr> <?=$order->order->address->mobile; ?>
        </address>
        <p>
          <span><strong>Order Date: </strong> <?=date('d-M-Y,h:i a', strtotime($order->order->created_at))?></span>
          <br/>
        </p>
        <p>
          <span><strong>Payment Mode: </strong> <?=$order->order->payment_mode?></span>
          <br/>
        </p>
        <p>
          <span><strong>Payment Status: </strong> <?=$order->order->payment_status?></span>
          <br/>
        </p>

      </div>
    </td>

  </tr>

  <tr style="border-top:1px solid #666;">
    <td colspan="4"></td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center; padding:0px 0px;">
      <div style="border-top:1px solid #666;">
        <h3 style="font-size:15px;"></h3>
      </div>
    </td>
  </tr>
</table>

<table style="width:100%" width="100%" class="table invoice-table" border="1">
  <thead>
  <tr>
    <th style='font-weight:normal'>Item Id</th>
    <th style='font-weight:normal'>Item List</th>
    <th style='font-weight:normal'>Image</th>
    <th style='font-weight:normal'>O-Status</th>
    <th style='font-weight:normal'>Qty</th>
    <th style='font-weight:normal'>Unit Price</th>
    <th style='font-weight:normal'>Weight(gm)</th>
    <th style='font-weight:normal'>Total Price</th>

  </tr>
  </thead>
  <tbody>

  @foreach($order_meta as $vs)
    <tr>
      <td>
        <div><strong>{{$vs->id}}</strong></div>
      </td>
      <td>
        <div><strong>{{$vs->product_name}} {{$vs->size}} </strong></div>
      </td>
      <td><img height="100" width="100" src="{{ URL::asset('/public/admin/uploads/product') }}/{{$vs->product_image}}"></td>
      <td>{{$vs->status}}</td>
      <td>{{$vs->qty}}</td>
      <td>Rs. {{$vs->price}}</td>
      <td>{{$vs->weight}}</td>
      <td>Rs. {{$vs->price*$vs->qty}}</td>
    </tr>
  @endforeach
  </tbody>
</table>

<table class="table invoice-table">
  <tr>
    <td colspan="3" style="padding:0px 5px; margin-top:10px;">
      <div style=" width:250px">
        <div style="font-size:14px;padding-bottom:5px;font-weight:bold;">Sub Total : Rs.
          <?=$order->order->total_amount; ?>
        </div>
      </div>
      <div style=" width:250px">
        <div style="font-size:14px;padding-bottom:5px;font-weight:bold;">Shipping Charge: Rs. {{$order->order->shipping_charge}}</div>
        <div style="font-size:14px;"></div>
      </div>
      <div style=" width:250px">
        <div style="font-size:14px; padding-bottom:5px;font-weight:bold;">TOTAL : Rs.
          <?=$order->order->total_amount+$order->order->shipping_charge; ?>
        </div>

      </div>
    </td>
  </tr>

</table>
</body>
</html>