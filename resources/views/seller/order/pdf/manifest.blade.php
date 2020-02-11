<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Manifest</title>
</head>

<body>
<caption><h2>Cartlay Manifest</h2></caption>
<table style="font-family: sans-serif;border-collapse: collapse;border-top: 1px solid #555; width:100%;" cellpadding="10">
  <tr>
  <th colspan="2"><h3>Service:- <?PHP
  $data=Helper::get_product_for_manifest($order_ids[0]);
  echo $data['shipped_by'];
  ?></h3>
  </th>
  <th></th>
  <th></th>
  <th></th>
  <th></th>
  </tr>
  
  <tr>
  <th>Product Id</th>
  <th>Order Id</th>
  <th>AWB No</th>
  <th>Qty</th>
  <th>Date</th>
  <th>Sign</th>
  </tr>
  
  
  
  @foreach($order_ids as $vs)
   <?PHP $data= Helper::get_product_for_manifest($vs); ?>
  <tr>
    <td><?=$data['product_id'];?></td>
    <td><?=$data['order_id'];?></td>
    <td><?=$data['awb'];?></td>
	<td><?=$data['qty'];?></td>
	<td><?=$data['date'];?></td>
	<td></td>
  </tr>
   @endforeach
   
   
   <tr>
  <th colspan="2">
  <th colspan="2">
  </th>
  <th colspan="2">
  </th>
  </tr>
  
  <tr>
  <th colspan="2">Date (<?=date('d-m-Y');?>)
  <th colspan="2">Name of courier boy
  </th>
  <th colspan="2">
  (Sign Here)
  </th>
  </tr>
</table>
</body>
</html>
