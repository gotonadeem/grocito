<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Order;
use App\OrderReturnVideos;
use App\DeliveryBoyNotification;
use App\OrderMeta;
use App\OrderCancel;
use App\OrderRmaDetail;
use App\ResellerPayment;
use App\Warehouse;
use App\DeliveryBoyRide;
use App\OrderExchange;
use App\OrderTracking;
use Carbon\Carbon;
use App\User;
use DB;
use SimpleXMLElement;
use DNS1D;
use DNS2D;
use PDF;
use URL;
use Excel;
use Helper;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class ManageOrderController extends Controller
{

    public function __construct()
    {
		$data=Session::get('user_sdata');
		$this->role= $data->role;
		$this->warehouse_list=Helper::get_warehouse($data->id);
		
        $this->middleware('auth.admin:admin');
    }
	
	public function index()
    {
		
			$query = \DB::table("delivery_boy_notifications")
								->join('orders', 'orders.id', '=', 'delivery_boy_notifications.order_id')
								->join('user_addresses', 'orders.address_id', '=', 'user_addresses.id')
								->join('user_kyc', 'orders.seller_id', '=', 'user_kyc.user_id')
								->join('users', 'orders.user_id', '=', 'users.id')
								->select('delivery_boy_notifications.id as d_id','delivery_boy_notifications.status','delivery_boy_notifications.created_at','orders.id','orders.order_id','orders.payment_mode','orders.delivery_date','orders.delivery_time','user_kyc.user_id as seller_id','user_kyc.f_name','user_kyc.l_name','user_kyc.address_2',
								'user_addresses.lattitude as user_lat','user_addresses.longitude as user_long','user_addresses.house','user_addresses.pincode','user_addresses.street','user_addresses.city','user_addresses.state','user_kyc.latitude as seller_lat','user_kyc.longitude as seller_long','users.mobile','users.username as name')
								->where('delivery_boy_notifications.type','seller_to_warehouse')
								->where('delivery_boy_notifications.status','accepted')->orderBy('id','desc');
				if($this->role==2)
				{
						$query=$query->whereIn('delivery_boy_notifications.warehouse_id',array(explode(",",$this->warehouse_list)));
						     
				}					
		$data=$query->paginate(10);
        return view("admin.manage_order.index",compact('data'));
    }
	
	function accept_order($id=null)
	{
		DB::table('delivery_boy_notifications')->where('id',$id)->update(['status'=>"delivered"]);
		$commission=DB::table('delivery_boy_commissions')->first();
		$data=DeliveryBoyNotification::where('id',$id)->first();
		$dbr['delivery_boy_id']=$data->delivery_boy_id;
		$dbr['order_id']=$data->order_id;
		$odata= Order::where('id',$data->order_id)->select('payment_mode','warehouse_id')->first();
		$dbr['payment_mode']=$odata->payment_mode;
		$dbr['warehouse_id']=$odata->warehouse_id;
		$dbr['seller_id']=$data->seller_id;
		$dbr['user_id']=$data->user_id;
		$dbr['distance']=$data->distance;
		$dbr['amount_per_km']=$commission->per_km;
		$dbr['job_id']=$data->job_id;
		$dbr['bonus']=$commission->bonus;
		$dbr['date']= date('Y-m-d');
		$dbr['type']="seller_to_warehouse";
		$obj= new DeliveryBoyRide($dbr);
		$obj->save();
		DB::table('order_metas')->where('order_id',$data->order_id)->where('seller_id',$data->seller_id)->update(['status'=>"assign_to_warehouse"]);
		Session::flash('success_message', 'You have accepted the order from delivery Boy'); 
		return redirect()->back();		
	}
	
	public function at_warehouse()
    {
		
		    
			        $data = \DB::table("delivery_boy_notifications")
								->join('orders', 'orders.id', '=', 'delivery_boy_notifications.order_id')
								->join('user_addresses', 'orders.address_id', '=', 'user_addresses.id')
								->join('user_kyc', 'orders.seller_id', '=', 'user_kyc.user_id')
								->join('users', 'orders.user_id', '=', 'users.id')
								->select('delivery_boy_notifications.warehouse_id','delivery_boy_notifications.type as d_type','delivery_boy_notifications.assign_to_driver_status','delivery_boy_notifications.id as d_id','delivery_boy_notifications.status','delivery_boy_notifications.created_at','delivery_boy_notifications.seller_id as d_seller_id','orders.id','orders.order_id','orders.payment_mode','orders.delivery_date','orders.delivery_time','user_kyc.user_id as seller_id','user_kyc.f_name','user_kyc.l_name','user_kyc.address_2',
								'user_addresses.lattitude as user_lat','user_addresses.longitude as user_long','user_addresses.house','user_addresses.pincode','user_addresses.street','user_addresses.city','user_addresses.state','user_kyc.latitude as seller_lat','user_kyc.longitude as seller_long','users.mobile','users.username as name')
								->where('delivery_boy_notifications.type','seller_to_warehouse')
								->where('delivery_boy_notifications.status','delivered')->orderBy('id','desc');
					if($this->role==2)
					{
							$data=$data->whereIn('delivery_boy_notifications.warehouse_id',array(explode(",",$this->warehouse_list)));
										
					}				
								 $data=$data->paginate(10);
		
        return view("admin.manage_order.at_warehouse",compact('data'));
    }
	
	public function assign_to_rider()
    {
		$data = \DB::table("delivery_boy_notifications")
								->join('orders', 'orders.id', '=', 'delivery_boy_notifications.order_id')
								->join('user_addresses', 'orders.address_id', '=', 'user_addresses.id')
								->join('user_kyc', 'orders.seller_id', '=', 'user_kyc.user_id')
								->join('users', 'orders.user_id', '=', 'users.id')
								->select('delivery_boy_notifications.warehouse_id','delivery_boy_notifications.id as d_id','delivery_boy_notifications.status','delivery_boy_notifications.created_at','orders.id','orders.order_id','orders.payment_mode','orders.delivery_date','orders.delivery_time','user_kyc.user_id as seller_id','user_kyc.f_name','user_kyc.l_name','user_kyc.address_2',
								'user_addresses.lattitude as user_lat','user_addresses.longitude as user_long','user_addresses.house','user_addresses.pincode','user_addresses.street','user_addresses.city','user_addresses.state','user_kyc.latitude as seller_lat','user_kyc.longitude as seller_long','users.mobile','users.username as name')
								->where('delivery_boy_notifications.type','warehouse_to_customer')
								->whereIn('delivery_boy_notifications.status',array('requested','accepted'))->orderBy('id','desc');
				if($this->role==2)
					{
							$data=$data->whereIn('delivery_boy_notifications.warehouse_id',array(explode(",",$this->warehouse_list)));
						      			
					}				
						    $data=$data->paginate(10);
		
        return view("admin.manage_order.assigned_to_rider",compact('data'));
    }
	
	public function delivered_order()
    {
		
			$data = \DB::table("delivery_boy_notifications")
								->join('orders', 'orders.id', '=', 'delivery_boy_notifications.order_id')
								->join('user_addresses', 'orders.address_id', '=', 'user_addresses.id')
								->join('user_kyc', 'orders.seller_id', '=', 'user_kyc.user_id')
								->join('users', 'orders.user_id', '=', 'users.id')
								->select('delivery_boy_notifications.warehouse_id','delivery_boy_notifications.id as d_id','delivery_boy_notifications.status','delivery_boy_notifications.created_at','orders.id','orders.order_id','orders.payment_mode','orders.delivery_date','orders.delivery_time','user_kyc.user_id as seller_id','user_kyc.f_name','user_kyc.l_name','user_kyc.address_2',
								'user_addresses.lattitude as user_lat','user_addresses.longitude as user_long','user_addresses.house','user_addresses.pincode','user_addresses.street','user_addresses.city','user_addresses.state','user_kyc.latitude as seller_lat','user_kyc.longitude as seller_long','users.mobile','users.username as name')
								->where('delivery_boy_notifications.type','warehouse_to_customer')
								->whereIn('delivery_boy_notifications.status',array('delivered'))->orderBy('id','desc');
					if($this->role==2)
					{
						$data=$data->whereIn('delivery_boy_notifications.warehouse_id',array(explode(",",$this->warehouse_list)));
									
					}
							     $data=$data->paginate(10);
		
        return view("admin.manage_order.delivered_to_customer",compact('data'));
    }
	
   function get_rider(Request $request)
	{
		$id= $request->id;
		$data = \DB::table("users")
								->join('user_kyc', 'user_kyc.user_id', '=', 'users.id')
								->select('users.username','users.id')
								->where('user_kyc.warehouse_id',$id)
								->where('users.is_active',1)
								->get();
		 return view("admin.manage_order.get_rider_ajax",compact('data'));
	}
	function assign_rider(Request $request)
	{	
		$id= $request->id;
		$order_id= $request->order_id;
		$seller_id= $request->seller_id;
		$order_details=Order::with('user_kyc','address','user','seller_kyc','seller')->where('id',$order_id)->first();
	    $item_details=OrderMeta::select(DB::raw('group_concat(product_name) as item_name'),DB::raw('group_concat(qty) as item_qty'),DB::raw('group_concat(weight) as weight'),DB::raw('sum(price*qty) as total_amount'))->where('order_id',$order_details->id)->where('seller_id',$seller_id)->first();

			  //update order status........
			 $data= User::where('id',$id)->where('is_active',1)->get();
			 $riderData= User::where('id',$id)->where('is_active',1)->first();
			 $sellerData= User::where('id',$seller_id)->first();
			 $deviceArray=array();
			 $dbDataList=array();
			 $otp= rand(11,99).rand(22,99);
			 foreach($data as $vs)
			 {
				   
					if($vs->device_token!='')
					{
						 $dbData=array();
					     $dbData['order_id']=$order_details->id;
						 $dbData['distance']="5";
						 $dbData['seller_id']=$order_details->seller->id;
						 $dbData['type']="warehouse_to_customer";
						 $dbData['status']="requested";
						 $dbData['delivery_boy_id']=$vs->id;
						 $dbData['user_id']=$order_details->user->id;
						 $dbData['warehouse_id']=$order_details->warehouse_id;
						 $dbData['delivery_code']=$otp;
						 $dbData['job_id']="JOB-".rand(11,99).rand(99,11).date('d').date('m');
						 $dbDataList[]=$dbData;
					     $deviceArray[]= $vs->device_token;
					}
			 }
			 DB::table('delivery_boy_notifications')->insert($dbDataList);
			 $wData= Warehouse::where('id',$order_details->warehouse_id)->first();
			 $notifyData['id']=$order_details->id;
			 $notifyData['order_id']=$order_details->order_id;
			 $notifyData['amount']=$item_details->total_amount+$order_details->shipping_charge;
			 $notifyData['payment_mode']=$order_details->payment_mode;
			 $notifyData['mobile']=$order_details->user->mobile;
			 $notifyData['address']=$order_details->address->house.",".$order_details->address->street.",".$order_details->address->pincode;
			 $notifyData['username']=$order_details->user->username;
			 $notifyData['seller_name']=$sellerData->username;
			 $notifyData['seller_address']= $order_details->seller_kyc->address_2;
			 $notifyData['delivery_date']=date("d-m-Y");
			 $notifyData['user_long']=$order_details->address->longitude;
			 $notifyData['user_lat']=$order_details->address->lattitude;
			 $notifyData['seller_lat']=$wData->lattitude;
			 $notifyData['seller_long']=$wData->longitude;
			 $notifyData['seller_id']=$order_details->seller->id;
		     $notifyData['type']='rider_order_request';
		     $notifyData['count']=2;
			 $dt=Helper::send_push_notification($deviceArray,$notifyData);
		     Helper::updateOrderStatus($order_details->id,'assign_to_rider_to_deliverd','On the way');
			 DB::table('order_metas')->where('order_id',$order_details->order_id)->where('seller_id',$order_details->seller_id)->update(['status'=>"assign_to_rider_to_deliverd"]);
			 $msg="Dear ".$order_details->user->username.". Your order is assigned to ".$riderData->username. ". Your Delivery Code is $otp.". " Delivery Boy Contact no ".$riderData->mobile;
			 Helper::send_msg($order_details->user->mobile,$msg);
			 
			 echo json_encode(array('status'=>true,'message'=>"assigned successfully"));  
	}
	
}