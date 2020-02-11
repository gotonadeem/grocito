@extends('seller.layouts.seller')
@section('content')
<?PHP
$months = array (1=>'Jan',2=>'Feb',3=>'Mar',4=>'Apr',5=>'May',6=>'Jun',7=>'Jul',8=>'Aug',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Dec');
?>
<div id="rightSidenav" class="right_side_bar right_side_bar_new">
  <div class="graph_div">
    <div class="graph_list">
      <ul>
        <li>
          <A style="text-decoration:none" href="{{URL::to('seller/order')}}"><div class="graph_menu blue_light"><div class="budge">{{$not_shipped_orders}}</div><i class="fa fa-truck" aria-hidden="true"></i><span>Total Pending order</span></div></a>
        </li>
       <!-- <li>
         <A style="text-decoration:none" href="{{URL::to('seller/cancellation-risk')}}"> <div class="graph_menu red_light"><div class="budge">{{$cancellation_risk_orders}}</div><i class="fa fa-ban" aria-hidden="true"></i><span>Cancellation Risk</span></div></a>
        </li>-->
        <li>
          <div class="graph_menu blue_dark"><div class="budge">{{round($total_sale ,2)}}</div><i class="fa fa-shopping-cart" aria-hidden="true"></i><span>Total Sales</span></div>
        </li>
          <li>
              <A style="text-decoration:none" href="{{URL::to('seller/next-payment-details')}}"><div class="graph_menu blue_light"><div class="budge">{{round($total_today_payable_amount,2)}}</div><i class="fa fa-truck" aria-hidden="true"></i><span>Today Amount</span></div></a>
          </li>

       
      </ul>
    </div>
  </div>
  <!--<div class="graph_status">
    <div class="rows">
      <div class="col-lg-12"> 
        
        
        <div class="border-head">
          <h3>Number of executive order</h3>
        </div>
        <div class="custom-bar-chart">
          <ul class="y-axis">
            <li><span>800</span></li>
            <li><span>400</span></li>
            <li><span>200</span></li>
            <li><span>100</span></li>
            <li><span>50</span></li>
            <li><span>0</span></li>
          </ul>
		  <?PHP foreach($monthsData as $vs): ?>
          <div class="bar">
            <div class="title"><?=$months[(int)$vs->month_name];?></div>
            <div data-toggle="tooltip" data-placement="top" title="<?=$vs->total_order?>" class="value tooltips" style="height: <?=$vs->total_order/2.5?>%;"></div>
          </div>
		  <?PHP endforeach; ?>   
        </div>
        
       
        
      </div>
    </div>
  </div>-->
</div>

<script>

    $(document).ready(function(){

        $('[data-toggle="tooltip"]').tooltip();

    });

</script>

@endsection