<?PHP 
$page=array("dashboard","payment","catalog","payment-details");
if($read_count)
{
$read_notice= count(explode(",",$read_count->notice_id));
}
else
{
 $read_notice=0;
}
$notice_count= $notice_count-$read_notice;
 ?>
<!-- dashboard-->
<div id="mySidenav" class="sidenav left_side_bar left_side_bar_new">
<div class="remove-window" onclick="myFunctiondiv()"></div>
    <div class="col-md-12">
        <div class="row">
            <ul>
                <li class="<?PHP if(Request::segment(2)=="dashboard") { echo "sidebar_active"; } ?>"><a href="{{ URL::to('/seller/dashboard') }}"><i class="fa fa-tachometer" aria-hidden="true"></i><span>Dashboard</span></a></li>
                 <?PHP if(Auth::user()->verify_status=="verified"): ?>
				<li class="<?PHP if(Request::segment(2)=="order" or Request::segment(2)=="to-be-dispatched" or Request::segment(2)=="ready-to-ship" or Request::segment(2)=="shipped-order" or Request::segment(2)=="cancelled-order") { echo "sidebar_active"; } ?>"><a href="{{ URL::to('/seller/order') }}"><i class="fa fa-shopping-bag"></i><span>Order</span></a></li>
				
                <li class="<?PHP if(Request::segment(2)=="inventory" or Request::segment(2)=="product-by-category" or Request::segment(2)=="out-of-stock") { echo "sidebar_active"; } ?>"><a href="{{ URL::to('/seller/inventory') }}"><i class="glyphicon glyphicon-folder-close"></i><span>Inventory</span></a></li>
                <li class="<?PHP if(Request::segment(2)=="payment") { echo "sidebar_active"; } ?>"><a href="{{ URL::to('/seller/payment') }}"><i class="fa fa-credit-card"></i><span>Payment</span></a></li>
                <li class="<?PHP if(Request::segment(2)=="catalog" or Request::segment(2)=="catalog-edit") { echo "sidebar_active"; } ?>"><a href="{{ URL::to('/seller/catalog') }}"><i class="fa fa-list-alt"></i><span>Product Upload</span></a></li>
                <li class="<?PHP if(Request::segment(2)=="duplicate-product" or Request::segment(2)=="duplicate-product") { echo "sidebar_active"; } ?>"><a href="{{ URL::to('/seller/duplicate-product') }}"><i class="fa fa-list-alt"></i><span>Duplicate Product </span></a></li>
                <li class="<?PHP if(Request::segment(2)=="offer-product" or Request::segment(2)=="offer-product-add") { echo "sidebar_active"; } ?>"><a href="{{ URL::to('/seller/scheme-product') }}"><i class="fa fa-list-alt"></i><span>Scheme</span></a></li>
                <?php if($notice_count>0):
				     $url="/seller/notice/count";
				    else: 
				     $url="/seller/notice";
				   endif;				   ?>
				<li class="<?PHP if(Request::segment(2)=="notice") { echo "sidebar_active"; } ?>"><a href="{{ URL::to($url) }}"><i class="glyphicon glyphicon-list"></i><span>Notice Board <span class="notification"><i class="fa fa-bell"></i> @if($notice_count>0) <b style="color:#ffd400"> {{$notice_count}}</b> 
                </span>@endif </span></a></li>
				<?PHP
				endif;
				?>
            </ul>
        </div>
    </div>
</div>
