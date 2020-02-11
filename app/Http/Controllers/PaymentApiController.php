<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Slider;
use App\UserProfile;
use App\UserSender;
use App\ProductRating;
use App\UserAddress;
use App\GeneralSetting;
use App\OrderReturnVideos;
use App\Enquiry;
use App\OrderCancel;
use App\Category;
use App\ResellerPayment;
use App\BankDetail;
use App\UserKyc;
use App\UserWallet;
use App\UserProductShare;
use App\ProductCategory;
use App\Product;
use App\ProductImage;
use App\SubCategory;
use App\Cart;
use App\Order;
use App\OrderMeta;
use App\DeliveryPincode;
use DB;
use URL;
use Helper;
use Excel;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class PaymentApiController extends Controller
{
	public function __construct()
	{
	   parent::__construct();
	}
	
	/*..................My Payment request...................................................................*/
	public function my_payment_request(Request $request)
     {
        $users = array(
            'user_id'    =>$request->input('user_id'),
            'amount'    =>$request->input('amount'),
        );
        $rules = array(
            'user_id' =>'required',
            'amount' =>'required',
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
				$requested=UserWallet :: where('type','withdraw')->where('user_id',$users['user_id'])->where('status',0)->get()->count();
				if($requested>0)
				{
					return Response::json(array(
								'status_code' => 0,
								'message' => 'You have already requested',
							), 200); 
				}
				else
				{
					
					// $payment_total = DB::table('orders')
					// ->join('order_metas', 'orders.id', '=', 'order_metas.order_id')
					// ->where('orders.user_id', '=',$users['user_id'])
					// ->where('order_metas.status', '=','shipped')
					// ->groupBy('orders.user_id')
					// ->select(DB::raw('sum(margin_amount) AS sum'))
					// ->first();
					
				$payments = DB::table('reseller_payments')
				->join('orders', 'orders.id', '=', 'reseller_payments.order_id')
				->where('orders.user_id', '=',$users['user_id'])
				->where('orders.payment_mode', '=','cod')
				->where('orders.shipped_date', '!=','0000-00-00')
				->select(DB::raw('sum(reseller_payments.amount+reseller_payments.extra_amount+reseller_payments.shipping_charge+reseller_payments.return_amount) AS sum'),'orders.created_at','orders.shipped_date','orders.order_id','orders.id')
				->get();
			   
				  $total_sum=0;
				   foreach($payments as $vs)
				   { 
					   if((round((strtotime(date('Y-m-d h:i')) - strtotime($vs->shipped_date))/3600, 1)>=48))
					   {
						 $total_sum=$total_sum+ $vs->sum; 
					   }  
				   }
					
					 $wallet= UserWallet :: where('type','withdraw')->where('user_id',$users['user_id'])->where('status',1)->sum('amount');
					 $rest_amount= $total_sum - $wallet;
						 if($users['amount']<=$rest_amount)
						 {
							$amount['user_id']= $users['user_id'];
							$amount['amount']= $users['amount'];
							$amount['type']= 'withdraw';
							$obj = new UserWallet($amount);
							$obj->save();
							return Response::json(array(
								'status_code' => 1,
								'message' => 'Your request has been submitted successfully. Please wait for max 10 days.',
							), 200); 
						 }
						 else
						 {
							return Response::json(array(
								'status_code' => 0,
								'message' => 'Your requested amount is greater than wallet amount',
							), 200);
						 }
						 
				}
				
				}
				catch (\Exception $e) {
                   return Response::json(array(
						'status_code' => 0,
						'message' => $e->getMessage(),
					), 500);
				}
        }
    } 
	
	/*..................My Payment request........................................................*/
	public function get_withdraw_list(Request $request)
     {
        $users = array(
            'user_id'    =>$request->input('user_id'),
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
					 $wallet= UserWallet ::where('type','withdraw')->where('user_id',$users['user_id'])->orderBy('created_at','desc')->get();
						return Response::json(array(
							'status_code' => 1,
							'data' =>  $wallet,
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
	
	/*..................My Payment request........................................................*/
	public function payment_details(Request $request)
     {
        $users = array(
            'order_id'    =>$request->input('order_id'),
        );
        $rules = array(
            'order_id' =>'required',
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
					 $wallet= ResellerPayment ::where('order_id',$users['order_id'])->first();
						return Response::json(array(
							'status_code' => 1,
							'data' =>  $wallet,
							'message' => 'Payment Details',
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
	
	/*..................Admin Bank Account details........................................................*/
	public function get_admin_bank_details()
     {
       
			 $details= BankDetail ::first();
				return Response::json(array(
					'status_code' => 1,
					'data' =>  $details,
					'message' => 'Bank Details',
				), 200);
						
			
    } 
	
	/*..................Admin Bank Account details........................................................*/
	public function get_customer_bank_details(Request $request)
     {
			$users = array(
            'user_id'    =>$request->input('user_id'),
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
					 $bankDetails= UserKyc ::select('account_number','ifsc_code','account_holder_name','alternate_mobile_no')->where('user_id',$users['user_id'])->first();
						return Response::json(array(
							'status_code' => 1,
							'data' =>  $bankDetails,
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
	
	/*..................App Video........................................................*/
	public function get_video_details()
     {
			 $video_details= GeneralSetting ::select('app_hindi_video','app_english_video','video_hindi_title','video_english_title')->first();
				if($video_details)
				{
				return Response::json(array(
					'status_code' => 1,
					'data' =>  $video_details,
					'message' => 'Video details',
				), 200);	
				
				}
				else
				{
					return Response::json(array(
					'status_code' => 0,
					'data' =>  $video_details,
					'message' => 'Video details',
				), 200);	
				
				
				}
    } 
	/*..................App Video........................................................*/
	public function upload_video(Request $request)
     {
		 	$users = array(
            'order_id'    =>$request->input('order_id'),
            'product_id'    =>$request->input('product_id'),
            'order_meta_id'    =>$request->input('id'),
        );
        $rules = array(
            'order_id' =>'required',
            'product_id' =>'required',
            'order_meta_id' =>'required',
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
			
				 if(!empty($_FILES['user_image']['name'])){
				$image = $request->file('user_image');
					$path_original = public_path() . '/admin/uploads/order';
					$file = $request->user_image;
					$photo_name = time(). '-' .$file->getClientOriginalName();
					if($file->move($path_original, $photo_name))
					{
						$data= OrderReturnVideos::where('id',$users['order_meta_id'])->where('product_id',$users['product_id'])->where('order_id',$users['order_id']);
						if($data->count()>0)
						{
						 $data1=$data->first();
					     $data=DB::table('order_return_videos')->where('product_id',$users['product_id'])->where('order_id',$users['order_id'])->update(['video_name'=>$photo_name]);
						 return Response::json(array(
							'status_code' => 1,
							'id' =>  $data1->id,
							'message' => 'Video has been uploaded successfully',
							), 200); 
						}
						else
						{
						$videoData['order_id']= $users['order_id'];
						$videoData['video_name']= $photo_name;
						$videoData['product_id']= $users['product_id'];
						$videoData['order_meta_id']= $users['order_meta_id'];
						$obj= new OrderReturnVideos($videoData);
						$obj->save();
						 return Response::json(array(
							'status_code' => 1,
							'id' =>  $obj->id,
							'message' => 'Video has been uploaded successfully',
							), 200);
						}
						  	
					}
					else
					{
						  return Response::json(array(
							'status_code' => 0,
							'data' =>  "",
							'message' => 'Please try again',
							), 200); 	
					}
				}
		 }
		 catch (\Exception $e) {
                   return Response::json(array(
						'status_code' => 0,
						'message' => $e->getMessage(),
					), 500);
				}
       }
	 }	
	
}