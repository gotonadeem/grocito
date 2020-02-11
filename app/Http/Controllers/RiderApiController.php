<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\UserKyc;
use App\Order;
use App\Enquiry;
use App\Cms;
use App\UserAddress;
use App\UserLocation;
use App\OrderMeta;
use App\Warehouse;
use App\DeliveryBoySetting;
use App\RiderCommission;
use App\DeliveryBoyRide;
use App\Cashback;
use App\PushNotification;
use DB;
use URL;
use Excel;
use Helper;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class RiderApiController  extends Controller
{
     public function __construct()
      {
	   parent::__construct(); 
      }
      
	function get_order_amount($id,$seller_id)
			{
				$query = \DB::table("order_metas")->select('product_name','product_image','qty','price')
								->where('order_id',$id)
								->where('seller_id',$seller_id)->get();
								
				$query1 = \DB::table("orders")->select('shipping_charge')->where('id',$id)->first();
				$sum=0;
				foreach($query as $vs)
				{
					$sum= $sum+($vs->qty*$vs->price);
				}
                 return $sum+$query1->shipping_charge;  				
			}
	//forgot Password...........................................................................
     public function change_password(Request $request)
      {
		$user = array(
            'user_id'    => $request->input('user_id'),
            'old_password'    => $request->input('old_password'),
            'new_password'    => $request->input('new_password'),
        );
        $rules = array(
            'user_id'    =>   'required',
            'old_password'    =>   'required',
            'new_password'    =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
             if(User::where('id', $request->input('user_id'))->count()>0)
			 {   $usersData=array();
				 if(User::where('id', $request->input('user_id'))->where('simple_pass', $request->input('old_password'))->count()>0)
				 { 
     			 
					 $usersData['password'] =    Hash::make($user['new_password']);
					 $usersData['simple_pass'] =    $user['new_password'];
						$user = User::findOrFail($request->input('user_id'));
						$user->fill($usersData)->save();
						
						 return Response::json(array(
						'status_code' => 1,
						'message' => 'Password has been reset successfully',
						'error_message'=>"Password has been reset successfully",
					  ), 200);
                 }
                 else
				 {
						  return Response::json(array(
						'status_code' => 0,
						'message' => 'Old password does not matched',
						'error_message'=>"Old password does not matched",
					  ), 200);
				 }					 
			 }
             else
			 {
					 return Response::json(array(
					'status_code' => 0,
					'message' => 'User Does not exists',
					'error_message'=>"User Does not exists",
				    ), 200); 
			 }				 
			
        }
    }


   public function forgot_password_now(Request $request){
	    $input=json_decode($request->getContent(), true);
        $mobile = $input['mobile'];
                $checkMobileNumber = User::where('mobile', $mobile)->where('role_id',4)->first();
                if (count((array)$checkMobileNumber)>0) {
                    $mmsg=" Use ".$checkMobileNumber->simple_pass." as your password.  Do not share this OTP to anyone for security reasons.";
                    Helper::send_msg($mobile,$mmsg);
                    	return Response::json(array(
							'status_code' => 1,
							'message' => 'password send successfully!',
						), 200);
                   
                } else {
                   	return Response::json(array(
							'status_code' => 0,
							'message' => 'Invalid mobile number',
						), 200);
                }
    }
	
	
	//forgot Password...........................................................................
     public function forgot_password(Request $request)
      {
		$otp= rand(123,999).rand(12,99);
        $user = array(
            'otp'    => $otp,
            'mobile'    => $request->input('mobile'),
        );
        $rules = array(
            'mobile'    =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
			  $data=User::where('mobile', $request->input('mobile'))->pluck('id')->toArray();
			  if(count($data)>0)
			  {
					DB::table('users')->where('mobile', $request->input('mobile'))->update(['otp' =>  $otp]);			
					$mmsg=" Use $otp as one time password(OTP) forgot password.  Do not share this OTP to anyone for security reasons.";
					Helper::send_msg($user['mobile'],$mmsg);	
					return Response::json(array(
						'status_code' => 1,
						'message' => 'successfully resend',
						'otp_code' => $otp,
						'user_id' => $data[0],
						'error_message'=>"Resend successfully",
					), 200);
			  }
			  else
			  {
					return Response::json(array(
						'status_code' => 0,
						'message' => 'Your account does not exists',
						'error_message'=>"Your account does not exists",
					), 200);  
			  }
        }
    }
	
	//forgot Password...........................................................................
     public function reset_password(Request $request)
      {
		$user = array(
            'otp'    => $request->input('otp'),
            'user_id'    => $request->input('user_id'),
            'password'    => $request->input('password'),
        );
        $rules = array(
            'user_id'    =>   'required',
            'password'    =>   'required',
            'otp'    =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
            //DB::table('users')->where('mobile', $request->input('mobile'))->update(['otp' =>  $otp]);
             if(User::where('otp', $request->input('otp'))->where('id', $request->input('user_id'))->count()>0)
			 {   $usersData=array();
				 $usersData['password'] =    Hash::make($user['password']);
                 $usersData['simple_pass'] =    $user['password'];
			     //User::where('otp', $request->input('otp'))->where('id', $request->input('user_id'))->update($usersData);
				    $user = User::findOrFail($request->input('user_id'));
                    $user->fill($usersData)->save();
					
					 return Response::json(array(
					'status_code' => 1,
					'message' => 'Password has been reset successfully',
					'error_message'=>"Password has been reset successfully",
				  ), 200);				 
			 }
             else
			 {
					 return Response::json(array(
					'status_code' => 0,
					'message' => 'Either does not exists or otp unmatched',
					'error_message'=>"Either does not exists or otp unmatched",
				    ), 200); 
			 }				 
			
        }
    }
  

	//user login....................................................................................
	    public function api_login(Request $request){
			$input=json_decode($request->getContent(), true);
			     $email = $input['email'];
			     $password = $input['password'];
			     $device_token = $input['device_token'];
		         $data= User::where('email', $email)->where('simple_pass', $password)->where('role_id',4)->first();
				  if(count((array)$data)>0)
				  {
						  if($data['banned']==1)
							{
								 return Response::json(array(
									'status_code' => 0,
									'message' => 'Your account has been blocked or unverified',
									'error_message'=>'Your account has been blocked or unverified',
								), 200);
							}
							 // elseif($data['is_login']==1)
							// {
								 // return Response::json(array(
									// 'status_code' => 0,
									// 'message' => 'Your have already logged in',
									// 'message' => 'Your have already logged in',
								// ), 200);
							// }
							else
							{	
						       $query = \DB::table("users")
								->join('user_kyc', 'users.id', '=', 'user_kyc.user_id')
								->select('users.username','users.device_token','users.id','users.mobile','users.unique_code as employee_no','users.email','user_kyc.profile_image','user_kyc.city_id')
								->where('users.id',$data->id)->first();
								
						               DB::table('users')->where('id',$data->id)->update(['device_token'=>$device_token,'login_time'=>date('h:i A'),'is_login'=>1,'is_active'=>1]);
						               DB::table('rider_login_history')->insert(['rider_id'=>$data->id,'login_time'=>date('h:i A'),'is_login'=>1]);
										return Response::json(array(
										'status_code' => 1,
										'message' => 'Loggged in successfully',
										'error_message'=>"Loggged in successfully",
										'img_path' => url('/')."/public/admin/uploads/seller/",
										'data'=>$query,
									), 200);
							}
				  }
				  else
				  {
							  return Response::json(array(
								'status_code' => 0,
								'message' => 'Invalid email or password',
								'error_message'=>'Invalid email or password',
								'data'=>null
							), 200);
				  }
        
    }
	public function riderLogout(Request $request){
		try{
			$input=json_decode($request->getContent(), true);
			$id =$input['user_id'];
			DB::table('users')->where('id',$id)->update(['login_time'=>date('h:i A'),'is_login'=>0,'is_active'=>0]);
			DB::table('rider_login_history')->insert(['rider_id'=>$id,'login_time'=>date('h:i A'),'is_login'=>0]);
			return Response::json(array(
				'status' => 1,
				'message' => 'Logout successfully',
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			), 200);
		}
	}
	//not in use....
	public function updateProfileImage(Request $request)
	{
		try{
			$userId = $request->input('user_id');
			$checkUser =User::where('id',$userId)->first();
			if(!$checkUser){
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Invalid user id',
				), 200);
			}
			$image = $request->file('profile_image');
			$path_original=public_path() . '/admin/uploads/seller';//m\/public\/admin\/uploads\/seller\/"
			$file = $request->profile_image;
			$photo_name = time() . '-' . $file->getClientOriginalName();
			$file->move($path_original, $photo_name);
			$kycData['profile_image'] = $photo_name;
			$userKyc=UserKyc::where('user_id', '=',$userId)->first();
			$userKyc->update($kycData);

			return Response::json(array(
				'status_code' => 1,
				'image' => $photo_name,
				'url' => public_path() . '/front/user_profile/',
				'message' => 'Update successfully',
			), 200);
		}catch (\Exception $e) {
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			), 200);
		}
	}
	   //user details....................................................................................
	    public function get_user(Request $request){
			$input=json_decode($request->getContent(), true);
			     $user_id = $input['user_id'];
		         $data= User::with('user_kyc')->where('id', $user_id)->first();
                 if(count((array)$data)>0)
				 {					 
				return Response::json(array(
							'status_code' => 1,
							'message' => 'User details',
							'error_message'=>'User details',
							'data'=>$data
 						), 200);
                 }
                 else
                 {
					return Response::json(array(
							'status_code' => 0,
							'message' => 'User details',
							'error_message'=>'User details',
							'data'=>null
 						), 200); 
				 }					 
    }
	
	
	 //user details....................................................................................
	    public function get_logout(Request $request){
			$input=json_decode($request->getContent(), true);
			     $user_id = $input['user_id'];
				 DB::table('users')->where('id',$user_id)->update(['is_login'=>0]);
		        return Response::json(array(
							'status_code' => 1,
							'message' => 'Logout User',
							'error_message'=>'Logout User',
 						), 200);					 
    }
	
	//get commission....................................................................................
	    public function get_commission(Request $request){
			$input=json_decode($request->getContent(), true);
			     $user_id = $input['user_id'];
		         $deposit= RiderCommission::where('user_id', $user_id)->where('type', 'deposit')->sum('amount');
		         $withdraw= RiderCommission::where('user_id', $user_id)->where('type', 'withdraw')->sum('amount');
                				 
				 return Response::json(array(
							'status_code' => 1,
							'message' => 'User details',
							'error_message'=>'User details',
							'data'=>round($deposit-$withdraw,2),
 						), 200);
               		 
    }
	
	
	//get commission....................................................................................
	    public function get_commission_wallet(Request $request){
			$input=json_decode($request->getContent(), true);
			     $user_id = $input['user_id'];
		         $data= RiderCommission::with("order",'order.seller_kyc','order.address')->where('user_id', $user_id)->get();			 
				 $list=array();
				 $sum= 0;
				 foreach($data as $vs)
				 {
					$array= array();
					$array['user_id']=$vs->user_id;
					$array['amount']=$vs->amount;
					$array['order_id']=$vs->order_id;
					$array['payment_amount']=$vs->payment_amount;
					$array['delivery_time']=$vs->order->delivery_time;
					$array['delivery_date']=$vs->order->delivery_date;
					$array['from_address']=$vs->order->seller_kyc->address_1;
					$array['to_address']=$vs->order->address->house.",".$vs->order->address->landmark.",".$vs->order->address->street.",".$vs->order->address->city.",".$vs->order->address->state;
					$list[]= $array;
					$sum= $sum+$vs->amount;
				 }
				 
				 return Response::json(array(
							'status_code' => 1,
							'total' => $sum,
							'message' => 'User List',
							'error_message'=>'User List',
							'data'=>$list,
 						), 200);
               		 
    }
	//get commission....................................................................................
	public function get_order_history(Request $request){
		$input=json_decode($request->getContent(), true);
				$query = \DB::table("delivery_boy_notifications")
								->join('orders', 'orders.id', '=', 'delivery_boy_notifications.order_id')
								->join('user_addresses', 'orders.address_id', '=', 'user_addresses.id')
								->join('user_kyc', 'orders.seller_id', '=', 'user_kyc.user_id')
								->join('users', 'orders.user_id', '=', 'users.id')
								->select('delivery_boy_notifications.status','orders.shipping_charge','delivery_boy_notifications.warehouse_id','delivery_boy_notifications.type','orders.id','orders.order_id','orders.payment_mode','orders.delivery_date','orders.delivery_time','user_kyc.user_id as seller_id','user_kyc.f_name','user_kyc.l_name','user_kyc.address_2',
								'user_addresses.lattitude as user_lat','user_addresses.longitude as user_long','user_addresses.house','user_addresses.pincode','user_addresses.street','user_addresses.city','user_addresses.state','user_kyc.latitude as seller_lat','user_kyc.longitude as seller_long','users.mobile','users.username as name')
								->where('delivery_boy_notifications.status',$input['status'])
								->where('delivery_boy_notifications.delivery_boy_id',$input['user_id'])->orderBy('delivery_boy_notifications.id','desc')->get();
				$dataList=array();
				foreach($query as $vs)
				{
				
					$data=array();
					$data['id']=$vs->id;
					$data['order_id']=$vs->order_id;
					$data['payment_mode']=$vs->payment_mode;
					$data['delivery_date']=$vs->delivery_date;
					$data['delivery_time']=$vs->delivery_time;
					$data['status']=$vs->status;
					$data['type']=$vs->type;
					if($vs->type=="warehouse_to_customer")
					{
						//pickup location
						$wData= Warehouse::where('id',$vs->warehouse_id)->first();
						$data['pickup_name']=$wData->name;
						$data['pickup_lat']=$wData->lattitude;
						$data['pickup_address']=$wData->address;
						$data['pickup_long']=$wData->longitude;
						$data['pickup_mobile']="";
				        $amount=Helper::get_order_meta_total($vs->id);
						$ordAmount=($amount>0)?$amount+$vs->shipping_charge:$amount;
						$data['amount']=$ordAmount;
				        //drop location  
						$data['seller_name']=$vs->name;
						$data['seller_lat']=$vs->user_lat;
						//$data['seller_address']=$wData->address;
						$data['seller_long']=$vs->user_long;
						$data['user_mobile']=$vs->mobile;
						$data['seller_address']=$vs->house.",".$vs->street.",".$vs->pincode.",".$vs->city.",".$vs->state;
					   
					}
					else
					{
						//pickup location
						$dta=$this->get_seller_details($vs->seller_id);
						$data['pickup_name']=$dta->username;
						$data['pickup_lat']=$vs->seller_lat;
					    $data['pickup_address']=$vs->address_2;
					    $data['pickup_long']=$vs->seller_long;
						$data['pickup_mobile']=$dta->mobile;
						
						//drop location 
						$wData= Warehouse::where('id',$vs->warehouse_id)->first();
						$data['seller_name']=$wData->name;
						$data['seller_lat']=$wData->lattitude;
					    $data['seller_address']=$wData->address;
					    $data['seller_long']=$wData->longitude;
						$data['user_mobile']="";
						$data['amount']=$this->get_order_amount($vs->id,$vs->seller_id);
					}
					//$data['user_lat']=$vs->user_lat;
					$data['seller_id']=$vs->seller_id;
					//$data['user_long']=$vs->user_long;
					
					
					$data['item_details']=$this->get_item_details($vs->id,$vs->seller_id);
					
					//$data['user_name']=$vs->name;
					
					$dataList[]=$data;
				}
				return Response::json(array(
								'status_code' => 1,
								'data' =>$dataList,
								'message' => "List of order",
								'error_message'=>"List of order",
							), 200);

	}
	//get commission....................................................................................
	public function get_today_commission(Request $request){
		$input=json_decode($request->getContent(), true);
		$user_id = $input['user_id'];
		$todayDate = date('Y-m-d');
		$data= RiderCommission::with("order",'order.seller_kyc','order.address')->where('user_id', $user_id)->whereDate('created_at','=',$todayDate)->get();
		$list=array();
		$sum= 0;
		foreach($data as $vs)
		{
			$array= array();
			$array['user_id']=$vs->user_id;
			$array['amount']=$vs->amount;
			$array['order_id']=$vs->order_id;
			$array['payment_amount']=$vs->payment_amount;
			$array['delivery_time']=$vs->order->delivery_time;
			$array['delivery_date']=$vs->order->delivery_date;
			$array['from_address']=$vs->order->seller_kyc->address_1;
			$array['to_address']=$vs->order->address->house.",".$vs->order->address->landmark.",".$vs->order->address->street.",".$vs->order->address->city.",".$vs->order->address->state;
			$list[]= $array;
			$sum= $sum+$vs->amount;
		}

		return Response::json(array(
			'status_code' => 1,
			'total' => $sum,
			'message' => 'Payment List',
			'error_message'=>'Payment List',
			'data'=>$list,
		), 200);

	}
	  //get notifications....................................................................................
	    public function get_notifications(Request $request){
			$input=json_decode($request->getContent(), true);
			     $user_id = $input['user_id'];
		         $data= PushNotification::where('user_id', $user_id)->get();			 
				 return Response::json(array(
							'status_code' => 1,
							'message' => 'User details',
							'error_message'=>'User details',
							'data'=>$data,
 						), 200);
               		 
    }
	
	   //change Status...............................................................
	    public function change_status(Request $request){
			$input=json_decode($request->getContent(), true);
			$user_id = $input['user_id'];
		    $response=DB::statement("UPDATE users SET is_active =(CASE WHEN (is_active = 1) THEN '0' ELSE '1' END) where id = $user_id");
		    $user= User::select('is_active')->where('id',$user_id)->first();
				
			return Response::json(array(
							'status_code' => 1,
							'status' => $user->is_active,
							'message' => 'Status has been changed',
							'error_message'=>'Status has been changed',
 						), 200);
               		 
            }

			//change Status.......................................
	    public function dashboard(Request $request){
			$commission=DB::table('delivery_boy_commissions')->first();
			$input=json_decode($request->getContent(), true);
			$user_id = $input['user_id'];
			$cDate=date('Y-m-d');
			//$deliverBoyRides = \DB::table("delivery_boy_rides")->where('delivery_boy_id',$user_id)->whereIn('type',array('warehouse_to_customer','seller_to_warehouse'))->whereDate('created_at','=',$cDate)->get();
			$order = DeliveryBoyRide::with('order')->where('delivery_boy_id',$user_id)->whereIn('type',array('warehouse_to_customer','seller_to_warehouse'))->whereDate('created_at','=',$cDate)->orderBy('id','DESC')->first();
			$codOrder = DeliveryBoyRide::with('order')->where('delivery_boy_id',$user_id)->where('type','warehouse_to_customer')->where('is_cod_submitted',0)->get();
			$sum1 = 0;
			foreach ($codOrder as $rides){
				$totalAmount = OrderMeta::select(DB::raw('SUM(price*qty) AS grand_total'))->where('order_id',$rides->order_id)->where('seller_id',$rides->seller_id)->where('return_status',0)->first();

				$sum1=$sum1+($totalAmount->grand_total +$rides->order->shipping_charge);
			}
			$corder=0;
			if(count((array)$order)>0)
			{
			//$sum1= $sum1+$order->order->shipping_charge;
			   if($order->type=="warehouse_to_customer")
			   {
				   $corder=Helper::get_order_meta_total($order->order->id);
			   }
			   else
			   {
			       $corder=$order->order->total_amount+$order->order->shipping_charge;
			   }
			}


				//$order= Order::where('delivery_boy_id',$user_id)->where('status','delivered')->orderBy('id','desc')->first();
				//$cod_payment= Order::where('delivery_boy_id',$user_id)->where('payment_mode','cod')->where('is_cod_submitted',0)->sum('total_amount');

			$user= User::select('login_time','is_active')->where('id',$user_id)->first();
			$today_orders= DeliveryBoyRide::select('distance')->where('delivery_boy_id',$user_id)->where('date',$cDate)->get()->count();
				$today_payment=DeliveryBoyRide::select('distance','amount_per_km','bonus')->where('delivery_boy_id',$user_id)->where('date',$cDate)->get();
			    $sum=0;
				$data= DeliveryBoySetting::first();
				foreach($today_payment as $vs)
				{
					$sum= $sum+(($vs->distance*$vs->amount_per_km)+$vs->bonus);
				}
				return Response::json(array(
								'status_code' => 1,
								'current_completed_amount' =>$corder,
								'current_completed_order' =>$order,
								'login_time' =>$user->login_time,
								'cod_amount' =>sprintf ("%.2f",$sum1),
								'today_order_count' =>$today_orders,
								'today_payment' =>sprintf ("%.2f",$sum),
								'status' =>$user->is_active,
								'cod_limit'=>$commission->cod_limit,
								'message' => 'Status has been changed',
								'error_message'=>'Status has been changed',
							), 200);  		 
            }
			
	    //cod List....................................
	    public function cod_list(Request $request){
				$input=json_decode($request->getContent(), true);
				$user_id = $input['user_id'];
				$cod_payment= DeliveryBoyRide::with('order')->where('delivery_boy_id',$user_id)->where('payment_mode','cod')->whereIn('type',array('warehouse_to_customer'))->where('is_cod_submitted',0)->get();
			$dataArr =[];
			foreach ($cod_payment as $cod){
					$amount=Helper::get_order_meta_total($cod->order->id);
					$ordAmount=($amount>0)?$amount+$cod->order->shipping_charge:$amount;
					//$data['amount']=$ordAmount;
					$dataArr[] = ['id'=>$cod->order->id,'order_id'=>$cod->order->order_id,'net_amount'=>$ordAmount,'shipping_charge'=>$cod->order->shipping_charge,'status'=>$cod->order->status,'payment_mode'=>$cod->order->payment_mode,'delivery_date'=>$cod->order->delivery_date];
				}

				return Response::json(array(
								'status_code' => 1,
								'cod_list' =>$dataArr,
								'message' => 'List of Order',
								'error_message'=>'List of Order',
							), 200);
               		 
            }
			
			
			 //cod List....................................
	    public function check_notification_status(Request $request){
				$input=json_decode($request->getContent(), true);
				$seller_id = $input['seller_id'];
				$order_id = $input['order_id'];
				$type = $input['type'];
				$query = \DB::table("delivery_boy_notifications")->where('seller_id',$seller_id)->where('order_id',$order_id)->where('type',$type)->whereIn('status',array('accepted','delivered'))->count();
				//$cod_payment= DeliveryBoyRide::with('order')->where('delivery_boy_id',$user_id)->where('payment_mode','cod')->whereIn('type',array('warehouse_to_customer'))->where('is_cod_submitted',0)->get();
				
				return Response::json(array(
								'status_code' => 1,
								'status' =>($query>0)?1:0,
								'message' => 'status',
								'error_message'=>'Status',
							), 200);
               		 
            }

			//cod List....................................
	    public function today_payment(Request $request){
			$input=json_decode($request->getContent(), true);
			$query = \DB::table("delivery_boy_rides")
								->join('orders', 'orders.id', '=', 'delivery_boy_rides.order_id')
								->join('user_addresses', 'orders.address_id', '=', 'user_addresses.id')
								->join('user_kyc', 'orders.seller_id', '=', 'user_kyc.user_id')
								->join('users', 'orders.user_id', '=', 'users.id')
								->select('delivery_boy_rides.warehouse_id','orders.shipping_charge','delivery_boy_rides.type','delivery_boy_rides.amount_per_km','delivery_boy_rides.bonus','delivery_boy_rides.distance','orders.id','orders.order_id','orders.payment_mode','orders.delivery_date','orders.delivery_time','user_kyc.user_id as seller_id','user_kyc.f_name','user_kyc.l_name','user_kyc.address_2',
								'user_addresses.lattitude as user_lat','user_addresses.longitude as user_long','user_addresses.house','user_addresses.pincode','user_addresses.street','user_addresses.city','user_addresses.state','user_kyc.latitude as seller_lat','user_kyc.longitude as seller_long','users.mobile','users.username as name')
								->where('delivery_boy_rides.delivery_boy_id',$input['user_id'])->orderBy('delivery_boy_rides.id','desc')->get();
				$dataList=array();
				foreach($query as $vs)
				{
					$data=array();
					$data['id']=$vs->id;
					$data['order_id']=$vs->order_id;
					$data['payment_mode']=$vs->payment_mode;
					$data['delivery_date']=$vs->delivery_date;
					$data['delivery_time']=$vs->delivery_time;
					$data['status']="delivered";
					$data['type']=$vs->type;
					if($vs->type=="warehouse_to_customer")
					{
						//pickup location
						$wData= Warehouse::where('id',$vs->warehouse_id)->first();
						$data['pickup_name']=$wData->name;
						$data['pickup_lat']=$wData->lattitude;
						$data['pickup_address']=$wData->address;
						$data['pickup_long']=$wData->longitude;
						$data['pickup_mobile']="";
				        $amount=Helper::get_order_meta_total($vs->id);
						$ordAmount=($amount>0)?$amount+$vs->shipping_charge:$amount;
						$data['amount']=$ordAmount;
				        //drop location  
						$data['seller_name']=$vs->name;
						$data['seller_lat']=$vs->user_lat;
						//$data['seller_address']=$wData->address;
						$data['seller_long']=$vs->user_long;
						$data['user_mobile']=$vs->mobile;
						$data['seller_address']=$vs->house.",".$vs->street.",".$vs->pincode.",".$vs->city.",".$vs->state;
					}
					else
					{
						//pickup location
						$dta=$this->get_seller_details($vs->seller_id);
						$data['pickup_name']=$dta->username;
						$data['pickup_lat']=$vs->seller_lat;
					    $data['pickup_address']=$vs->address_2;
					    $data['pickup_long']=$vs->seller_long;
						$data['pickup_mobile']=$dta->mobile;
						
						//drop location 
						$wData= Warehouse::where('id',$vs->warehouse_id)->first();
						$data['seller_name']=$wData->name;
						$data['seller_lat']=$wData->lattitude;
					    $data['seller_address']=$wData->address;
					    $data['seller_long']=$wData->longitude;
						$data['user_mobile']="";
						$data['amount']=$this->get_order_amount($vs->id,$vs->seller_id);
					}
					//$data['user_lat']=$vs->user_lat;
					$data['seller_id']=$vs->seller_id;
					//$data['user_long']=$vs->user_long;
					
					$data['commission']=sprintf ("%.2f", ($vs->amount_per_km*$vs->distance)+$vs->bonus);
					$data['item_details']=$this->get_item_details($vs->id,$vs->seller_id);
					
					//$data['user_name']=$vs->name;
					$dataList[]=$data;
				}
				return Response::json(array(
								'status_code' => 1,
								'data' =>$dataList,
								'message' => "List of assigned order",
								'error_message'=>"List of assigned order",
							), 200);
							
				/*$input=json_decode($request->getContent(), true);
				$user_id = $input['user_id'];
				$current_day = date("Y-m-d");
				$cod_payment= DeliveryBoyRide::with('order','order.user')->where('delivery_boy_id',$user_id)->where('created_at','like', '%'.$current_day.'%')->get();
				return Response::json(array(
								'status_code' => 1,
								'cod_list' =>$cod_payment,
								'message' => 'List of Order',
								'error_message'=>'List of Order',
							), 200);*/
							
            }

			//cod List....................................
	    public function accept_order(Request $request){
				$input=json_decode($request->getContent(), true);
				$user_id = $input['user_id'];
				$status = $input['status'];
				$order_id = $input['order_id'];
				$seller_id = $input['seller_id'];
				$count = $input['count'];

				$job_id= "JOB-".rand(11,99).rand(99,11).date('d').date('m');
				$chk=Db::table('delivery_boy_notifications')->where('order_id',$order_id)->where('seller_id',$seller_id)->where('status','accepted')->count();
				if($chk>0)
				{
					Db::table('delivery_boy_notifications')->where('order_id',$order_id)->where('seller_id',$seller_id)->where('status','requested')->delete();
				}
				
				Db::table('delivery_boy_notifications')->where('delivery_boy_id',$user_id)->where('seller_id',$seller_id)->where('status','requested')->where('order_id',$order_id)->where('delivery_boy_id',$user_id)->update(['status'=>$status,'job_id'=>$job_id]);
				Db::table('order_metas')->where('seller_id',$seller_id)->where('order_id',$order_id)->update(['delivery_boy_id'=>$user_id]);
			if($count ==2){
				Db::table('delivery_boy_notifications')->where('delivery_boy_id',$user_id)->where('seller_id',$seller_id)->where('order_id',$order_id)->update(['assign_to_driver_status'=>1]);
			}
			return Response::json(array(
								'status_code' => 1,
								'message' => "Order has been $status successfully",
								'error_message'=>"Order has been $status successfully",
							), 200);
               		 
            }
			
			function get_item_details($id,$seller_id)
			{
				$query = \DB::table("order_metas")->select('product_name','product_image','qty')
								->where('order_id',$id)
								->where('seller_id',$seller_id)->get();
				$dataList=array();
				foreach($query as $vs)
				{
					$data=array();
					$data['product_name']=$vs->product_name;
					$data['product_image']=$vs->product_image;
					$data['qty']=$vs->qty;
					$dataList[]=$data;
				}				
				return 	$dataList;			
			}
			
			function get_seller_details($seller_id)
			{
			   $data=User::select('username','mobile')->where('id',$seller_id)->first();
			   return $data;			
			}
			
			

			//cod List....................................
	    public function assigned_order(Request $request){
				$input=json_decode($request->getContent(), true);
				$query = \DB::table("delivery_boy_notifications")
								->join('orders', 'orders.id', '=', 'delivery_boy_notifications.order_id')
								->join('user_addresses', 'orders.address_id', '=', 'user_addresses.id')
								->join('user_kyc', 'orders.seller_id', '=', 'user_kyc.user_id')
								->join('users', 'orders.user_id', '=', 'users.id')
								->select('delivery_boy_notifications.warehouse_id','delivery_boy_notifications.status','delivery_boy_notifications.type','orders.id','orders.shipping_charge','orders.order_id','orders.payment_mode','orders.delivery_date','orders.delivery_time','user_kyc.user_id as seller_id','user_kyc.f_name','user_kyc.l_name','user_kyc.address_2',
								'user_addresses.lattitude as user_lat','user_addresses.longitude as user_long','user_addresses.house','user_addresses.pincode','user_addresses.street','user_addresses.city','user_addresses.state','user_kyc.latitude as seller_lat','user_kyc.longitude as seller_long','users.mobile','users.username as name')
								->whereIn('delivery_boy_notifications.status',array('accepted','requested'))
								->where('delivery_boy_notifications.order_deliverd',0)
								->where('delivery_boy_notifications.delivery_boy_id',$input['user_id'])->orderBy('delivery_boy_notifications.id','desc')->get();
				$dataList=array();
				foreach($query as $vs)
				{
					$data=array();
					$data['id']=$vs->id;
					$data['order_id']=$vs->order_id;
					$data['payment_mode']=$vs->payment_mode;
					$data['delivery_date']=$vs->delivery_date;
					$data['delivery_time']=$vs->delivery_time;
					$data['status']=$vs->status;
					$data['type']=$vs->type;
					if($vs->type=="warehouse_to_customer")
					{
						//pickup location
						$wData= Warehouse::where('id',$vs->warehouse_id)->first();
						$data['pickup_name']=$wData->name;
						$data['pickup_lat']=$wData->lattitude;
						$data['pickup_address']=$wData->address;
						$data['pickup_long']=$wData->longitude;
						$data['pickup_mobile']="";
				        $amount=Helper::get_order_meta_total($vs->id);
						$ordAmount=($amount>0)?$amount+$vs->shipping_charge:$amount;
						$data['amount']=$ordAmount;
				        //drop location  
						$data['seller_name']=$vs->name;
						$data['seller_lat']=$vs->user_lat;
						//$data['seller_address']=$wData->address;
						$data['seller_long']=$vs->user_long;
						$data['user_mobile']=$vs->mobile;
						$data['seller_address']=$vs->house.",".$vs->street.",".$vs->pincode.",".$vs->city.",".$vs->state;
					}
					else
					{
						//pickup location
						$dta=$this->get_seller_details($vs->seller_id);
						$data['pickup_name']=$dta->username;
						$data['pickup_lat']=$vs->seller_lat;
					    $data['pickup_address']=$vs->address_2;
					    $data['pickup_long']=$vs->seller_long;
						$data['pickup_mobile']=$dta->mobile;
						
						//drop location 
						$wData= Warehouse::where('id',$vs->warehouse_id)->first();
						$data['seller_name']=$wData->name;
						$data['seller_lat']=$wData->lattitude;
					    $data['seller_address']=$wData->address;
					    $data['seller_long']=$wData->longitude;
						$data['user_mobile']="";
						$data['amount']=$this->get_order_amount($vs->id,$vs->seller_id);
					}
					//$data['user_lat']=$vs->user_lat;
					$data['seller_id']=$vs->seller_id;
					//$data['user_long']=$vs->user_long;
					
					
					$data['item_details']=$this->get_item_details($vs->id,$vs->seller_id);
					
					//$data['user_name']=$vs->name;
					
					$dataList[]=$data;
				}
				return Response::json(array(
								'status_code' => 1,
								'data' =>$dataList,
								'message' => "List of assigned order",
								'error_message'=>"List of assigned order",
							), 200);
               		 
            }
			
				//cod List....................................
	    public function your_earning(Request $request){
				$input=json_decode($request->getContent(), true);
				$user_id = $input['user_id'];
				$query = \DB::table("delivery_boy_rides")
								->join('orders', 'orders.id', '=', 'delivery_boy_rides.order_id')
								->join('user_addresses', 'orders.address_id', '=', 'user_addresses.id')
								->join('user_kyc', 'orders.seller_id', '=', 'user_kyc.user_id')
								->join('users', 'orders.user_id', '=', 'users.id')
								->select('delivery_boy_rides.warehouse_id','delivery_boy_rides.bonus','delivery_boy_rides.distance','delivery_boy_rides.amount_per_km','delivery_boy_rides.type','orders.id','orders.order_id','orders.payment_mode','orders.delivery_date','orders.delivery_time','user_kyc.user_id as seller_id','user_kyc.f_name','user_kyc.l_name','user_kyc.address_2',
								'user_addresses.lattitude as user_lat','user_addresses.longitude as user_long','user_addresses.house','user_addresses.pincode','user_addresses.street','user_addresses.city','user_addresses.state','user_kyc.latitude as seller_lat','user_kyc.longitude as seller_long','users.mobile','users.username as name')
								->where('delivery_boy_rides.delivery_boy_id',$user_id)->get();

				
				$dataList=array();
				$sum= 0;
				foreach($query as $vs)
				{
					$sum= $sum+(($vs->distance*$vs->amount_per_km)+$vs->bonus);
					
					$data=array();
					$data['id']=$vs->id;
					$data['order_id']=$vs->order_id;
					$data['payment_mode']=$vs->payment_mode;
					$data['earning']=sprintf ("%.2f",(($vs->distance*$vs->amount_per_km)));
					$data['delivery_date']=$vs->delivery_date;
					$data['delivery_time']=$vs->delivery_time;
					$data['distance']=$vs->distance."Km";
					$data['bonus']=$vs->bonus;

					if($vs->type=="warehouse_to_customer")
					{
						//pickup location
						$wData= Warehouse::where('id',$vs->warehouse_id)->first();
						$data['pickup_name']=$wData->name;
						$data['pickup_lat']=$wData->lattitude;
						$data['pickup_address']=$wData->address;
						$data['pickup_long']=$wData->longitude;
						$data['pickup_mobile']="";
                       
						//drop location
						$data['seller_name']=$vs->name;
						$data['seller_lat']=$vs->user_lat;
						//$data['seller_address']=$wData->address;
						$data['seller_long']=$vs->user_long;
						$data['user_mobile']=$vs->mobile;
						$data['seller_address']=$vs->house.",".$vs->street.",".$vs->pincode.",".$vs->city.",".$vs->state;
					}
					else
					{
						//pickup location
						$dta=$this->get_seller_details($vs->seller_id);
						$data['pickup_name']=$dta->username;
						$data['pickup_lat']=$vs->seller_lat;
						$data['pickup_address']=$vs->address_2;
						$data['pickup_long']=$vs->seller_long;
						$data['pickup_mobile']=$dta->mobile;

						//drop location
						$wData= Warehouse::where('id',$vs->warehouse_id)->first();
						$data['seller_name']=$wData->name;
						$data['seller_lat']=$wData->lattitude;
						$data['seller_address']=$wData->address;
						$data['seller_long']=$wData->longitude;
						$data['user_mobile']="";
					}
				/*	if($vs->type=="warehouse_to_customer")
					{
						$wData= Warehouse::where('id',$vs->warehouse_id)->first();
						$data['seller_name']=$wData->name;
						$data['seller_lat']=$wData->lattitude;
						$data['seller_address']=$wData->address;
						$data['seller_long']=$wData->longitude;
					}
					else
					{
						$data['seller_name']=$vs->f_name." ".$vs->l_name;
						$data['seller_lat']=$vs->seller_lat;
					    $data['seller_address']=$vs->address_2;
					    $data['seller_long']=$vs->seller_long;
					}*/
					//$data['user_lat']=$vs->user_lat;
					//$data['user_long']=$vs->user_long;
					
					//$data['user_mobile']=$vs->mobile;
					$data['item_details']=$this->get_item_details($vs->id,$vs->seller_id);
					//$data['user_name']=$vs->name;
					//$data['user_address']=$vs->house.",".$vs->street.",".$vs->pincode.",".$vs->city.",".$vs->state;
					$dataList[]=$data;
				}
				
				return Response::json(array(
								'status_code' => 1,
								'data' => $dataList,
								'total_earning' => sprintf ("%.2f", $sum),
								'message' => "List of your earning",
								'error_message'=>"List of your earning",
							), 200);
               		 
            }
			
				//cod List....................................
	    public function deliver_to_customer(Request $request){
				$input=json_decode($request->getContent(), true);
				$commission=DB::table('delivery_boy_commissions')->first();
				$user_id = $input['user_id'];//rider id
				$order_id = $input['order_id'];
				$seller_id = $input['seller_id'];
				$code = $input['code'];//customer otp
				$checkOtp = $user = DB::table('delivery_boy_notifications')->where('delivery_code',$code)->where('delivery_boy_id',$user_id)->where('status','accepted')->where('seller_id',$seller_id)->where('order_id',$order_id)->first();

			if($checkOtp){

					Db::table('delivery_boy_notifications')->where('delivery_boy_id',$user_id)->where('seller_id',$seller_id)->where('status','accepted')->where('order_id',$order_id)->where('delivery_boy_id',$user_id)->update(['status'=>'delivered']);
					Db::table('delivery_boy_notifications')->where('delivery_boy_id',$user_id)->where('seller_id',$seller_id)->where('order_id',$order_id)->update(['order_deliverd'=>1]);
					$time= date("h:i a");
					$date= date("Y-m-d");
					Db::table('delivery_boy_rides')->insert(['bonus'=>$commission->bonus,'amount_per_km'=>$commission->per_km,'date'=>$date,'delivery_time'=>$time,'job_id'=>$checkOtp->job_id,'delivery_boy_id'=>$checkOtp->delivery_boy_id,'order_id'=>$checkOtp->order_id,'seller_id'=>$checkOtp->seller_id,'user_id'=>$checkOtp->user_id,'distance'=>$checkOtp->distance,'warehouse_id'=>$checkOtp->warehouse_id,'type'=>'warehouse_to_customer','payment_mode'=>'cod']);
					Db::table('order_metas')->where('seller_id',$seller_id)->where('order_id',$order_id)->where('status',"!=",'return')->update(['status'=>'delivered','delivery_date'=>date('Y-m-d')]);//delivered to customer
					Db::table('orders')->where('id',$order_id)->update(['status'=>'delivered','shipped_date'=>date('Y-m-d')]);//delivered to customer
					//update order status for order tracking..........
					Helper::updateOrderStatus($order_id,'delivered','order placed');
					//refer money add to wallet

					$orderDetails = Order::with('order_meta_data')->where('id',$order_id)->first();
					$customerDetails = User::where('id',$orderDetails->user_id)->first();
				     Helper::addReferMoneyToWallet($orderDetails->user_id);
				//add first cashback
				     Helper::addUserFirstCashbackAmount($orderDetails->user_id,$orderDetails->total_amount);
				//add order cashback
				     Helper::addUserOrderCashbackAmount($orderDetails->user_id,$orderDetails->total_amount);
					//send mail....
					$orders = OrderMeta::with('order','order.address_details')->where('order_id',$order_id)->groupBy('order_id')->orderBy('created_at','desc')->first();
					$data['order']=$orders;
					$ordermeta_data = OrderMeta::with('seller')->where('order_id',$order_id)->get();

				//Send sms to user .......
				$mmsg = "Hi " . $customerDetails['username'];
				$mmsg .= " .Your order number $orderDetails->order_id. \n";
				$mmsg .= "Has been delivered successfully. \n";
				$mmsg .= "\n\n Thanks Grocito";
				Helper::send_msg($customerDetails['mobile'], $mmsg);

					$data['order_meta']=$ordermeta_data;
					$msg = "Hi " . $customerDetails['username'] . ", <br><br>  Order Delivered<br><br>";
					$msg .= "Here is your order number $orderDetails->order_id. \n";
					$msg .= "\n\n Thanks Grocito";

					if($customerDetails['email']){

						$emailData = array(
							'to'        => array(strtolower($customerDetails['email'])),
							'from'      => 'support@grocito.com',
							'subject'   => 'Order Delivered',
							'view'      => 'email.order-delivered',
							'content'=>$msg,
							'order'=>$orders,
							'order_meta'=>$ordermeta_data
						);
				Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
					$message
						->to($emailData['to'])
						->from($emailData['from'])
						->subject($emailData['subject']);

				});
					}
					return Response::json(array(
						'status_code' => 1,
						'message' => "Order has been delivered successfully",
						'error_message'=>"Order has been delivered successfully",
					), 200);
				}else{
					return Response::json(array(
						'status_code' => 0,
						'message' => "Invalid code",
						'error_message'=>"invalid code",
					), 200);
				}

               		 
            }

			//cod List....................................
	    public function order_details(Request $request){
				$input=json_decode($request->getContent(), true);
				$order_id = $input['order_id'];
				$seller_id = $input['seller_id'];
				$type = $input['type'];
				$data = \DB::table("orders")
								->join('user_addresses', 'orders.address_id', '=', 'user_addresses.id')
								->join('user_kyc', 'orders.seller_id', '=', 'user_kyc.user_id')
								->join('users', 'orders.user_id', '=', 'users.id')
								->select('orders.id','orders.wallet_amount','orders.shipping_charge','orders.order_id','orders.payment_mode','orders.delivery_time','orders.warehouse_id','user_kyc.f_name','user_kyc.l_name','user_kyc.address_2',
								'user_addresses.lattitude as user_lat','user_addresses.longitude as user_long','user_addresses.mobile as cus_mobile','user_addresses.name as user_name','user_addresses.house','user_addresses.pincode','user_addresses.street','user_addresses.city','user_addresses.state','user_kyc.latitude as seller_lat','user_kyc.longitude as seller_long','users.mobile','users.username as name')
								->where('orders.id',$order_id)->first();
				 $ItemData=OrderMeta::where('order_id',$order_id)->where('seller_id',$seller_id)->get();  
				 $itemArray=array();
				 $sum= 0;
				 $item_count=0;
				 $return_count=0;
				 foreach($ItemData as $vs)
				 {
					 $item_count++;
				   	 $array= array();
					 $array['qty']=$vs->qty;
					 $array['price']=sprintf("%.2f", $vs->price);
					  if($vs->return_status==1)
					 {
						 $return_count++;
					 }
					    $array['name']=$vs->product_name;
					 
					 $array['image']=$vs->product_image;
					 $array['weight']=$vs->weight;
					 $array['return_status']=$vs->return_status;
					 if($vs->return_status==0)
					 {
					 $sum= $sum+($array['price']*$vs->qty);
					 }
					
					 $itemArray[]=$array;
					 
				 }
				 $sum= $sum+$data->shipping_charge;
				 
			 $wData= Warehouse::where('id',$data->warehouse_id)->first();
			  if($type=="seller_to_warehouse")
			   {
				     $name=$wData->name;
				     $address=$wData->address;
				     $mobile=$wData->mobile;
					 
			   }
			   else
			   {
				     $name=$data->user_name;
				     $address=$data->house.",".$data->street.",".$data->pincode;
					 $mobile=$data->cus_mobile;
			   }
				 return Response::json(array(
								'status_code' => 1,
								'data' => $itemArray,
								'name' => $name,
								'item_count' =>$item_count,
								'return_count' => $return_count,
								'shipping_charge' => $data->shipping_charge,
								'wallet_amount' => $data->wallet_amount,
								'mobile' => $mobile,
								'address' => $address,
								'total_amount' => sprintf("%.2f",$sum),
								'order_info' => $data,
								'img_path' => url('/')."/public/admin/uploads/product",
								'message' => "Order has been delivered successfully",
								'error_message'=>"Order has been delivered successfully",
							), 200);
            }
			
			
}
?>