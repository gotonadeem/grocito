<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Bill</title>
</head>

<body>
<table style="font-family: sans-serif;border-collapse: collapse;border-top: 1px solid #555; width:750px;">
  <tr>
    <td style="padding:15px 5px; width:300px;vertical-align: text-top; border-left: 1px solid #666;"><h2 style="margin:0px 0 5px; font-size:30px;">To.</h2>
      <h2 style="margin:0px 0 15px; padding-bottom:25px; font-size:30px;"><?=$order->order->address_details->name?></h2>
      <div>
        <h5 style="margin:0px; padding-bottom:5px; font-size:15px; font-weight:400;"><?=$order->order->address_details->house?>,<?=$order->order->address_details->landmark?>,<?=$order->order->address_details->street?>,</h5>
        <h5 style="margin:0px; padding-bottom:5px; font-size:15px; font-weight:400;"><?=$order->order->address_details->city?> </h5>
        <h5 style="margin:0px; padding-bottom:5px; font-size:15px; font-weight:400;"><?=$order->order->address_details->state?>- <?=$order->order->address_details->pincode?></h5>
        <h5 style="margin:0px; padding-bottom:5px; font-size:15px; font-weight:400;"><span><?=$order->order->address_details->mobile?></span></h5>
      </div></td>
    <td style="padding:15px 0px;vertical-align: text-top; width:80px;"><div style="border:1px solid #666;  padding:5px;">
        <h2 style="margin:0px; padding-bottom:15px; font-size:30px;"><?=$order->order->address_details->pincode?></h2>
        <h5 style="margin:0px; padding-bottom:10px; font-size:15px;"><?=$order->order->address_details->state?></h5>
        <h5 style="margin:0px; padding-bottom:10px; font-size:15px;">
          <div style="border:1px solid #666; padding:5px">Order-No#</div>
        </h5>
        <h4 style="margin:0px;">{{$order->order->order_id}}</h4>
      </div></td>
    <td style="padding:15px 5px 90px; border: 1px solid #666;vertical-align: text-top; width:160px;"> @if($order->order->payment_mode=="cod") <h3 style="margin:0px; padding-bottom:5px; font-weight:400;"> <h2 style="margin:0px; padding-bottom:5px; font-size:28px;">COD Amount: INR {{$order->order->payment_amount+$order->order->shipping_charge+$order->order->margin_amount+$order->order->extra_amount}}</h2> @endif
      <div style="margin:0px; padding-bottom:5px;">
        <div style="border:1px solid #666; padding:5px; font-size:15px;">Quantity: <?=Helper::get_number_of_qty($order->order->id,Auth::user()->id,'delivered');?> Pics</div>
      </div>
      <h2 style="margin:0px; padding-bottom:5px; font-size:28px;">{{$order->order->shipped_by}}</h2>
	  <h6 style="margin:0px; padding-bottom:5px; font-size:28px;">{{$order->order->dock_no}}</h6></td>

  </tr>
  <tr>
    <td style="padding:15px 5px; border-left: 1px solid #666;">&nbsp;</td>
    <td style="padding:15px 5px;">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" style="text-align:center; padding:0px 0px;"><div style="border-top:1px solid #666;">
        <h3 style="font-size:15px;">Declaration letter</h3>
      </div></td>
  </tr>
  <tr>
    <td colspan="3" style="padding:0px 5px;"><div style=" width:250px">
        <div style="font-size:14px;padding-bottom:5px;">Consignment No: {{$order->order->dock_no}}</div>
        <div style="font-size:14px;">Date: <?=date("d-m-Y",strtotime($order->order->created_at)); ?></div>
      </div>
      <!--<div style=" width:250px">
        <div style="font-size:14px;padding-bottom:5px;">Content: Ethnic Wear</div>
        <div style="font-size:14px;">Gift: 100</div>
      </div>
      <div style=" width:250px">
        <div style="font-size:14px; padding-bottom:5px;">Consignment No: {{$order->order->order_id}}</div>
        <div style="font-size:14px;">Date: <?=date("d-m-Y",strtotime($order->order->created_at)); ?></div>
      </div>-->
  </tr>
  <tr>
    <td colspan="3" style="text-align:center; padding:0px 5px;"><div>
        <h5 style="font-size:14px; text-align:left; font-weight:400; font-style:italic;">The above is true to the best of my knowledge and if found false. i/we will only by responsibilty for all cost and consequences arising out of this declaration.</h5>
      </div></td>
  </tr>
  <tr>
    <td style="padding:0px 5px;"><div>Signature of the Consignor:</div></td>
  </tr>
  <tr>
    <td style="padding:0px 5px;"><div><img src="{{ URL::asset('public/admin/uploads/seller/'.$order->seller_kyc->signature) }}" height="100" width="150"></div></td>
  </tr>

</table>
</body>
</html>
