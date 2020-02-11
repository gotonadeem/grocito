<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Notice;
use App\OrderMeta;
use App\Order;
use App\UserAddress;
use App\UserKyc;

use Helper;
use DB;
class TestController extends Controller
{
	var $key1="AAAA_YtrgDM:APA91bHGwuMXAqYx9630IBtWm2LcGrEu9VOyZZd4-Pzd2fNmfcQENhFUPLyU5ZiKHkDVSFOYwboLhD-otKdTWqCB6GuwYirAM9fL6P5LRoT-jyRBxGsN7iVId_7_DFfsPb_SYiSup437";
	
    public function __construct()
    {		parent::__construct();
	   
    }
     public function update_order_status_dtdc($dock_no)
	  {
		    $data = array (
				   'DocketNo' => $dock_no,
				);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://instacom.dotzot.in/RestService/DocketTrackingService.svc/GetDocketTrackingDetails");
				curl_setopt($ch, CURLOPT_POST, 1);    
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($data));
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec ($ch);
				curl_close ($ch);
				return json_decode($response);
				
	  }	
	  
	 public function update_order_status_delivery($dock_no)
	 {
		 //echo $dock_no; die;
		 $old="https://track.delhivery.com/api/packages/json/?token=2713249514eb30ccbf6c3a5d8d9f423d8b5173a5&waybill=$dock_no&verbose=2";
		 
		 $new="https://track.delhivery.com/api/packages/json/?token=236d8546e58918e5f1c5d296357a79fca288af0d&waybill=$dock_no&verbose=2";
				 $curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => $new,
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
					"Postman-Token: ef04c8f0-99c2-4604-a356-119b2f5bf67f",
					"content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
					return false;
				  //echo "cURL Error #:" . $err;
				} else {
				 $data= json_decode($response);
				 return $data;
				}
				
	 }	

	 
	 public function update_order_status()
	{
		$orders = OrderMeta::with('order')->where('status','ready_to_ship')->orWhere('status','dispatched')->groupBy('order_id')->orderBy('created_at','desc')->get();
		foreach($orders as $vs)
		{
			if($vs->order->id>1079)
			{
			    if($vs->order->shipped_by=="Dotzot")
				{
					
					 $data=$this->update_order_status_dtdc($vs->order->dock_no);
					 $reason=$data[0]->CURRENT_STATUS;
					 if($data[0]->CURRENT_STATUS=="Delivered")
					{
						
					   $date=date("Y-m-d h:i:s");
					  \DB::table('orders')->where('id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['shipped_date'=>$date]);
					  \DB::table('order_metas')->where('order_id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['status'=>'shipped']);
					}
					else
					{
						\DB::table('orders')->where('id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['reason'=>$reason]);
					
					}
				}
				elseif($vs->order->shipped_by=="Delhivery")
				{
					 $data=$this->update_order_status_delivery($vs->order->dock_no);
					  if(!is_null($data))
					  {
						  
						  if(!is_null($data->ShipmentData))
						  {
							 $reason=$data->ShipmentData[0]->Shipment->Status->Instructions;
							 if($data->ShipmentData[0]->Shipment->Status->Status=="Delivered")
							 {
								   $date=date('Y-m-d', strtotime($data->ShipmentData[0]->Shipment->Status->StatusDateTime));
								  \DB::table('orders')->where('id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['shipped_date'=>$date]);
								  \DB::table('order_metas')->where('order_id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['status'=>'shipped']);
							 }
							 else
							 {
									\DB::table('orders')->where('id', '=',$vs->order->id)->where('seller_id', '=',$vs->order->seller_id)->update(['reason'=>$reason]);
							 }
						  }
					  }
					  
						 //Instructions
				}
			}
				
				
		}
	}


	
	
	
	
	public function notification()
	{
		$colname = date("Y-m-d");
		
              $query = \DB::table('products')
						 ->join('product_sponsors', 'products.id', '=', 'product_sponsors.product_id')
						 ->join('product_images', 'products.id', '=', 'product_images.product_id')
						 ->select("products.*",'product_images.image as image')
						 ->whereRaw('FIND_IN_SET(?,product_sponsors.date)', [$colname])
						 ->where('product_sponsors.admin_status', 1);
				    $product_list=$query->get();
					
	
					foreach($product_list as $vs)
					{
  						 $json=array();
						 $json['id']=$vs->id;
						 $json['name']=$vs->name;
						 $json['description'] = $vs->description;
						 $json['price'] = $vs->starting_price;
						 $json['sell_price'] = $vs->sell_price;
						 $json['image'] = $vs->image;
						 $json['catalog_images'] = Helper::get_catalog_images($vs->id);
						 $jsonData= $json;
						 ///////
						 $deviceToken = \DB::table('category_notifications')
						 ->join('users', 'users.id', '=', 'category_notifications.customer_id')
						 ->select("users.device_token")
						 ->where('category_notifications.special_status', 1)->where('users.id', 1153)->get();
						 $deviceArray=array();
							foreach($deviceToken as $vs)
							{
								if($vs->device_token!='')
								{
								$deviceArray[]= $vs->device_token;
								}
							}
						
						$this->new_push_notification($jsonData,$deviceArray);
						
					}
	}
    		
  public function new_push_notification(Request $request)
	{ 
	    $user_id=40;
	    $device_token="";
	    $msg="hello";
	
	     Helper::send_push_notification(array($device_token),$msg,$user_id);
	}
	
	
	function  test_delivery()
	{
		
		     $order_id=472;
			 $order_details=Order::with('user_kyc','address','user','seller_kyc','seller')->where('id',$order_id)->first();
			  $seller_details= UserKyc::with('country','city','state','user')->where('user_id',$order_details->seller_id)->first();
			  //dd($order_details);
			 $item_details=OrderMeta::select(DB::raw('group_concat(product_name) as item_name'),DB::raw('group_concat(qty) as item_qty'),DB::raw('group_concat(weight) as weight'))->where('order_id',$order_details->id)->first();
			 ///push order to dtdc...............
			 $weight= explode(",",$item_details->weight);
			 $weight_list= array();
			 $sumW=0;
			 foreach($weight as $vs)
			 {
				 $sumW= $sumW + $vs;
				 
				
			 }
			 $weight_list= number_format($sumW/1000,2);
				$token = "afc916e6bc46e77b757ceb4fde514a0a74d8c4ae"; // replace this with your token key
				$url = "https://test.delhivery.com/cmu/push/json/?token=".$token;
				$params = array(); // this will contain request meta and the package feed
				$package_data = array(); // package data feed
				$shipments = array();
				$pickup_location = array();
				/////////////start: building the package feed/////////////////////

				$shipment = array();
				$shipment['waybill'] = ''; // waybill number
				$shipment['name'] = $order_details->user_kyc->f_name." ".$order_details->user_kyc->l_name;// consignee name
				$shipment['order'] = $order_details->order_id; // client order number
				$shipment['products_desc'] = $item_details->item_name;
				$shipment['order_date'] = date("c");; // ISO Format
				$shipment['payment_mode'] = 'COD';
				$shipment['total_amount'] = ($order_details->payment_mode=="cod")?$order_details->payment_amount+$order_details->margin_amount+$order_details->shipping_charge+$order_details->extra_amount:0; // in INR
				$shipment['cod_amount'] = $order_details->shipping_charge; // amount to be collected, required for COD
				$shipment['add'] = $order_details->address->street; // consignee address
				$shipment['city'] = $order_details->address->city;
				$shipment['state'] = $order_details->address->state;
				$shipment['country'] = 'India';
				$shipment['phone'] = $order_details->address->mobile;
				$shipment['pin'] = $order_details->address->pincode;
				$shipment['seller_gst_tin'] = $order_details->seller_kyc->gst_number;
				$shipment['client_gst_tin'] = 'XXXXXXXXXXXX';
				$shipment['hsn_code'] = ['02314h03'];
				$shipment['quantity'] = 1; // quanitity of quantity
				$shipment['weight'] = $weight_list; // quanitity of quantity
				$shipment['seller_name']=$order_details->seller_kyc->f_name." ".$order_details->seller_kyc->l_name; //name of seller
				$shipment['seller_add']=$order_details->seller_kyc->address_1; // add of seller
				$shipment['seller_cst'] = ''; //cst number of seller
				$shipment['seller_tin'] = '';  //tin number of seller
				$shipment['seller_inv']= $order_details->order_id; // invoice number of shipment
				$shipment['seller_inv_date']= '2013-04-08T18:30:00+00:00'; // ISO Format
				// pickup location information //
				$pickup_location['add'] = $order_details->seller_kyc->address_1;
				$pickup_location['city'] = $seller_details->city->name;
				$pickup_location['country'] ='India';
				$pickup_location['name'] = 'DENTMARK';  // Use client warehouse name
				$pickup_location['phone'] = $seller_details->city->name;
				$pickup_location['pin'] = $seller_details->pincode;
				$pickup_location['state'] = $seller_details->state->name;
				$shipments = array($shipment);
				$package_data['shipments'] = $shipments;
				$package_data['pickup_location'] = $pickup_location;
				$params['format'] = 'json';
				$params['data'] =json_encode($package_data);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($params));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
				$data1=curl_exec($ch);
				curl_close($ch);
				echo "<pre>";
				$response=json_decode($data1);
				print_r($response); 
				echo $response->packages[0]->status;
				//$data1=json_decode($result);
				
				
	}
	
	function test_pincode()
	{
		
			$curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://delhivery.com/c/api/pin-codes/json/?token=2713249514eb30ccbf6c3a5d8d9f423d8b5173a5&filter_codes=302013&pre_paid=Y",
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
			  echo "cURL Error #:" . $err;
			} else {
			  $data= json_decode($response);
			  // print_r($data);
			  // $count=count($data->delivery_codes);
				  // if($count>0)
				  // {
					 // echo "yes";
				  // }
				  // else
				  // {
					  // echo 'No';
				  // }			  
			}
		 
	}
	
	function track_order_delivery()
	{

		$curl = curl_init();

		curl_setopt_array($curl, array(
		  CURLOPT_URL => "https://test.delhivery.com/api/packages/json/?token=afc916e6bc46e77b757ceb4fde514a0a74d8c4ae&waybill=76110000641&verbose=2",
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
			"Postman-Token: ef04c8f0-99c2-4604-a356-119b2f5bf67f",
			"content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
		  ),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
		  echo "cURL Error #:" . $err;
		} else {
		 $data= json_decode($response);
		 echo "<pre>";
		 print_r($data); 
		 print_r($data->ShipmentData[0]->Shipment->Status->Status);
		 //Dispatched
		}
		 
	}
	
	
}