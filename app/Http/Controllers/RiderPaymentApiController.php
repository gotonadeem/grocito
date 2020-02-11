<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Slider;
use App\UserAddress;
use App\GeneralSetting;
use App\Enquiry;
use App\Category;
use App\UserKyc;
use App\UserProductShare;
use App\DeliveryBoyPayment;
use App\DeliveryBoyCommission;
use App\PaymentSlot;
use App\SubCategory;
use App\Cart;
use App\Order;
use App\OrderMeta;
use DB;
use URL;
use Helper;
use Excel;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class RiderPaymentApiController extends Controller
{
	public function __construct()
	{
	   parent::__construct();
	}
	
	function get_paid_payment(Request $request)
	{
		$input=json_decode($request->getContent(), true);
		$users = array(
            'user_id'    =>$input['user_id'],
        );
        $rules = array(
            'user_id' =>'required',
        );
        $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
			try {	
						$paidData=DeliveryBoyPayment::with('payment_slot')->where('delivery_boy_id',$users['user_id'])->get();
	                	// return Response::json(array(
							// 'status_code' => 1,
							// 'data' =>  $paid,
							// 'message' => 'Payment List',
						// ), 200);
						
				    $paid=array();
				   foreach($paidData as $vs)
				   {
					   
					   $array=array();
					   $array['from_date']=$vs->payment_slot->from_date;
					   $array['to_date']=$vs->payment_slot->to_date;
					   $array['payment_id']=$vs->payment_slot_id;
					   $array['total_count']=$vs->order_count;
					   $array['total_distance']=$vs->distance;
					   $array['total']=sprintf ("%.2f", $vs->payment_amount);
					   $array['amount_per_km']=$vs->distance_wise_amount;
					   $array['base_income']=$vs->base_income;
					   $array['cod_total']=sprintf ("%.2f", $vs->cod);
					   $array['bonus']=sprintf ("%.2f", $vs->bonus);
					   $array['payable']=sprintf ("%.2f", $vs->payment_amount);
					   $paid[]=$array;
				   }
				   
						return Response::json(array(
							'status_code' => 1,
							'data' =>   $paid,
							'message' => 'Payment List',
						), 200);
						
				}
				catch (\Exception $e) {
                   return Response::json(array(
						'status_code' => 0,
						'message' => $e->getMessage(),
					), 500);
				}
        }
	}
	
	function check_paid($id,$user_id)
	{
		return DeliveryBoyPayment::where('payment_slot_id',$id)->where('delivery_boy_id',$user_id)->get()->count();
	}
	
	function get_unpaid_payment(Request $request)
	{
		$input=json_decode($request->getContent(), true);
		$users = array(
            'user_id'    =>$input['user_id'],
        );
        $rules = array(
            'user_id' =>'required',
        );
        $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
			try {	
			       $unpaid=array();
				   $data=PaymentSlot::get();
				   foreach($data as $vs)
				   {
					   if(!$this->check_paid($vs->id,$users['user_id'])>0)
					   {
					   $rdata=Helper::get_rider_total($vs->from_date,$vs->to_date,$users['user_id']);
					   $commission= DeliveryBoyCommission::first();
				
					   $array=array();
					   $array['from_date']=$vs->from_date;
					   $array['to_date']=$vs->to_date;
					   $array['payment_id']=$vs->id;
					   $array['total_count']=((count((array)$rdata)>0)?$rdata->total_count:0);
					   $array['total_distance']=((count((array)$rdata)>0)?$rdata->total_distance:0);
					   $array['total']=(count((array)$rdata)>0)?$rdata->grand_total:0;
					   $array['amount_per_km']=$commission->per_km;
					   $array['base_income']=$commission->base_income;
					   $array['total_days']=Helper::get_total_days($vs->from_date,$vs->to_date,$users['user_id']);
					   $array['cod_total']=Helper::get_cod_total($vs->from_date,$vs->to_date,$users['user_id']);
					   //$array['deduction']=Helper::get_total_deduction($vs->from_date,$vs->to_date,$users['user_id']);
					   $array['bonus']=(count((array)$rdata)>0)?$rdata->bonus:0;
					   $array['payable']=$array['total']+$array['bonus'];
					   $unpaid[]=$array;
					   }
				   }
				   
						return Response::json(array(
							'status_code' => 1,
							'data' =>   $unpaid,
							'message' => 'Payment List',
						), 200);
						
				}
				catch (\Exception $e) {
                   return Response::json(array(
						'status_code' => 0,
						'message' => $e->getMessage(),
					), 500);
				}
        }
	}
	
	function order_list(Request $request)
	{
		$input=json_decode($request->getContent(), true);
		$users = array(
            'user_id'    =>$input['user_id'],
            'from_date'  =>$input['from_date'],
            'to_date'    =>$input['to_date'],
        );
        $rules = array(
            'user_id'    =>'required',
			'from_date'  =>'required',
            'to_date'    =>'required',
        );
        $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
		   $response=array();
		   $data = DB::table('delivery_boy_rides')
		          ->whereBetween("delivery_boy_rides.date",[date('Y-m-d', strtotime($users['from_date'])),date('Y-m-d', strtotime($users['to_date']))])
		          ->join('orders', 'orders.id', '=', 'delivery_boy_rides.order_id')			
				  ->where('delivery_boy_rides.delivery_boy_id', '=', $users['user_id'])
				  ->select('orders.created_at','delivery_boy_rides.job_id',
				  'orders.order_id','orders.id','delivery_boy_rides.seller_id','delivery_boy_rides.distance',
				  'delivery_boy_rides.bonus','delivery_boy_rides.payment_mode','delivery_boy_rides.type','delivery_boy_rides.amount_per_km')
				  ->get();
	        $orderList=array();
			$sum=0;
			$bonus=0;
			foreach($data as $vs)
			{
				$dta=DB::table('order_metas')->select(DB::raw('SUM(price*qty) AS grand_total'))->where('seller_id',$vs->seller_id)->where('order_id',$vs->id)->groupBy('seller_id')->first();
					
				$array=array();
				$array['date']= date("d-m-Y",strtotime($vs->created_at));
				$array['job_id']= $vs->job_id;
				$array['order_id']= $vs->order_id;
				$array['id']= $vs->id;
				$array['seller_id']=$vs->seller_id;
				$array['distance']=$vs->distance;
				$array['order_amount']=sprintf("%.2f",$dta->grand_total);
				$array['my_payment']=sprintf("%.2f",$vs->distance*$vs->amount_per_km);
				$array['bonus']=$vs->bonus;
				$array['payment_mode']=$vs->payment_mode;
				$array['type']=$vs->type;
				$orderList[]=$array;
				$sum= $sum+sprintf("%.2f",$dta->grand_total);
				$bonus= $bonus+$vs->bonus;
			}
			
			if(count((array)$orderList)>0)
			{
				$rdata=Helper::get_rider_total($users['from_date'],$users['to_date'],$users['user_id']);
                //print_r($rdata); die;				
				$commission= DeliveryBoyCommission::first();
				$total_days=Helper::get_total_days($users['from_date'],$users['to_date'],$users['user_id']);
				$response['status']=1;
				$response['data']=$orderList;
				$response['order_amount']=$sum;
                $response['bonus_amount']=$bonus;
                $response['base_income']=$commission->base_income*$total_days;
                $response['img_path']="";
                $response['message']="List of records";
			}
			else
			{
				$response['status']=0;
                $response['message']="Record not Found";
			}
			echo json_encode($response);
		}
	}
	
	public function paid_order_list(Request $request)
	{
		$input=json_decode($request->getContent(), true);
		$users = array(
            'user_id'    =>$input['user_id'],
            'payment_id' =>$input['payment_id'],
            'from_date'  =>$input['from_date'],
            'to_date'    =>$input['to_date'],
        );
        $rules = array(
            'user_id'    =>'required',
			'payment_id' =>'required',
			'from_date'  =>'required',
            'to_date'    =>'required',
        );
        $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
		   $response=array();
		   $data = DB::table('delivery_boy_rides')
		          ->whereBetween("delivery_boy_rides.date",[date('Y-m-d', strtotime($users['from_date'])),date('Y-m-d', strtotime($users['to_date']))])
		          ->join('orders', 'orders.id', '=', 'delivery_boy_rides.order_id')			
				  ->where('delivery_boy_rides.delivery_boy_id', '=', $users['user_id'])
				  ->select('orders.created_at','delivery_boy_rides.job_id',
				  'orders.order_id','orders.id','delivery_boy_rides.seller_id','delivery_boy_rides.distance',
				  'delivery_boy_rides.bonus','orders.shipping_charge','delivery_boy_rides.payment_mode','delivery_boy_rides.type','delivery_boy_rides.amount_per_km')
				  ->get();
			 $orderList=array();
			$sum=0;
			$bonus=0;
			foreach($data as $vs)
			{
			
				$dta=DB::table('order_metas')->select(DB::raw('SUM(price*qty) AS grand_total'))->where('seller_id',$vs->seller_id)->where('order_id',$vs->id)->groupBy('seller_id')->first();
				
				$array=array();
				$array['date']= date("d-m-Y",strtotime($vs->created_at));
				$array['job_id']= $vs->job_id;
				$array['order_id']= $vs->order_id;
				$array['id']= $vs->id;
				$array['seller_id']=$vs->seller_id;
				$array['distance']=$vs->distance;
				$array['order_amount']=sprintf("%.2f",$dta->grand_total+$vs->shipping_charge);
				$array['my_payment']=sprintf("%.2f",$vs->distance*$vs->amount_per_km);
				$array['bonus']=$vs->bonus;
				$array['payment_mode']=$vs->payment_mode;
				$array['type']=$vs->type;
				$orderList[]=$array;
				$sum= $sum+($vs->distance*$vs->amount_per_km);
			}
			
			
			if(count((array)$data)>0)
			{
				$response['status']=1;
				$paidData=DeliveryBoyPayment::with('payment_slot')->where('delivery_boy_id',$users['user_id'])->where('payment_slot_id',$users['payment_id'])->first();
                $response['data']=$orderList;
				$response['order_amount']=sprintf("%.2f",$sum);
                $response['bonus_amount']=$paidData['bonus'];
                $response['base_income']=sprintf("%.2f",$paidData['base_income']);
                $response['transaction_id']=$paidData['transaction_id'];
                $response['slip']=$paidData['slip'];
                $response['payment_amount']=sprintf("%.2f",$paidData['payment_amount']);
                $response['img_path']="";
                $response['message']="List of records";
			}
			else
			{
				$response['status']=0;
                $response['message']="Record not Found";
			}
			echo json_encode($response);
		}
		
	}
}