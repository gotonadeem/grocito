<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\OrderMeta;
use App\Order;
use App\User;
use App\UserKyc;
use DB;
use Helper;
use App\Payment;
use App\OrderTracking;
use App\OrderRmaDetail;
use App\OrderExchange;
class PaymentController extends Controller
{
    public function __construct()
    {      parent::__construct();
    }
	
	public function get_next_payment()
	{
		
	   $seller_order_date= Order::select(DB::raw("MIN(shipped_date) AS shipped_date"))->with('order_meta')->where('seller_id',Auth::user()->id)->where('shipped_date','!=','0000-00-00')->first();
	  
	   if(count((array)$seller_order_date)>0)
	   {
	    $date=date("Y-m-d",strtotime($seller_order_date->shipped_date));
	    
	   $id= Auth::user()->id;	   
       $response=DB::select("SELECT 
			1 + DATEDIFF(orders.shipped_date, '".$date."') DIV 7  AS weekNumber
		  ,'".$date."' + INTERVAL (DATEDIFF(orders.shipped_date,'".$date."') DIV 7) WEEK
			  AS week_start_date
		  , MIN(orders.shipped_date) AS actual_first_date
		  , MAX(orders.shipped_date) AS actual_last_date
		  , SUM(order_metas.price * order_metas.qty) AS total
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			orders.shipped_date >= '".$date."'  and orders.seller_id='$id' and (order_metas.status='shipped')
		GROUP BY
			DATEDIFF(orders.shipped_date, '".$date."') DIV 7");		
	    return $response;
	   }
	   else
	   {
		   return false;
	   }
	}



    public function index()
    {
		
		$sum= 0;
		$today_payment= 0;
		$rma_amount= 0;
		$pending_amount= 0;
		$paid_amount= 0;
		$seller_id = Auth::user()->id;
		$paid_amount = Payment::where('user_id',$seller_id)->where('type','deposit')->sum('amount');
		$todayDate = date('Y-m-d');
		$todayPaymentData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
              SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
		order_metas.delivery_date = '$todayDate' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ");
		/*$todayPaymentData = DB::select("SELECT orders.shipped_date as shippedDate,
              SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			orders.shipped_date!='0000-00-00' and orders.shipped_date = '$todayDate' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(orders.shipped_date) ");*/

		$seller_total_amount = DB::select("SELECT order_metas.delivery_date as shippedDate, 
             SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			 order_metas.seller_id='$seller_id'  and (order_metas.status='delivered' )
		 ");
//echo '<pre>';print_r($todayPaymentData);die();
        return view('seller.payment.index')
		           ->with('today_payment',$todayPaymentData)
		           ->with('seller_total_amount',$seller_total_amount)
		           ->with('rma_amount',$rma_amount)
		           ->with('pending_amount',$pending_amount)
		           ->with('paid_amount',$paid_amount);

		
    }
	public function next_payment()
	{
		$seller_id = Auth::user()->id;
		$todayDate = date('Y-m-d');
		$todayPaymentData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
              SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			order_metas.delivery_date = '$todayDate' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ");
		return view('seller.payment.next_payment_details',compact('todayPaymentData','total','order_list','payment_list','return_data','exchange_data','cancelled_data','next_payment1','gst','commission'));

	}
	public function today_payment_order_list(){
		$seller_id = Auth::user()->id;
		$todayDate = date('Y-m-d');
		$returnAmount = Helper::getSellerReturnPenaltyToday();
		$exchangePenalty = Helper::getSellerExchangePenaltyAmount($seller_id,$todayDate);
		$todayPaymentData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
              SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			 order_metas.delivery_date = '$todayDate' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ");

		$order_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('delivery_date',$todayDate)->where('status','delivered')->groupBy('order_id');

		$order_list= $order_data->get();

		$sum_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('delivery_date',$todayDate)->where('status','delivered')->groupBy('order_id');
		$sum=0;
		foreach($sum_data->get() as $vs)
		{
			$sum=$sum+$vs->order->total_amount;
		}

		$last_payment_amount1= $sum;

		/*..........Return Orders.......*/
		$return_data= OrderRmaDetail::with('order')->whereDate(DB::raw('DATE(order_rma_details.approved_date)'),'=', $todayDate)->WhereHas('order', function ($query)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);
		})->where('is_approved',1);
		$return_data= $return_data->get();


		/*........Exchange Orders.........*/
		$exchange_data= OrderExchange::with('order')->where('approved_date', '=', $todayDate)->WhereHas('order', function ($query)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);
		})->where('is_approved',1);
		$exchange_data= $exchange_data->get();

		/*........Cancelled Orders.........*/
		$cancelled_data= Order::with('order_meta_data')->where('status','cancelled')->where('seller_id',Auth::user()->id)->whereDate('updated_at', '=', $todayDate)->get();


		return view('seller.payment.today_payment_order_list',compact('returnAmount','exchangePenalty','todayPaymentData','return_data','exchange_data','order_list','cancelled_data'));

	}
	public function pending_payment_order_list($date){
		$seller_id = Auth::user()->id;
		$todayDate = $date;
		$returnAmount = Helper::getSellerReturnPenaltyAmount($seller_id,$todayDate);
		$exchangePenalty = Helper::getSellerExchangePenaltyAmount($seller_id,$todayDate);
		$todayPaymentData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
             SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			 order_metas.delivery_date = '$todayDate' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ");

		$order_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('delivery_date',$todayDate)->where('status','delivered')->groupBy('order_id');

		$order_list= $order_data->get();

		$sum_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('delivery_date',$todayDate)->where('status','delivered')->groupBy('order_id');
		$sum=0;
		foreach($sum_data->get() as $vs)
		{
			$sum=$sum+$vs->order->total_amount;
		}

		$last_payment_amount1= $sum;

		/*..........Return Orders.......*/
		$return_data= OrderRmaDetail::with('order')->whereDate(DB::raw('DATE(order_rma_details.approved_date)'),'=', $todayDate)->WhereHas('order', function ($query)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);
		})->where('is_approved',1);
		$return_data= $return_data->get();

		/*........Exchange Orders.........*/
		$exchange_data= OrderExchange::with('order')->where('approved_date', '=', $todayDate)->WhereHas('order', function ($query)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);
		})->where('is_approved',1);
		$exchange_data= $exchange_data->get();

		/*........Cancelled Orders.........*/
		$cancelled_data= Order::with('order_meta_data')->where('status','cancelled')->where('seller_id',Auth::user()->id)->whereDate('updated_at', '=', $todayDate)->get();

		return view('seller.payment.pending_payment_order_list',compact('exchangePenalty','returnAmount','todayDate','todayPaymentData','return_data','exchange_data','order_list','cancelled_data'));

	}

	public function pending_payments()
	{
		$seller_id = Auth::user()->id;
		$todayDate = date('Y-m-d');
		$pendindOrderDate = DB::select("SELECT order_metas.delivery_date as pending_order_date
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id 
			left join seller_payments on seller_payments.user_id= orders.seller_id
		WHERE 
			order_metas.seller_id='$seller_id' and order_metas.status='delivered' and  NOT EXISTS
    		(SELECT * FROM seller_payments D2  WHERE D2.order_date = order_metas.delivery_date and D2.user_id='$seller_id' )
		GROUP BY
			(order_metas.delivery_date) ");
		foreach ($pendindOrderDate as $p_date){
			//echo $p_date->pending_order_date;
		}
		//$pendigOrderData =

		//echo '<pre>';print_r($pendindOrderDate);die;

		$order_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('delivery_date',$todayDate)->where('status','delivered')->groupBy('order_id');

		$order_list= $order_data->get();

		$sum_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('delivery_date',$todayDate)->where('status','delivered')->groupBy('order_id');
		$sum=0;
		foreach($sum_data->get() as $vs)
		{
			$sum=$sum+$vs->order->total_amount;
		}

		$last_payment_amount1= $sum;

		/*..........Return Orders.......*/
		$return_data= OrderRmaDetail::with('order','reseller_payment')->whereDate(DB::raw('DATE(order_rma_details.approved_date)'),'=', $todayDate)->WhereHas('order', function ($query)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);
		})->where('is_approved',1);
		$return_data= $return_data->get();


		/*........Exchange Orders.........*/
		$exchange_data= OrderExchange::with('order','reseller_payment')->where('approved_date', '=', $todayDate)->WhereHas('order', function ($query)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);
		})->where('is_approved',1);
		$exchange_data= $exchange_data->get();

		/*........Cancelled Orders.........*/
		// $cancelled_data= Order::with('order_meta_data')->where('seller_id',Auth::user()->id)->where('updated_at', '>=', $start_date)->where('updated_at', '<=', $end_date)->WhereHas('order_meta_data', function ($query)
		// {
		// $query->where('status','cancelled');
		// });
		// $cancelled_data= $cancelled_data->get();
		$cancelled_data= Payment::with('order')->where('user_id',Auth::user()->id)->where('created_at', '=', $todayDate)->where('type','withdraw');
		$cancelled_data= $cancelled_data->get();

		return view('seller.payment.pending_payment_details',compact('pendindOrderDate'));

	}
	public function previous_payments()
	{
		if(Auth::user())
		{
			$data=Payment::select('*',DB::raw("SUM(amount) as total_amount,SUM(commission) as total_commission"))->where('user_id',Auth::user()->id)->where('type','deposit')->groupBy('order_date');
			$payment_list= $data->paginate(15);

			$total=Payment::where('user_id',Auth::user()->id)->where('type','deposit');
			$total= $total->sum('amount');


			return view('seller.payment.previous_payment_details',compact('payment_list','total'));
		}
		else
		{
			return redirect("/login");
		}

	}
    public function payment_details()
    {
        return view('seller.payment.payment_details');
    }
    


	public function payment_order_details($order_id)
	{
	
		 if(Auth::user())
		 {
			 
			$user_data= UserKyc::where('user_id',Auth::user()->id)->first();
  			
			$orders = OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('order_id',$order_id)->get();
			$order_info = Order::with('address')->where('id',$order_id)->first();
		    $track_data= OrderTracking::where('order_id',$order_id)->get();
		 }
		else
		 {
			return redirect("/login");
		 }
		    return view('seller.payment.order_details',compact('orders','order_info','user_data','track_data'));
	}
	
	public function payment_previous_order_details($order_id)
	{
		 if(Auth::user())
		 {
			 $start_date= $_GET['start_date'];

			$lastDate= Payment::where('order_date','=',$start_date)->where('user_id',Auth::user()->id)->where('type','deposit')->first();
  			
			$orders = OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('order_id',$order_id)->get();
			$order_info = Order::with('address')->where('id',$order_id)->first();
			$track_data= OrderTracking::where('order_id',$order_id)->get();
		 }
		else
		 {
			return redirect("/login");
		 }
		    return view('seller.payment.order_previous_details',compact('orders','order_info','lastDate','track_data'));
	}
	
	public function last_payment()
	{
		 if(Auth::user())
		 {
			 
			   
			     $lastDate= Payment::where('user_id',Auth::user()->id)->where('type','deposit')->orderBy('id','desc')->first();
				 $holdAmount= Payment::where('user_id',Auth::user()->id)->where('type','deposit')->where('payment_type','weekly')->orderBy('id','desc')->skip(1)->take(1)->get();
				
				 $settleAmount= Payment::where('from_date',$holdAmount[0]->from_date)->where('to_date',$holdAmount[0]->to_date)->where('payment_type','settlement')->sum('amount'); 
				  $hold_amount= $holdAmount[0]->amount - $settleAmount;
	              if(count($lastDate)==0)
					{
						 return view('seller.payment.next_payment_details_error');
					}
					else
					{
			    $start_date=  $lastDate->from_date;
				$last_payment_amount= $lastDate->amount;
				$last_payment_date=$lastDate->to_date;
			    // $order_data= OrderMeta::with('order')->whereHas('order', function ($query) use ($start_date,$last_payment_date)
            // {
                // $query->whereBetween('orders.shipped_date', [$start_date, $last_payment_date]);
				
            // })->where('status','shipped');
				// $order_list= $order_data->get();
				//echo $start_date;
				//echo $last_payment_date;
				
				$order_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->WhereHas('order', function ($query) use ($start_date,$last_payment_date)
               {
				$query->whereBetween('shipped_date', [$start_date, $last_payment_date])->where('shipped_date',"!=","0000-00-00");	
               })->where('status','shipped')->groupBy('order_id');

			  $order_list= $order_data->get();
			  
			  $sum_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->WhereHas('order', function ($query) use ($start_date,$last_payment_date)
               {
				$query->whereBetween('shipped_date', [$start_date, $last_payment_date])->where('shipped_date',"!=","0000-00-00");	
               })->where('status','shipped')->groupBy('order_id')->get();
              
			    $sumT=0;
			    foreach($order_list as $vs)
				{
					$sumT=$sumT+ $vs->order->payment_amount;
				}
			    $last_payment_amount1=$sumT;
			  $sponsor_amount=Helper::get_sponsor_deduction(date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($last_payment_date)),Auth::user()->id);
		      $commission=($last_payment_amount1 * $lastDate->seller_commission/100);
		      $gst= ($commission * 18/100);
			  $comm= $lastDate->seller_commission;
				
			 $penalty_amount=Helper::get_seller_deduction(date('Y-m-d',strtotime($lastDate->from_date)),$lastDate->to_date,Auth::user()->id);	
				
				/*..........Return Orders.......*/
			$return_data= OrderRmaDetail::with('order','reseller_payment')->whereBetween(DB::raw('DATE(order_rma_details.approved_date)'), [$start_date, $last_payment_date])->WhereHas('order', function ($query)
            {
				$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);	
            })->where('is_approved',1);
			$return_data= $return_data->get();
			
			
			/*........Exchange Orders.........*/
			//echo $start_date;
			//echo $last_payment_date;
			$exchange_data= OrderExchange::with('order','reseller_payment')->where('approved_date', '>=', $start_date)->where('approved_date', '<=', $last_payment_date)->WhereHas('order', function ($query)
            {
				$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);	
            })->where('status','completed');
			$exchange_data= $exchange_data->get();
			
			/*........Cancelled Orders.........*/
			// $cancelled_data= Order::with('order_meta_data')->where('seller_id',Auth::user()->id)->where('updated_at', '>=', $start_date)->where('updated_at', '<=', $last_payment_date)->WhereHas('order_meta_data', function ($query)
            // {
				// $query->where('status','cancelled');	
            // });
			// $cancelled_data= $cancelled_data->get();
			/*.......................*/
			/*........Cancelled Orders.........*/
			$cancelled_data= Payment::with('order')->where('user_id',Auth::user()->id)->where('created_at', '>=', $start_date)->where('created_at', '<=', $last_payment_date)->where('type','withdraw');
			$cancelled_data= $cancelled_data->get();
			/*.......................*/
				
				return view('seller.payment.last_payment_details',compact('last_payment_amount','last_payment_date','order_list','cancelled_data','exchange_data','return_data','penalty_amount','last_payment_amount1','sponsor_amount','commission','gst','hold_amount','comm'));
					}
		 }
		else
		 {
			return redirect("/login");
		 }
	}
	

	
	public function outstanding_payments()
	{
		$user_data= UserKyc::where('user_id',Auth::user()->id)->first();
	    $response=$this->get_next_payment();
		$next_payment=end($response);
		$start_date= $next_payment->week_start_date;
		$end_date= date('Y-m-d',strtotime("+6 days", strtotime($next_payment->week_start_date)));
		if($end_date<date('Y-m-d'))
		{
			$start_date= date('Y-m-d',strtotime("+1 days", strtotime($end_date)));
		    $end_date= date('Y-m-d',strtotime("+12 days", strtotime($end_date)));
		}
		
		//manage next payment.............
		$week_number=$next_payment->weekNumber;
		$next_hold_amount=Payment::where('payment_type','weekly')->where('week_number',$week_number)->where('user_id',Auth::user()->id)->first();
		$old_hold_amount=Payment::where('payment_type','weekly')->where('week_number',$week_number-1)->where('user_id',Auth::user()->id)->first();
		//echo Auth::user()->id; die;
		//echo $user_data->cartlay_commission; die;
		if($old_hold_amount)
		{
			$old_amount=$old_hold_amount->amount;
		}
		else
		{
			$old_amount=0;
		}
		
		if(count($next_hold_amount)>0)
		{
        //$next_payment1= (!is_null($next_payment)?$next_payment->total:'');
		//$commission=$next_payment1 * $next_hold_amount->seller_commission/100;
		//$gst= ($commission * 18/100);
		//$next_payment1= (($next_payment1-$commission)-$gst)+$old_hold_amount->amount;
		if($end_date<=date('Y-m-d'))
		     {
                 $next_payment1= (!is_null($next_payment)?$next_payment->total:'');
				 $outstanding=$next_payment1+$old_hold_amount->amount;
				 $commission=$next_payment1 * $next_hold_amount->seller_commission/100;
		         $gst= ($commission * 18/100);
		         $next_payment1= (($next_payment1-$commission)-$gst)+$old_hold_amount->amount;
				 $next_payment_amount= $next_payment->total;
			 }
			 else
			 {
				$next_payment1= 0;
				$commission= 0;
				$gst= 0;
				$next_payment_amount= 0;
				$outstanding=$old_hold_amount->amount;
			 }
		}
		else
		{
		  
		   $next_payment1= (!is_null($next_payment)?$next_payment->total:'');
		   $commission=($next_payment1 * $user_data->cartlay_commission/100);
		   $gst= ($commission * 18/100);
		   $next_payment1= (($next_payment1-$commission)-$gst)+$old_amount;	
		    $outstanding= (!is_null($next_payment)?$next_payment->total:0)+$old_amount;	
		}
		$next_payment_amount=$next_payment->total;
		
		
		
		$start_date= date('Y-m-d',strtotime($start_date));
		$next_payment_date=$end_date;
		
		$penalty_amount=Helper::get_seller_deduction(date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)),Auth::user()->id);
		
		$sponsor_amount=Helper::get_sponsor_deduction(date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)),Auth::user()->id);
		
		
		
		$order_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->WhereHas('order', function ($query) use ($start_date,$next_payment_date)
            {
				$query->whereBetween('shipped_date', [$start_date, $next_payment_date])->where('shipped_date',"!=","0000-00-00");	
            })->where('status','shipped')->groupBy('order_id');

			$order_list= $order_data->get();
			
			/*..........Return Orders.......*/
			$return_data= OrderRmaDetail::with('order','reseller_payment')->whereBetween('approved_date', [$start_date, $next_payment_date])->WhereHas('order', function ($query)
            {
				$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);	
            })->where('is_approved',1)->groupBy('order_id');
			$return_data= $return_data->get();
			//echo "<pre>";
			//dd($return_data); 
			
			
			
			/*........Exchange Orders.........*/
			$exchange_data= OrderExchange::with('order','reseller_payment')->where('approved_date', '>=', $start_date)->where('created_at', '<=', $next_payment_date)->WhereHas('order', function ($query)
            {
				$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);	
            })->where('status','completed');
			$exchange_data= $exchange_data->get();
			
			
			/*........Cancelled Orders.........*/
			$cancelled_data= Payment::with('order')->where('user_id',Auth::user()->id)->where('created_at', '>=', $start_date)->where('created_at', '<=', $next_payment_date)->where('type','withdraw');
			$cancelled_data= $cancelled_data->get();
			if($cancelled_data->count()>0)
			{
				$cancelled_data=$cancelled_data;
			}
			else
			{
				//echo $next_payment_date;
				//echo date('Y-m-d');
			  $cancelled_data= Payment::with('order')->where('user_id',Auth::user()->id)->where('created_at', '>=', $next_payment_date)->where('created_at', '<=', date('Y-m-d'))->where('type','withdraw');
			  $cancelled_data= $cancelled_data->get();		  
			}
			/*.......................*/
			
		    return view('seller.payment.outstanding_payment_details',compact('next_payment_date','order_list','penalty_amount','return_data','exchange_data','cancelled_data','sponsor_amount','next_payment1','gst','commission'))->with('old_amount',$old_amount)->with('user_data',$user_data)->with('next_payment_amount',$next_payment_amount)->with('total_outstanding',$outstanding);

	}
	
	public  function previous($id){
    // get previous  user
    return Payment::where('id', '<', $id)->where('user_id',Auth::user()->id)->orderBy('id','asc')->first();

   }
  
	public function previous_all_orders($date1)
	{
		
		$start_date=$date1;
		$commission =0;
		$gst =0;
		$penalty_amount =0;
		$sponsor_amount =0;
		$hold_amount =0;
		$comm =0;
		$returnAmount = Helper::getSellerReturnPenaltyAmount(Auth::user()->id,$start_date);
		$exchangePenalty = Helper::getSellerExchangePenaltyAmount(Auth::user()->id,$start_date);
		$paidAmount= Payment::whereDate('order_date','=',$start_date)->where('user_id',Auth::user()->id)->where('type','deposit')->first();
		$last_payment_amount=$paidAmount->amount;
		$adminCommission=$paidAmount->commission;
		$tcsCommission=$paidAmount->tcs_amount;
		$order_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('delivery_date',$start_date)->where('status','delivered')->groupBy('order_id');

			$order_list= $order_data->get();
			
                $sum_data= OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('delivery_date',$start_date)->where('status','delivered')->groupBy('order_id');
               $sum=0;
			   foreach($sum_data->get() as $vs)
			   {
				   $sum=$sum+$vs->order->total_amount;
			   }
		//updated by nadeem
		     // $orderSum = OrderMeta::with('order')->whereDate('order.shipped_date','=', $start_date)->where('status','delivered')->where('seller_id',Auth::user()->id)->sum('price');
		     // $orderSum = Order::whereDate('shipped_date','=', $start_date)->where('status','delivered')->where('seller_id',Auth::user()->id)->sum('total_amount');
			  //$last_payment_amount1= $orderSum;

		//to get net amount
		//$orderNetAmount = Order::whereDate('shipped_date','=', $start_date)->where('status','delivered')->where('seller_id',Auth::user()->id)->sum('net_amount');
		//$orderNetAmount = OrderMeta::with('order')->whereDate('order.shipped_date','=', $start_date)->where('status','delivered')->where('seller_id',Auth::user()->id)->sum('net_amount');
		$seller_id =Auth::user()->id;
		$amountData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
             SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			order_metas.delivery_date = '$start_date' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' )
		GROUP BY
			(order_metas.delivery_date) ");
			  //$last_payment_amount1= $sum;

			/*..........Return Orders.......*/
			$return_data= OrderRmaDetail::with('order','reseller_payment')->whereDate(DB::raw('DATE(order_rma_details.approved_date)'),'=', $start_date)->WhereHas('order', function ($query)
            {
				$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);	
            })->where('is_approved',1);
			$return_data= $return_data->get();
			
			
			/*........Exchange Orders.........*/
			$exchange_data= OrderExchange::with('order','reseller_payment')->where('approved_date', '=', $start_date)->WhereHas('order', function ($query)
            {
				$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',Auth::user()->id);	
            })->where('is_approved',1);
			$exchange_data= $exchange_data->get();
			
			/*........Cancelled Orders.........*/
		$cancelled_data= Order::with('order_meta_data')->where('status','cancelled')->where('seller_id',Auth::user()->id)->whereDate('updated_at', '=', $start_date)->get();


		return view('seller.payment.previous_payment_order_list',compact('amountData','orderNetAmount','exchangePenalty','returnAmount','end_date','tcsCommission','adminCommission','start_date','order_list','cancelled_data','exchange_data','return_data','commission','gst','last_payment_amount1','last_payment_amount','penalty_amount','sponsor_amount','hold_amount','comm'));

	}
	
	
}