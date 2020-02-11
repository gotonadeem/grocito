<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Slider;
use App\UserKyc;
use App\Enquiry;
use App\OrderMeta;
use App\PushNotification;
use App\Country;
use App\State;
use App\City;
use App\Category;
use App\SubCategory;
use App\Product;
use DB;
use URL;
use Helper;
use Excel;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class NotificationApiController extends Controller
{
	public function __construct()
	{
	  parent::__construct();
	}
	
	
	  public function add_notification_category(Request $request)
     {
		  
		  
		 $notification=array(
		    'customer_id'     => $request->input('user_id'),
            'category_id' => $request->input('category_id'),
		  );
		  
        $rules = array(
            'customer_id'   =>   'required',
            'category_id' => 'required',
        );
        $validator = Validator::make($notification,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
             DB::table('category_notifications')->where('customer_id', $notification['customer_id'])->update(['category_id' =>$notification['category_id']]);
			 return Response::json(array(
                'status_code' => 1,
                'message' => 'Updated Successfully',
                'error_message'=>"Updated Successfully",
            ), 200);
        }
    }
	
	// public function handle()
    // {
	
		      // $colname = date("Y-m-d");
              // $query = \DB::table('products')
						 // ->join('product_sponsors', 'products.id', '=', 'product_sponsors.product_id')
						 // ->join('product_images', 'products.id', '=', 'product_images.product_id')
						 // ->select("products.*",'product_images.image as image')
						 // ->whereRaw('FIND_IN_SET(?,product_sponsors.date)', [$colname])
						 // ->where('product_sponsors.admin_status', 1);
				    // $product_list=$query->get();
					
	
					// foreach($product_list as $vs)
					// {
  						 // $json=array();
						 // $json['id']=$vs->id;
						 // $json['name']=$vs->name;
						 // $json['description'] = $vs->description;
						 // $json['price'] = $vs->starting_price;
						 // $json['sell_price'] = $vs->sell_price;
						 // $json['image'] = $vs->image;
						 // $json['catalog_images'] = Helper::get_catalog_images($vs->id);
						 // $jsonData= $json;
						 /////
						 // $deviceToken = \DB::table('category_notifications')
						 // ->join('users', 'users.id', '=', 'category_notifications.customer_id')
						 // ->select("users.device_token")
						 // ->where('category_notifications.special_status', 1)->get();
						 // $deviceArray=array();
							// foreach($deviceToken as $vs)
							// {
								// $deviceArray[]= $vs->device_token;
							// }
						//print_r($jsonData); die;
						
						// $this->new_push_notification($jsonData,$deviceArray);
						//$this->update_order_status();
					// }	
                   

                
				 
				 					
    // }
	
	public function status_update()
	{
		$orders = OrderMeta::with('order')->where('status','ready_to_ship')->groupBy('order_id')->orderBy('created_at','desc')->get();
		foreach($orders as $vs)
		{
			   $data = array (
				   'DocketNo' => $vs->order->dock_no,
				);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://instacom.dotzot.in/RestService/DocketTrackingService.svc/GetDocketTrackingDetails");
				curl_setopt($ch, CURLOPT_POST, 1);    
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec ($ch);
				curl_close ($ch);
				$data=json_decode($response);
				
				
				if($data[0]->TRACKING_CODE=="DPBSI" or $data[0]->TRACKING_CODE=="DBKNG")
				{
				   $date=date("Y-m-d h:i:s");
				  \DB::table('order_metas')->where('order_id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['status'=>'dispatched']);
				}
				
		}
	}
	
	public function update_order_status()
	{
		$orders = OrderMeta::with('order')->where('status','ready_to_ship')->orWhere('status','dispatched')->groupBy('order_id')->orderBy('created_at','desc')->get();
		foreach($orders as $vs)
		{
			   $data = array (
				   'DocketNo' => $vs->order->dock_no,
				);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://instacom.dotzot.in/RestService/DocketTrackingService.svc/GetDocketTrackingDetails");
				curl_setopt($ch, CURLOPT_POST, 1);    
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec ($ch);
				curl_close ($ch);
				$data=json_decode($response);
				if($data[0]->CURRENT_STATUS=="Delivered")
				{
				   $date=date("Y-m-d h:i:s");
				  \DB::table('orders')->where('id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['shipped_date'=>$date]);
				  \DB::table('order_metas')->where('order_id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['status'=>'shipped']);
				}
				
		}
	}
	
	public function get_merchant_notifications(Request $request)
	{
		 $notification=array(
		    'user_id'     => $request->input('user_id'),
		  );
        $rules = array(
            'user_id'   =>   'required',
        );
        $validator = Validator::make($notification,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
			
             $data= PushNotification::where('user_id',$notification['user_id'])->get();
			 return Response::json(array(
                'data' =>$data,
                'status_code' => 1,
                'message' => 'List Of Notifications',
                'error_message'=>"List Of Notifications",
            ), 200);
			
        }
		
	}
	
	
	
}