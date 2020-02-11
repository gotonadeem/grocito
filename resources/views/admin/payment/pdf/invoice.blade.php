<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Payment</title>
</head>

<body>

<table style="width: 800px;
    border: 1px solid #999;
    border-collapse: collapse;
    padding: 10px;
    font-size: 20px;
    font-weight: 400;
    color: #333;
    font-family: sans-serif;">
<tbody>
<tr>
<td colspan="2" style="text-align:center; padding:10px; line-height: 25px; border-bottom:1px solid #999;">
<div>PAYMENT ADVICE</div>
<div>AJISSUPPLY PVT LTD</div>
<div>NEAR VAISHALI HOSPITAL</div>
<div>M.K. PLAZA, VAISHALI NAGAR</div>
<div>JAIPUR, RAJATHAN</div>
<div>302021</div>
</td>
</tr>
<tr>
<td style="padding:10px;">Beneficiary's Name :</td>
<td style="padding:10px;"><?=$user->user_kyc->f_name." ".$user->user_kyc->l_name?></td>
</tr>

<tr>
<td style="padding:10px;">Beneficiary's Address : </td>
<td style="padding:10px;"><?=$user->user_kyc->address_1?></td>
</tr>

<tr>
<td style="padding:10px;">Client Ref No :</td>
<td style="padding:10px;">#<?=$user->id?></td>
</tr>

<tr>
<td style="padding:10px;">Date :</td>
<td style="padding:10px;"><?=date('d-m-Y')?></td>
</tr>

<tr>
<td style="padding:10px;">Bank Reference No :</td>
<td style="padding:10px;"><?=$transaction_id?></td>
</tr>

<tr>
<td colspan="2" style="padding:10px;">We have initiated a credit to the Account Number <?PHP echo str_repeat('*', 12).substr($user->user_kyc->account_number,-4);?> for the amount of Rs.<?=$amount?> through NEFT for the below mentioned details. </td>
</tr>

<tr>
<td style="padding:10px;">IFC Code : </td>
<td><?=$user->user_kyc->ifsc_code;?></td>
</tr>

<tr>
<td style="padding:10px;">Micr Code : </td>
<td>&nbsp;</td>
</tr>

<tr>
<td style="padding:10px;">Beneficiary Bank Name :  </td>
<td><?=$bank_name;?></td>
</tr>

<tr>
<td style="padding:10px;">Beneficiary Brn Name :  </td>
<td>&nbsp;</td>
</tr>

<tr>
<td colspan="2" style="padding:20px 10px; border-top:1px solid #999;">This is Computer generated advice. Does not require any signature.</td>
</tr>
</tbody>
</table>

</body>
</html>
