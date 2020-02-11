<?php
namespace App\Helpers;
use App\ProductCategory;
use App\Category;
use App\CategoryStockStatus;
use App\Country;
use App\State;
use App\SellerPenalty;
use DB;
use Auth;
use DateTime;
use App\Order;
use App\City;
use App\Warehouse;
use DateInterval;
use App\Payment;
use App\UserKyc;
use App\Wallet;
use App\WithdrawWallet;
use App\SubadminAccess;
use App\GeneralSetting;
use App\PushNotification;
use App\ProductSponsor;
use App\ResellerPayment;
use App\DeliveryPincode;
use App\OrderRmaDetail;
use App\DeliveryBoyPayment;
use App\OrderExchange;
use App\OrderTracking;
use App\SubCategory;
use App\Product;
use App\SuperSubCategory;
use App\ProductRating;
use App\ProductImage;
use App\OrderMeta;
use App\ProductItem;
use App\SchemeProduct;
use App\SellerDuplicateProduct;
use App\Pincode;
use App\Cart;
use App\User;
use App\ReferralSetting;
use App\AdminNotification;
use App\Cashback;
class Helper
{
	public static function getAdminNotifyCount(){
		return $notify_count= AdminNotification::where('status',0)->get()->count();
	}
	public static function getAdminNotification(){
		$After7Days = \Carbon\Carbon::today()->addDays(7);
		return AdminNotification::whereDate('created_at','<=',$After7Days)->orderBy('id','desc')->get();
	}
	public static function getSubadminWarehouse($subAdminId){
		return $query = \DB::table("warehouses")
			->select('warehouses.*')
			->whereRaw("find_in_set($subAdminId,warehouses.subadmin_id)")->first();
	}
	public static function getReffCode($ref_id){
		if($ref_id){
			$user = User::where('id',$ref_id)->first()->reff_code;
			if($user){
				return $user;
			}else{
				return '';
			}
		}else{
			return '';
		}
	}
	public static function getProductById($pId){
		$data =Product::where('id',$pId)->first();
		if($data){
			return $data;
		}else{
			return [];
		}
	}
	public static function getOrderById($oId){
		$data =Order::where('id',$oId)->first();
		if($data){
			return $data;
		}else{
			return [];
		}
	}
	public static function getSellerById($sId){
		$data =User::where('id',$sId)->first();
		if($data){
			return $data;
		}else{
			return [];
		}
	}
	public static function getUserById($sId){
		$data =User::where('id',$sId)->first();
		if($data){
			return $data;
		}else{
			return [];
		}
	}

	public static function getSellerReturnPenaltyToday(){

		return $data = SellerPenalty::where('seller_id',Auth::user()->id)->where('type','return')->whereDate('created_at','=',date('Y-m-d'))->sum('amount');
	}
	public static function getSellerReturnPenaltyAmount($seller_id,$orderDate){

		return $data = SellerPenalty::where('seller_id',$seller_id)->where('type','return')->whereDate('created_at','=',$orderDate)->sum('amount');
	}
	public static function getSellerExchangePenaltyAmount($seller_id,$orderDate){

		return $data = SellerPenalty::where('seller_id',$seller_id)->where('type','exchange')->whereDate('created_at','=',$orderDate)->sum('amount');

	}
	public static function getOrderMeta($metaId){
		$data = OrderMeta::where('id',$metaId)->first();
		if($data){
			return $data;
		}else{
			return [];
		}
	}
	public static function getPendingOrderByDate($date,$sellerId){
		$orderdata = Order::where('shipped_date',$date)->where('seller_id',$sellerId)->where('status','delivered')->get();
		if($orderdata){
			return $orderdata;
		}else{
			return [];
		}
	}
	public static function getPendingOrderByDateNew($date,$sellerId){
		//$orderdata = Order::where('shipped_date',$date)->where('seller_id',$sellerId)->where('status','delivered')->get();
		$todayPaymentData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
              SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			order_metas.delivery_date = '$date' and order_metas.seller_id='$sellerId' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ");
		if($todayPaymentData){
			return $todayPaymentData;
		}else{
			return [];
		}
	}
	//unique code
	public static function unique_code($limit)
	{
		return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);
	}
	public static function getOrderTrackingStatusForApp($orderId){
		$status = OrderTracking::where('order_id',$orderId)->get();

		if($status){
			return $status;
		}else{
			return [];
		}
	}
	public static function getOrderTrackingStatus($orderId){
		$status = OrderTracking::where('order_id',$orderId)->orderBy('id', 'DESC')->first();

		if($status){
			return $status;
		}else{
			return [];
		}
	}
	public static function updateOrderStatus($order_id,$status,$reason){
		$data['order_id'] = $order_id;
		$data['type'] = $status;
		$data['reason'] = $reason;
		$data['date'] = date('Y-m-d H:i:s');
		$updateStatus = DB::table('order_trackings')->insert($data);
		if($updateStatus){
			return true;
		}else{
			return false;
		}
	}
	public static function get_order_return_status($order_id,$meta_id)
	{
		$status= OrderRmaDetail::where('order_id',$order_id)->where('order_meta_id',$meta_id)->first();
		return $status;
	}
	public static function get_order_exchange_status($order_id,$meta_id)
	{
		$status= OrderExchange::where('order_id',$order_id)->where('order_meta_id',$meta_id)->first();
		return $status;
	}
	public static function get_wallet($user_id)
	{
		$deposit= Wallet::with('user')->where('user_id',$user_id)->where('type','deposit')->sum('amount');
		$withdraw= Wallet::with('user')->where('user_id',$user_id)->where('type','withdraw')->sum('amount');
		$total = $deposit - $withdraw;
		
		return (round($total,2));
	}

	public static function getSellerName($sellerId){
		$userData= User::where('id',$sellerId)->first();
		if($userData){
			echo $userData['username'];
		}else{
			echo '';
		}

	}
	public static function getProductItemBySellerId($productId,$sellerId){
		$item = ProductItem::where('seller_id',$sellerId)->where('product_id',$productId)->get();
		if($item){
			return $item;
		}else{
			return [];
		}
	}
	public static function getProductByCat($catId,$subCatId=''){
		$sessionPincode = session('pincode');
		if($sessionPincode){

			$query = \DB::table("user_kyc")
				->join('users', 'users.id', '=', 'user_kyc.user_id')
				->join('products', 'products.user_id', '=', 'user_kyc.user_id')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
				//->leftJoin('scheme_products', 'scheme_products.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
				->where('products.is_admin_approved',1)
				->where('users.verify_status','=','verified')
				->orderBy('products.id','desc')
				//->limit(15)
				->groupBy('products.id')->distinct('products.id');
			if ($subCatId){
				$query->where('products.sub_category_id', $subCatId);
			}else{
				$query->where('products.category_id', $catId);
			}
			$allCatProduct = $query->get();
		}else{
			$allCatProduct = \DB::table("products")
				->select('products.*')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->where('products.status',1)
				->select('products.*','product_images.image')
				->where('products.category_id',$catId)
				->limit(15)
				->where('products.is_admin_approved',1)
				->get();
		}

		return $allCatProduct;
	}
	public static function getProductByType($type){
		$sessionPincode = session('pincode');
		if($sessionPincode){

			$query = \DB::table("user_kyc")
				->join('users', 'users.id', '=', 'user_kyc.user_id')
				->join('products', 'products.user_id', '=', 'user_kyc.user_id')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
				//->leftJoin('scheme_products', 'scheme_products.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
				->where('products.is_admin_approved',1)
				->where('users.verify_status','=','verified')
				->orderBy('products.id','DESC')
				->limit(15)
			    ->groupBy('products.id')->distinct('products.id');
			if($type != 'new' ){
				$query->where($type,1);
			}

			$allCatProduct = $query->get();
		}else{
			/*$allCatProduct = \DB::table("products")
				->select('products.*')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->where('products.status',1)
				->select('products.*','product_images.image')
				->limit(15)
				->where('products.is_admin_approved',1)
				->groupBy('products.id');
			if($type != 'new' ){
				$allCatProduct->where($type,1);
			}
			$allCatProduct->get();*/
			$allCatProduct=[];
		}

		return $allCatProduct;
	}
	public static function getApiProductByType($type,$sessionPincode){
		if($sessionPincode){

			$query = \DB::table("user_kyc")
				->join('users', 'users.id', '=', 'user_kyc.user_id')
				->join('products', 'products.user_id', '=', 'user_kyc.user_id')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
				//->leftJoin('scheme_products', 'scheme_products.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
				->where('products.is_admin_approved',1)
				->orderBy('products.id','DESC')
				->where('users.verify_status','=','verified')
				->limit(15)
				->groupBy('products.id')->distinct('products.id');
			if($type != 'new'){
				$query->where($type,1);
			}

			$allCatProduct = $query->get();
		}else{
			$allCatProduct = \DB::table("products")
				->select('products.*')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->where('products.status',1)
				->select('products.*','product_images.image')
				->where($type,1)
				->where('products.is_admin_approved',1)
				->groupBy('products.id')
				->get();
			$allCatProduct = [];
		}

		return $allCatProduct;
	}
	//new for product type filter......
	public static function getApiProductListingByTypeWithFilter($type,$sessionPincode,$brandId='',$min_price='',$max_price='',$min_offer='',$max_offer=''){
		if($sessionPincode){

			$query = \DB::table("user_kyc")
				->join('products', 'products.user_id', '=', 'user_kyc.user_id')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
				->orderBy('products.id','desc')
				->where('products.is_admin_approved',1)
				->groupBy('products.id')->distinct('products.id');

			if($type != 'new'){
				$query->where($type,1);
			}
			if($brandId)
			{
				$query= $query->where("products.brand_id",$brandId);
			}
			//join with product item.....
			$query= $query->join('product_items', function ($q) {
				$q->on('product_items.product_id', '=', 'products.id');
			});
			if($min_price!=""){

				$query=$query->where('product_items.sprice','>=',$min_price)->where('product_items.sprice','<=', $max_price);
			}

				if($max_offer==0 && $max_offer !='')
				{
					$query=$query->where('product_items.offer','<=',$min_offer);
				}
				elseif($min_offer || $max_offer)
				{
					$query=$query->where('product_items.offer','>=',$min_offer)->where('product_items.offer','<=', $max_offer);
				}

			
			$allCatProduct = $query->get();
		}else{
			$allCatProduct = \DB::table("products")
				->select('products.*')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->where('products.status',1)
				->select('products.*','product_images.image')
				->where($type,1)
				->where('products.is_admin_approved',1)
				->groupBy('products.id')
				->get();
			$allCatProduct = [];
		}

		return $allCatProduct;
	}
	public static function getApiProductListingByType($type,$sessionPincode){
		if($sessionPincode){

			$query = \DB::table("user_kyc")
				->join('products', 'products.user_id', '=', 'user_kyc.user_id')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
				//->leftJoin('scheme_products', 'scheme_products.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
				->where('products.is_admin_approved',1)

				->groupBy('products.id')->distinct('products.id');
			if($type != 'new'){
				$query->where($type,1);
			}
			$allCatProduct = $query->get();
		}else{
			$allCatProduct = \DB::table("products")
				->select('products.*')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->where('products.status',1)
				->select('products.*','product_images.image')
				->where($type,1)
				->where('products.is_admin_approved',1)
				->groupBy('products.id')
				->get();
			$allCatProduct = [];
		}

		return $allCatProduct;
	}
	public static function getCartCount(){
		$system_address= md5($_SERVER['REMOTE_ADDR'].$_SERVER['SERVER_ADDR'].$_SERVER['SERVER_PORT'].$_SERVER['HTTP_USER_AGENT']);
		$count = Cart::where('system_address',$system_address)->get()->count();
		if($count){
			echo $count;
		}else{
			echo 0;
		}
	}
	public static function getCityStateByPincode(){
		$pincode = session('pincode');
		$data = [];
		if($pincode){
			$data = \DB::table("pincodes")
				->join('cities', 'cities.id', '=', 'pincodes.city_id')
				->join('states', 'states.id', '=', 'cities.state_id')
				->select('cities.name as city_name','states.name as state_name')
				->where('pincodes.pincode',$pincode)
				->first();

		}
		return $data;
	}
	//To get scheme product data
	public static function getIfSchemeProduct($productId, $productItemId){
		$schemeData = [];
		$data = SchemeProduct::where('product_id',$productId)->where('product_item_id',$productItemId)->where('status',1)->first();
		if($data){
			$schemeData = $data;
		}
		return $schemeData;
	}
	public static function getProductPriceData($productId){
		$priceData = [];
		$data = ProductItem::where('product_id',$productId)->get();
		if($data){
			$priceData =  $data;
		}
		return $priceData;
	}
	public static function getRelatedProduct($pId){
		$sessionPincode = session('pincode');
		$pData =[];
		if($sessionPincode){
			$data = \DB::table("user_kyc")
				->join('products', 'products.user_id', '=', 'user_kyc.user_id')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
				->where('products.id',$pId)
				->where('products.is_admin_approved',1)
				->first();
		}
		if($data){
			$pData =$data;
		}
		return $pData;
	}
	public static function checkSchemeOnProduct($productId){
		$data = false;
		$schemeData = SchemeProduct::where('product_id',$productId)->first();
		if($schemeData){
			$data = true;
		}
		return $data;
	}
	public static function getDuplicateSeller($productId){
		$list = SellerDuplicateProduct::with('get_seller')->where('product_id',$productId)->get();
		if(count($list)){
			return $list;
		}else{
			return [];
		}
	}
	public static function getProductSellerName($productId){
		$list = Product::with('user_name')->where('id',$productId)->first();
		if($list){
			return $list;
		}else{
			return [];
		}
	}

	public static function getServiceScheduleSlots($duration,$stTime,$enTime,$break=0)
	{
			$start = new DateTime($stTime);
			$end = new DateTime($enTime);
			$interval = new DateInterval("PT" . $duration. "M");
			$breakInterval = new DateInterval("PT" . $break. "M");
            $i=0;
			for ($intStart = $start; 
				 $intStart < $end; 
				 $intStart->add($interval)->add($breakInterval)) {

				   $endPeriod = clone $intStart;
				   $endPeriod->add($interval);
				   if ($endPeriod > $end) {
					 $endPeriod=$end;
				   }
				   $i++;
				   
				   $time[$i]['start'] = $intStart->format('h:iA');
				   $time[$i]['end'] = $endPeriod->format('h:iA');
				  
			}

			return $time;
	  }
  
	/*public static function getServiceScheduleSlots($duration, $start,$end)
	{

		$start = new DateTime($start);
		$end = new DateTime($end);
		$start_time = $start->format('h:i a');
		$end_time = $end->format('h:i a');
		$i=0;
		while(strtotime($start_time) <= strtotime($end_time)){
			$start = $start_time;
			$end = date('h:i a',strtotime('+'.$duration.' minutes',strtotime($start_time)));
			$start_time = date('h:i a',strtotime('+'.$duration.' minutes',strtotime($start_time)));
			$i++;
			if(strtotime($start_time) <= strtotime($end_time)){
				$time[$i]['start'] = $start;
				$time[$i]['end'] = $end;
			}
		}
		return $time;
	}*/
	
    public static function get_category_name($id)
    {
       $data=Category::select('name')->where('id',$id)->first();
	   echo $data['name'];
    }
	public static function get_catalog_images($product_id)
	{
		 
		  $list=array();
		  $data=ProductImage::with('product_rating_avg')->select('image','id','is_default')->where('product_id',$product_id)->get()->toArray();
          foreach($data as $vs)
		  {
			  $array=array();
			  $array['image']=$vs['image'];
			  $array['id']=$vs['id'];
			  $array['is_default']=$vs['is_default'];
			  $list[]=$array; 
		  }
		  return $list;
	}
	
	public static function get_catalog_images_list($product_id)
	{
		  $data=ProductImage::where('product_id',$product_id)->get()->toArray();
	      return $data;
	}
	
	public static function get_sub_category($category_id)
	{
		  $data=SubCategory::select('id','name','image','slug')->where('category_id',$category_id)->get()->toArray();
	      return $data;
	}
	public static function get_super_sub_category($category_id)
	{
		  $data=SuperSubCategory::select('id','name','image')->where('sub_category_id',$category_id)->get()->toArray();
	      return $data;
	}
	
	public static function get_state($country_id)
    {
        $state_list= State::where('country_id',$country_id)->get();
        return $state_list;
    }

    public static function get_city($state_id)
    {
        $city_list= City::where('state_id',$state_id)->where('status',1)->get();
        return $city_list;
    }
	public static function get_product_name($order_id)
	{
		 $order_list= OrderMeta::where('order_id',$order_id)->get(); 
         $data=array();
		 foreach($order_list as $vs)
		 {
		    $data[]= $vs->product_name."(".$vs->qty.")";
		 }
		 return implode($data,",");
	}
	
	
	
	public static function get_product_image($order_id)
	{
		
		 $order_list= OrderMeta::select('product_image')->where('order_id',$order_id)->get();
         $data=array();
		 foreach($order_list as $vs)
		 {
		    $data[]= $vs->product_image;
		 }
		 return $data;
	}
	public static function get_order_image($order_id,$user_id,$status)
	{
		//echo $order_id; die;
		 $order_list= OrderMeta::select('product_image')->where('order_id',$order_id)->where('status',$status)->where('seller_id',$user_id)->get(); 
         $data=array();
		 foreach($order_list as $vs)
		 {
		    $data[]= $vs->product_image;
		 }
		 //dd($order_list); die;
		 
		 return $data;
	}
	
	public static function get_shipped_order_image($order_id,$user_id,$status)
	{
		 $order_list= OrderMeta::select('product_image')->where('order_id',$order_id)->whereIn('status',$status)->where('seller_id',$user_id)->get(); 
         $data=array();
		 foreach($order_list as $vs)
		 {
		    $data[]= $vs->product_image;
		 }
		 return $data;
	}
	
	public static function get_number_of_product($order_id,$user_id)
	{
		 $order_list= OrderMeta::where('order_id',$order_id)->where('seller_id',$user_id)->get()->count(); 
		 return $order_list;
	}
	
	public static function get_order_meta($order_id,$user_id)
	{
		 $order= OrderMeta::select(DB::raw("SUM(price*qty) as total_sum"),DB::raw("count(id) as total_item"))->where('order_id',$order_id)->where('seller_id',$user_id)->groupBy('order_id')->first(); 
		
		if(count($order)>0)
		{
		return  $order;
		}
	    else
		{
			return  "No ".$status;
		}			
	}
	public static function get_order_meta_total($order_id)
	{
		 $order= OrderMeta::select(DB::raw("SUM(price*qty) as total_sum"))->where('return_status',0)->where('order_id',$order_id)->groupBy('order_id')->first(); 
		 if(count((array)$order)>0)
		 {
			 return  $order->total_sum;
		 }
		 else
		 {
		       return  0;
		 }	
	}
	
	public static function get_order_meta_rma($order_id,$user_id,$status)
	{
		 $order= OrderMeta::with('reseller_payment')->select('*',DB::raw("SUM(price*qty) as total_sum"),DB::raw("count(id) as total_item"))->where('order_id',$order_id)->where('seller_id',$user_id)->groupBy('order_id')->where('status',$status)->first();
		if(count($order)>0)
		{
			return $order; 
		}
	    else
		{
			return  false;
		}			
	}
	
	public static function get_seller_deduction($start_date,$end_date,$seller_id)
	{
		 $order= OrderRmaDetail::with('reseller_payment','order')->where('is_approved',1)->whereHas('reseller_payment', function ($query) use($seller_id)
            {
                $query->where('seller_id',$seller_id);
				
            })->groupBy('order_id')->get();
	    $sum=0;
		foreach($order as $vs)
		{
            //echo $vs->order_id."<br>";
			
			if(!is_null($vs->reseller_payment))
				{
					if($vs->reseller_payment->return_amount==$vs->order->payment_amount)
					{
				    $amount=$vs->reseller_payment->extra_amount+($vs->reseller_payment->shipping_charge*2)+$vs->reseller_payment->return_amount+$vs->reseller_payment->exchange_amount;	
				    }
					else
					{
					$amount=$vs->reseller_payment->shipping_charge+$vs->reseller_payment->extra_amount+$vs->reseller_payment->return_amount+$vs->reseller_payment->exchange_amount;	
					}
				    //echo $amount."<br>";
				   $sum= $sum+ $amount;
				}
		}
		
		 $sum1=0;
		//order Exchange...............
		  $order1= OrderExchange::with('reseller_payment','order')->where('status','completed')->where('approved_date', '>=', $start_date)->where('approved_date', '<=', $end_date)->whereHas('reseller_payment', function ($query) use($seller_id)
            {
                $query->where('seller_id',$seller_id);
				
            })->groupBy('order_id')->get();
	    
		foreach($order1 as $vs)
		{
            //echo $vs->order_id."<br>";
			
			if(!is_null($vs->reseller_payment))
			     {
					
					$amount=$vs->reseller_payment->exchange_amount;	
					}
				    //echo $amount."<br>";
				   $sum1= $sum1+ $amount;
				
		}
		
		$cancel_amount= Payment::where('user_id',$seller_id)->where('type',"withdraw")->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->sum('amount');
		return $sum+$sum1+$cancel_amount;
	}
	public static function get_total_amount($start_date,$end_date,$seller_id)
	{
		 $amount = Order::with('user','user_kyc','order_meta_data')->groupBy('shipped_date')->where('seller_id',$seller_id)->whereHas('order_meta_data', function ($query)
            {
                $query->whereIn('order_metas.status', array('delivered','return','exchange'))->groupBy('order_metas.order_id');
				
            })->sum('total_amount');
				
		 return $amount;	
	}
	
	public static function get_sponsor_deduction($start_date,$end_date,$seller_id)
	{
		 $order= ProductSponsor::with('product_data')->where('updated_at', '>=', $start_date)->where('admin_status',1)->where('updated_at', '<=', $end_date)->where('user_id',$seller_id)->get();
	    $sum=0;
		foreach($order as $vs)
		{
		  $sum= $sum+ $vs->price;
		}
		return $sum;
	}
	
	
	public static function get_number_of_product_by($order_id,$user_id,$status)
	{
		 $order_list= OrderMeta::where('order_id',$order_id)->where('seller_id',$user_id)->where('status',$status)->get()->count(); 
		 return $order_list;
	}
	public static function get_number_of_qty($order_id,$user_id,$status)
	{
		 $order_list= OrderMeta::where('order_id',$order_id)->where('seller_id',$user_id)->where('status',$status)->sum('qty');
		if($order_list){
			return $order_list;
		}else{
			return 0;
		}
	}
	
	public static function get_number_of_qty_incoice($order_id,$user_id)
	{
		 $order_list= OrderMeta::where('order_id',$order_id)->where('seller_id',$user_id)->sum('qty'); 
		 return $order_list;
	}
	
	public static function get_qty_sum($order_id)
	{
		 $sum= OrderMeta::where('order_id',$order_id)->sum('qty'); 
		 return $sum;
	}
	public static function get_seller_product_sum($order_id)
	{
		$sum= OrderMeta::select(DB::raw('sum(price * qty) as total'))->where('order_id',$order_id)->first();
		return $sum;
	}
	public static function get_product_for_manifest($order_id)
	{
		 $order_list= OrderMeta::where('order_id',$order_id)->get(); 
         $data=array();
		 foreach($order_list as $vs)
		 {
		    $data[]= $vs->product_id;
		 }
		 $order_data=Order::select('order_id','created_at','dock_no','shipped_by')->where('id',$order_id)->first();
		 $returnData['product_id']=implode($data,",");
		 $returnData['order_id']=$order_data['order_id'];
		 $returnData['awb']=$order_data['dock_no'];
		 $returnData['date']=date('d-m-Y h:i:s',strtotime($order_data['created_at']));
		 $returnData['shipped_by']=$order_data['shipped_by'];
		 $returnData['qty']= self::get_qty_sum($order_id);
		 return $returnData;
	}
	
	public static function get_item_sum($order_id)
	{
		 $count= OrderMeta::where('order_id',$order_id)->get()->count(); 
		 return $count;
	}
	
	public static function check_order_status($order_id)
	{
		 $data= OrderMeta::where('order_id',$order_id)->get();
		 $result=array();
		 $cancelled_product=0;
		 $shipped_product=0;
		 $assign_to_rider_to_deliverd=0;
		 $pending_product=0;
		 $assign_to_rider=0;
		 $incomplete=0;
		 $assign_to_warehouse=0;
		 $return=0;
		 $exchange=0;
		 
		 foreach($data as $vs)
		 {
			 if($vs->status=="cancelled")
			 {
				 $cancelled_product=$cancelled_product+1;
			 } 
			 if($vs->status=="assign_to_rider_to_deliverd")
			 {
				 $assign_to_rider_to_deliverd=$assign_to_rider_to_deliverd+1;
			 }
		    if($vs->status=="assign_to_rider")
			 {
				 $assign_to_rider=$assign_to_rider+1;
			 } 
			 if($vs->status=="pending")
			 {
				 $pending_product=$pending_product+1;
			 }
			 if($vs->status=="delivered")
			 {
				 $shipped_product=$shipped_product+1;
			 }
			 if($vs->status=="incomplete")
			 {
				 $incomplete=$incomplete+1;
			 }
			 if($vs->status=="assign_to_warehouse")
			 {
				 $assign_to_warehouse=$assign_to_warehouse+1;
			 } 
			 if($vs->status=="return")
			 {
				 $return=$return+1;
			 }
			 if($vs->status=="exchange")
			 {
				 $exchange=$exchange+1;
			 }
		 }
		 
		 $result=($cancelled_product>0)?"<b style='color:red'> Item(".$cancelled_product.") Cancelled,</b> ":"";
		 $result.=($assign_to_rider_to_deliverd>0)?"Item(".$assign_to_rider_to_deliverd.") Warehouse to customer, ":"";
		 $result.=($assign_to_rider>0)?"Item(".$assign_to_rider.") Assign to Rider, ":"";
		 $result.=($pending_product>0)?"Item(".$pending_product.") Pending, ":"";
		 $result.=($shipped_product>0)?"<b style='color:green'> Item(".$shipped_product.") Deliverd</b>, ":"";
		 $result.=($incomplete>0)?"<b style='color:coral'> Item(".$incomplete.") incomplete, </b>":"";
		 $result.=($assign_to_warehouse>0)?"Item(".$assign_to_warehouse.") Assign to Warehouse, ":"";
		 $result.=($return>0)?"Item(".$return.") Return, ":"";
		 $result.=($exchange>0)?"Item(".$exchange.") Exchange, ":"";
		 
		 return $result;
	}
	
	public static function get_order_item_status($order_id)
	{
		//echo $order_id;
		 $data= OrderMeta::where('order_id',$order_id)->get();
		 $result=array();
		 $cancelled_product=0;
		 $shipped_product=0;
		 $dispatch_product=0;
		 $pending_product=0;
		 $ready_to_ship=0;
		 $incomplete=0;
		 $exchange=0;
		 $dispatched=0;
		 $return=0;
		 
		 foreach($data as $vs)
		 {
			 if($vs->status=="cancelled")
			 {
				 $cancelled_product=$cancelled_product+1;
			 } 
			 if($vs->status=="to_be_dispatched")
			 {
				 $dispatch_product=$dispatch_product+1;
			 }
		    if($vs->status=="ready_to_ship")
			 {
				 $ready_to_ship=$ready_to_ship+1;
			 } 
			 if($vs->status=="pending")
			 {
				 $pending_product=$pending_product+1;
			 }
			 if($vs->status=="shipped")
			 {
				 $shipped_product=$shipped_product+1;
			 }
			 if($vs->status=="incomplete")
			 {
				 $incomplete=$incomplete+1;
			 }
			 if($vs->status=="dispatched")
			 {
				 $dispatched=$dispatched+1;
			 } 
			 if($vs->status=="return")
			 {
				 $return=$return+1;
			 }
			 if($vs->status=="exchange")
			 {
				 $exchange=$exchange+1;
			 }
		 }
		 
		  $result['cancelled_product']=(($cancelled_product>0)?$cancelled_product:0);
		  $result['to_be_dispatched']=(($dispatch_product>0)?$dispatch_product:0);
		  $result['ready_to_ship']=(($ready_to_ship>0)?$ready_to_ship:0);
		  $result['pending']=(($pending_product>0)?$pending_product:0);
		  $result['delivered']=(($shipped_product>0)?$shipped_product:0);
		  $result['incomplete']=(($incomplete>0)?$incomplete:0);
		  $result['dispatched']=(($dispatched>0)?$dispatched:0);
		  $result['return']=(($return>0)?$return:0);
		  $result['exchange']=(($exchange>0)?$exchange:0);
		 
		 return $result;
	}
	
	public static function check_category_status($category_id,$user_id)
	{
		 $count= CategoryStockStatus::where('category_id',$category_id)->where('user_id',$user_id)->get()->count(); 
		 return $count;
	}
	
	public static function get_new_product($id)
	{
		 $startDate= date('Y-m-d');
		 $beforeDate = strtotime(date("Y-m-d", strtotime("-7 day")));
	   	 $count= Product::where('is_collection', '=', 1)->where('category_id',$id)->whereBetween('created_at', [$beforeDate, $startDate])->get()->count(); 
		 return $count;
	}
	
	public static function get_product_count_by_category($column,$id)
	{
		 $count= Product::where($column,'=',$id)->where('is_admin_approved',1)->get()->count(); 
		 return $count;
	}
	public static function check_sponsor($id)
	{
			 $colname = date("Y-m-d");
			 $product_check = DB::table('product_sponsors')->where('product_id',$id)->orderBy('id', 'desc')->first();
		     if($product_check)
			 {
			 return $product_check;
			 }
			 else
			 {
				 return false;
			 }
	}
	public static function getProductAllRating($productId){
		return $rating= ProductRating::with('user')->where('verify_status',1)->where('product_id',$productId)->get();
	}
	public static function getProductRating($userId,$productId){
		return $rating= DB::table('product_ratings')->where('verify_status',1)->where('user_id',$userId)->where('product_id',$productId)->first();

	}
	public static function get_rating($id)
	{
		$sum= DB::table('product_ratings')->where('product_id',$id)->where('verify_status',1)->sum('rating');
		$count=ProductRating::where('product_id',$id)->where('verify_status',1)->get()->count();
		if($count>0 and $sum>0)
		{
		return array('average'=>$sum/$count,'total_users'=>$count);
		}
		else
		{
			return array('average'=>0,'total_users'=>0);
		}
	} 
	
	public static function get_wd_total_sum($id,$type)
	{
			 $sum = DB::table('seller_payments')->where('user_id',$id)->where('type',$type)->sum('amount');
		     if($sum)
			 {
			     return $sum;
			 }
			 else
			 {
				 return 0;
			 }
	}
	
	public static function check_payment($seller_id,$orderDate)
	{
			 $status= Payment::where('order_date',$orderDate)->where('user_id',$seller_id)->first();
		     if($status)
			 {
			     return $status;
			 }
			 else
			 {
				 return false;
			 }
	}
	public static function check_payment_settle($id,$seller_id)
	{
			 $status= Payment::where('week_number',$id)->where('user_id',$seller_id)->where('payment_type','settlement')->first();
		     if($status)
			 {
			     return $status;
			 }
			 else
			 {
				 return false;
			 }
	}
	public static function get_seller_total_amount($seller_id)
	{
		             $sum=DB::table('order_metas')->select(DB::raw('SUM(price*qty) AS grand_total'))->where('seller_id',$seller_id)->where('status','delivered')->first();	       
			 //$sum= OrderMeta::select(DB::raw('SUM(price*qty) AS amount'))->where('seller_id',$seller_id)->where('status','delivered')->get();
			//$sum= Order::where('seller_id',$seller_id)->where('status','delivered')->sum('total_amount');
		     if($sum->grand_total>0)
			 {
			     return $sum->grand_total;
			 }
			 else
			 {
				 return 0;
			 }
	}
	
	public static function check_permission($user_id,$menu)
	{
		if($user_id==1)
		{
			return true;
		}
		else
		{
		   $permission= SubadminAccess::where('user_id',$user_id)->where('access_permission',$menu)->get()->count();
		   if($permission>0)
		   {
			   return true;
		   }
		   else
			{
			  return false;	
			}
		}
	
	}
	
	public static function check_action($user_id,$menu,$action)
	{
		if($user_id==1)
		{
			return true;
		}
		else
		{
		   $permission= SubadminAccess::where('user_id',$user_id)->where('access_permission',$menu)->whereRaw("find_in_set($action,action)")->get()->count();
		   if($permission>0)
		   {
			   return true;
		   }
		   else
			{
			  return false;	
			}
		}
	
	}
	
	public static function get_warehouse($user_id)
	{
	   $data=Warehouse::select(DB::raw('group_concat(id) as id'))->whereRaw("find_in_set(".$user_id.",subadmin_id)")->first();
	   return $data->id;
	}
     
    public static function send_msg($mobile_number,$message)
		{

			$sender_id = "GRCITO";
			$password = "1000grocito";
			$sender = "grocit";
			//route=t for Transactional
			$apiKey = "thq5yVE9rnMIWAcTeL0djZK2ug64JBolp7wP8OxmsvSC3XRDHfiYnHCsMdrFIcuEb4W9vkZT78OQ0Vyf";
			$url = "https://www.fast2sms.com/dev/bulk?authorization=". urlencode($apiKey) . "&sender_id=". urlencode($sender_id) . "&message=". urlencode($message) . "&language=english&route=t&numbers=".urlencode($mobile_number);
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$curl_scraped_page = curl_exec($ch);
			curl_close($ch);

		}


       public static function check_pincode($pickup_postcode,$delivery_postcode,$weight,$cod=1)
	   {
		  
		            $curl = curl_init();
					curl_setopt_array($curl, array(
					  CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/courier/serviceability?pickup_postcode=$pickup_postcode&delivery_postcode=$delivery_postcode&cod=$cod&weight=$weight",
					  CURLOPT_RETURNTRANSFER => true,
					  CURLOPT_ENCODING => "",
					  CURLOPT_MAXREDIRS => 10,
					  CURLOPT_TIMEOUT => 30,
					  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					  CURLOPT_CUSTOMREQUEST => "GET",
					  CURLOPT_HTTPHEADER => array(
						"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjE5MjQ3OSwiaXNzIjoiaHR0cHM6Ly9hcGl2Mi5zaGlwcm9ja2V0LmluL3YxL2V4dGVybmFsL2F1dGgvbG9naW4iLCJpYXQiOjE1NjE1MzkzMDQsImV4cCI6MTU2MjQwMzMwNCwibmJmIjoxNTYxNTM5MzA0LCJqdGkiOiI0QjNTZ3p5UEk0aVZGOUZEIn0.T8B92xahd2sVOHnJ_TAMvbXag-fuevZJBBKBSBHIoXw",
						"Cache-Control: no-cache",
						"Content-Type: application/x-www-form-urlencoded",
						"Postman-Token: 7f9742c2-3e0d-476f-a721-e479571cf916",
						"content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
					  ),
					));

					$response = curl_exec($curl);
					$err = curl_error($curl);
					curl_close($curl);
					$data=json_decode($response);
					//print_r($data); die;
					
					if($data->status==200)
					{
						return true;
						///echo json_encode(array('status'=>true,'message'=>'Service is avialble at this location'));
					}
					else
					{
						return false;
						//echo json_encode(array('status'=>false,'message'=>'Service is not avialble at this location'));
					}
	   }		   
   
   public static function check_pincode_api($pincode)
	{
		$count=DeliveryPincode::where('pincode',$pincode)->get();
		if (!$count->count()) {
			return false;
		} else {
		   return $count;
		}

	}
	
	public static function check_pincode_api_with_payment($pincode,$type)
	{
		$count=DeliveryPincode::where('pincode',$pincode)->where($type,'Y')->first();
		if (count($count)>0) {
			return true;
		} else {
		   return false;
		}

	}
	
	public static function check_pincode_api_with_payment_delivery($pincode,$type)
	{
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://track.delhivery.com/c/api/pin-codes/json/?token=236d8546e58918e5f1c5d296357a79fca288af0d&filter_codes=$pincode&$type=Y",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_POSTFIELDS => "",
			  CURLOPT_HTTPHEADER => array(
				"Cache-Control: no-cache",
				"Content-Type: application/x-www-form-urlencoded",
				"Postman-Token: f1d54a29-6439-489d-b585-9bb70896ec9e",
				"content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				return 'No';
			  //echo "cURL Error #:" . $err;
			} else {
			  $data= json_decode($response);
			 // print_r($data);
			  $count=count($data->delivery_codes);
				  if($count>0)
				  {
					 return "Yes";
				  }
				  else
				  {
					  return 'No';
				  }			  
			}
		

	}

	public static function get_awb_no($status,$id)
	{
		if($status=="return")
			{
				$data=OrderRmaDetail::select('dock_no')->where('order_meta_id',$id)->first();
				return $data->dock_no;
			}
			elseif($status=="exchange")
			{
				$data=OrderExchange::select('dock_no')->where('order_meta_id',$id)->first();
				return $data->dock_no;
			}

	}
	
	public static function get_awb_no_order($status,$id)
	{
		if($status=="return")
			{
				$data=OrderRmaDetail::select('dock_no')->where('order_meta_id',$id)->first();
				return $data->dock_no;
			}
			elseif($status=="exchange")
			{
				$data=OrderExchange::select('dock_no')->where('order_meta_id',$id)->first();
				return $data->dock_no;
			}

	}
	
	
	public static function place_order($orderData)
	{
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://apiv2.shiprocket.in/v1/external/orders/create/adhoc",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS =>json_encode($orderData),
			  CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjE5MjQ3OSwiaXNzIjoiaHR0cHM6Ly9hcGl2Mi5zaGlwcm9ja2V0LmluL3YxL2V4dGVybmFsL2F1dGgvbG9naW4iLCJpYXQiOjE1NjE1MzkzMDQsImV4cCI6MTU2MjQwMzMwNCwibmJmIjoxNTYxNTM5MzA0LCJqdGkiOiI0QjNTZ3p5UEk0aVZGOUZEIn0.T8B92xahd2sVOHnJ_TAMvbXag-fuevZJBBKBSBHIoXw",
				"Cache-Control: no-cache",
				"Content-Type: application/json",
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);
			curl_close($curl);
			if ($err) {
			  return $err;
			} else {
			  return $response;
			}
	}
	 
	 public static function get_order_track_status($id,$status)
	 {
	     $data= OrderTracking::where('type',$status)->where('order_id',$id)->first();
		 return $data;
	 }
	 
	 public static function get_deduction_on_order($id)
	 {
		 $data= ResellerPayment::where('order_id',$id)->first();
		 if(count($data)>0)
		 {
		 $total1= $data->return_amount+$data->shipping_charge+$data->extra_amount+$data->exchange_amount;
		 }
		 else
		 {
		     $total1=0;
		 }
		 
		 return $total1;
	 }

	 public static function get_related_product($id)
	 {

	        $data= Product::with('product_image')->where('id',$id)->first();
	        return $data;
	 }
	 
	  public static function check_delivery_boy_payment($id,$delivery_boy_id)
	 {
	        return DeliveryBoyPayment::where('payment_slot_id',$id)->where('delivery_boy_id',$delivery_boy_id)->get()->count();  
	 }

	 public static function get_setting()
	 {
	      $setting= GeneralSetting::select('wallet_deduction','saleplus_commission')->first();
	      return $setting;
	 }

      public static function get_merchant_count($value)
	 {
	      $currentMonth = date('m');
             $count = DB::table("users")
            ->whereRaw('MONTH(created_at) = ?',[$currentMonth])
            ->where('agent_id',$value)
            ->get();
		  return count($count);	
	 }
	 
	 public static function get_agent_value($id)
	 {
		 $value= self::get_merchant_count($id);
	      if($value>=0 and $value<=29)
		  {
			 return "NILL";
		  }
		  elseif($value>=30 and $value<=59)
		  {
			return "15000/-";  
		  }
		  elseif($value==60)
		  {
			return "20,000/-";  
		  }
		  elseif($value>=60)
		  {
			 $extra= $value-60;
             $extra= $extra*100;			 
			return 20000+$extra;  
		  }
		  
		  
	 }
	 
	 public static function send_push_notification(array $device_tokens,array $msg)
	{
		// $notify['user_id']= $id;
		// $notify['title']= $msg['title'];
		// $notify['message']= $msg['description'];
		// $obj= new PushNotification($notify);
		// $obj->save();
		
	   $FIREBASE_API_KEY="AAAA7ydfHqk:APA91bGCw1Y93UFQLS3Y3Q24V9E3DXmwYd2V0YCIalzo6qUEd_uA4hnpuK4kbmqZO06A57PtIxf_s2HnN-4HsyfwoujdZr7Xu1fSj3fEU8YZznasEEpmhzJWrX5JZRm6J9g6GpjUKKQ4";
   
		    	// prep the bundle
				$fields = array
				(
					'registration_ids'  => $device_tokens,
					'data'   => $msg
				);

				$headers = array
				(
					'Authorization: key=' . $FIREBASE_API_KEY,
					'Content-Type: application/json'
				);

				$ch = curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers);
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true);
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode($fields));
				$result[] = curl_exec($ch );
				$info = curl_getinfo($ch);
				curl_close( $ch );
				return $result;
	}
	
	//Api helper...
	public static function getApiProductByCat($catId,$pincode,$subCatId=''){
		$sessionPincode = $pincode;
		if($sessionPincode){

			$query = \DB::table("user_kyc")
				->join('users', 'users.id', '=', 'user_kyc.user_id')
				->join('products', 'products.user_id', '=', 'user_kyc.user_id')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
				//->leftJoin('scheme_products', 'scheme_products.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
				->where('products.is_admin_approved',1)
				->orderBy('products.id','DESC')
				->where('users.verify_status','=','verified')
				->groupBy('products.id')->distinct('products.id');
			if ($subCatId){
				$query->where('products.sub_category_id', $subCatId);
			}else{
				$query->where('products.category_id', $catId);
			}
			$allCatProduct = $query->get();
		}else{
			$allCatProduct = \DB::table("products")
				->select('products.*')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->where('products.status',1)
				->select('products.*','product_images.image')
				->where('products.category_id',$catId)
				->where('products.is_admin_approved',1)
				->get();
		}

		return $allCatProduct;
	}
//Api helper for filter...
	public static function getApiFilterProductByCat($catId,$pincode,$subCatId='',$brandId='',$min_price='',$max_price='',$min_offer='',$max_offer=''){
		$sessionPincode = $pincode;
		if($sessionPincode){

			$query = \DB::table("user_kyc")
				->join('users', 'users.id', '=', 'user_kyc.user_id')
				->join('products', 'products.user_id', '=', 'user_kyc.user_id')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
				//->leftJoin('scheme_products', 'scheme_products.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
				->where('products.is_admin_approved',1)
				->where('products.category_id', $catId)
				->where('users.verify_status','=','verified')
				->orderBy('products.id','desc')
				->groupBy('products.id')->distinct('products.id');


			if ($subCatId){
				$query->where('products.sub_category_id', $subCatId);
			}
			if($brandId)
			{
				$query= $query->whereIn("products.brand_id",[$brandId]);
			}
			$query= $query->join('product_items', function ($q) {
				$q->on('product_items.product_id', '=', 'products.id');
			});
			if($min_price!=""){

				$query=$query->where('product_items.sprice','>=',$min_price)->where('product_items.sprice','<=', $max_price);
			}

				if($max_offer==0)
				{
					$query=$query->where('product_items.offer','>=',$min_offer);
				}
				else
				{
					$query=$query->where('product_items.offer','>=',$min_offer)->where('product_items.offer','<=', $max_offer);
				}


			$allCatProduct = $query->get();
		}else{
			$allCatProduct = \DB::table("products")
				->select('products.*')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->where('products.status',1)
				->select('products.*','product_images.image')
				->where('products.category_id',$catId)
				->where('products.is_admin_approved',1)
				->get();
		}

		return $allCatProduct;
	}
	public static function getApiRelatedProduct($pId,$pincode){


		if($pincode){
			$data = \DB::table("user_kyc")
				->join('products', 'products.user_id', '=', 'user_kyc.user_id')
				->join('product_images', 'product_images.product_id', '=', 'products.id')
				->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
				->select('products.*','product_images.image','brands.name as brand_name')
				->whereRaw("find_in_set($pincode,user_kyc.delivery_pincode)")
				->where('products.id',$pId)
				->where('products.is_admin_approved',1)
				->first();
				//echo "helo";
			if($data) {
				return $data;
			}
		}


	}
	public static function getApiSellerName($sellerId){
		$userData= User::where('id',$sellerId)->first();
		if($userData){
			return $userData;
		}else{
			return '';
		}

	}
	
	public static function get_rider_total($from_date,$to_date,$delivery_boy_id)
	{
		$data = DB::table('delivery_boy_rides')->whereBetween("date",[date('Y-m-d', strtotime($from_date)),date('Y-m-d', strtotime($to_date))])
		->where('delivery_boy_id', '=', $delivery_boy_id)->select('amount_per_km',DB::raw('SUM(amount_per_km*distance) AS grand_total'),DB::raw('SUM(distance) AS total_distance'),DB::raw('COUNT(*) AS total_count'),DB::raw('SUM(bonus) AS bonus'))->groupBy('delivery_boy_id')->first();
	    return $data;
		//where('datetime BETWEEN "'. date('Y-m-d', strtotime($vs->from_date)). '" and "'. date('Y-m-d', strtotime($vs->to_date)).'"');
	}
	
	public static function get_cod_total($from_date,$to_date,$delivery_boy_id)
	{
		$data = DB::table('delivery_boy_rides')->whereBetween("date",[date('Y-m-d', strtotime($from_date)),date('Y-m-d', strtotime($to_date))])
		->where('delivery_boy_id', '=', $delivery_boy_id)->where('payment_mode', '=','cod')->select(DB::raw('SUM(amount_per_km*distance) AS grand_total'))->groupBy('delivery_boy_id')->first();
	     if(count((array)$data)>0)
		{
		     return $data->grand_total;
		}
		else
		{
			return 0;
		}
		//where('datetime BETWEEN "'. date('Y-m-d', strtotime($vs->from_date)). '" and "'. date('Y-m-d', strtotime($vs->to_date)).'"');
	}
	
	public static function get_total_deduction($from_date,$to_date,$delivery_boy_id)
	{
		$data = DB::table('delivery_boy_withdraw_payments')->where("date",[date('Y-m-d', strtotime($from_date)),date('Y-m-d', strtotime($to_date))])
		->where('user_id', '=', $delivery_boy_id)->select(DB::raw('SUM(amount) AS amount'))->groupBy('user_id')->first();
	     if(count((array)$data)>0)
		{
		     return $data->amount;
		}
		else
		{
			return 0;
		}
		//where('datetime BETWEEN "'. date('Y-m-d', strtotime($vs->from_date)). '" and "'. date('Y-m-d', strtotime($vs->to_date)).'"');
	}
	public static function get_total_days($from_date,$to_date,$delivery_boy_id)
	{
		$data = DB::table('delivery_boy_rides')->whereBetween("date",[date('Y-m-d', strtotime($from_date)),date('Y-m-d', strtotime($to_date))])
		->where('delivery_boy_id', '=', $delivery_boy_id)->select(DB::raw('COUNT(*) AS total_days'))->groupBy('date')->first();
	    if(count((array)$data)>0)
		{
		return $data->total_days;
		}
		else
		{
			return 0;
		}
		//where('datetime BETWEEN "'. date('Y-m-d', strtotime($vs->from_date)). '" and "'. date('Y-m-d', strtotime($vs->to_date)).'"');
	}
	
		public static function check_subadmin_permission($user_id,$access)
		{
			return SubadminAccess::where('user_id',$user_id)->where('access_permission',$access)->get()->count();
		}
	public static function addUserFirstCashbackAmount($userID,$orderSum){
		try{
			$userOrderCount = Order::where('user_id',$userID)->count();
			 if($userOrderCount < 2){
				 $cashbackSetting = Cashback::first();
				 if($orderSum >= $cashbackSetting->welcome_min_order_value){
			 		if($cashbackSetting->welcome_cashback_per > 0){
			 			$cashbackAmount = ($orderSum * $cashbackSetting->welcome_cashback_per) / 100;
			 				if($cashbackAmount > $cashbackSetting->upto_cashback){
								$cashbackAmount = $cashbackSetting->upto_cashback;
							 }
						$walletDatap['amount'] = $cashbackAmount;
			 			$walletDatap['type'] = 'deposit';
						$walletDatap['payment_type'] = 'first_order_cashback';
			 			$walletDatap['reason'] = 'Its First Order Cashback';
						$walletDatap['user_id'] = $userID;
			 			$chk=Wallet::where('payment_type','first_order_cashback')->where('type','deposit')->where('user_id',$userID)->get()->count();
						if($chk==0)
						{
							DB::table('wallets')->insert($walletDatap);
						}
			 	 }
			 	}
			 }
		}catch (\Exception $e){
			return $e->getMessage().' Line No:-'.$e->getLine();
		}
	}
	public static function addUserOrderCashbackAmount($userID,$orderSum){
		try{
			$userOrderCount = Order::where('user_id',$userID)->count();
				$cashbackSetting = Cashback::first();
				if($orderSum >= $cashbackSetting->min_order_value){
					if($cashbackSetting->cashback_per > 0){
						$cashbackAmount = ($orderSum * $cashbackSetting->cashback_per) / 100;
						if($cashbackAmount > $cashbackSetting->upto_cashback){
							$cashbackAmount = $cashbackSetting->upto_cashback;
						}
						$walletDatap['amount'] = $cashbackAmount;
						$walletDatap['type'] = 'deposit';
						$walletDatap['payment_type'] = 'cashback';
						$walletDatap['reason'] = 'Order Cashback';
						$walletDatap['user_id'] = $userID;
						DB::table('wallets')->insert($walletDatap);
					}
				}

		}catch (\Exception $e){
			return $e->getMessage().' Line No:-'.$e->getLine();
		}
	}
	public static function addReferMoneyToWallet($user_id){
		$userData = User::where('id',$user_id)->where('refer_and_earn',0)->first();
		if($userData){
			if($userData->ref_by){
				$refByUser=User::where('reff_code',$userData->ref_by)->first();
				if($refByUser){
					$referreWallet['user_id'] =$refByUser->id;
					$referreWallet['ref_id'] =$user_id;
					$referreWallet['amount'] = ReferralSetting::first()->referrer_amount;
					$referreWallet['type'] = 'deposit';
					$referreWallet['payment_type'] = 'refer_and_earn';
					$referreWallet['reason'] = 'Refer and earn money has been added successfully';
					DB::table('wallets')->insert($referreWallet);
					//update referral amount wallet balance.....
					$referralWallet['user_id'] = $user_id;
					$referralWallet['ref_id'] = $refByUser->id;
					$referralWallet['amount'] = ReferralSetting::first()->referral_amount;
					$referralWallet['type'] = 'deposit';
					$referralWallet['payment_type'] = 'refer_and_earn';
					$referreWallet['reason'] = 'Refer and earn money has been added successfully';
					DB::table('wallets')->insert($referralWallet);
					//update status....
					DB::table('users')->where('id', $user_id)->update(['refer_and_earn'=>1]);
				}

			}
		}

	}
	
	 public static function order_amount($seller_id,$order_id)
	 {
             $data=DB::table('order_metas')->select(DB::raw('SUM(price*qty) AS grand_total'))->where('seller_id',$seller_id)->where('order_id',$order_id)->groupBy('seller_id')->first();	       
		 if($data){
			 return $data->grand_total;
		 }else{
			 return 0;
		 }


			 
	 }
}