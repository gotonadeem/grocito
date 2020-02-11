<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use URL;
use App\User;
use App\Order;
use App\OrderMeta;
use App\UserSender;
use Response;
use App\Payment;
use App\Warehouse;
use App\UserKyc;
use App\OrderTracking;
use App\Cart;
use Helper;
use DB;
use DNS1D;
use DNS2D;
use PDF;
use Session;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
class OrderController extends Controller
{
    public function __construct()
    {     
	parent::__construct();
	}

    public function index()
    {

		 if(Auth::user())
		{
			$orders = OrderMeta::select('*', DB::raw('SUM(price*qty) AS amount'))->with('order')->whereIn('status',array('pending'))->where('seller_id',Auth::user()->id)->groupBy('order_id')->orderBy('created_at','desc')->get();
          
			return view('seller.order.index',compact('orders'));
		}
		else
		{
			return redirect("/login");
		}	
    }
	
	public function cancellation()
    {
		 if(Auth::user())
		{
			
			$orders = OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('status','pending')->WhereHas('order', function ($query)
            {
				$date = \Carbon\Carbon::today()->subDays(5);
				$query->where('orders.created_at', '>=', $date);
				
            })->groupBy('order_id')->orderBy('created_at','desc')->get();
		
			
			return view('seller.order.index',compact('orders'));
		}
		else
		{
			return redirect("/login");
		}	
    }
	
	function order_details($order_id)
	{
		 if(Auth::user())
		{
		$orders = OrderMeta::with('order')->where('seller_id',Auth::user()->id)->where('order_id',$order_id)->get();
		$order_info = Order::with('address')->where('id',$order_id)->first();
		}
		else
		{
			return redirect("/login");
		}	
		return view('seller.order.details',compact('orders','order_info'));
		
	}
	
	function get_order_address(Request $request)
	{
		 if(Auth::user())
		{
		$id=$request->input('id');
		$order_info = Order::with('address')->where('id',$id)->first();
		return view('seller.order.address',compact('order_info'));	
		}
	}
	
	function distance($lat1, $lon1, $lat2, $lon2, $unit) {
			  if (($lat1 == $lat2) && ($lon1 == $lon2)) {
				return 0;
			  }
			  else {
				$theta = $lon1 - $lon2;
				$dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
				$dist = acos($dist);
				$dist = rad2deg($dist);
				$miles = $dist * 60 * 1.1515;
				$unit = strtoupper($unit);

				if ($unit == "K") {
				  return ($miles * 1.609344);
				} else if ($unit == "N") {
				  return ($miles * 0.8684);
				} else {
				  return $miles;
				}
			  }
}

	function assign_to_rider(Request $request)
	{
		 if(Auth::user())
		{
				try { 
			 $date=date("Y-m-d");
			 $order_id=$request->input('order_id');
			 $orderData['expected_delivery_date']=date('Y-m-d',strtotime($date));
			 $orderData['status']="assign_to_rider";
			 $order_details=Order::with('user_kyc','address','user','seller_kyc','seller')->where('id',$order_id)->first();
			 $item_details=OrderMeta::select(DB::raw('group_concat(product_name) as item_name'),DB::raw('group_concat(qty) as item_qty'),DB::raw('group_concat(weight) as weight'),DB::raw('sum(price*qty) as total_amount'))->where('order_id',$order_details->id)->where('seller_id',Auth::user()->id)->first();
			 $latlong= UserKyc::where('user_id',Auth::user()->id)->select('latitude','longitude')->first();
			 //update order status........
			 
			$serviceAccount = ServiceAccount::fromJsonFile(public_path().'/grocito-c6f82-firebase-adminsdk-z0mu8-4b836aeef4.json');
			$firebase = (new Factory)
			->withServiceAccount($serviceAccount)
			->withDatabaseUri('https://grocito-c6f82.firebaseio.com/')
			->create();

			$database = $firebase->getDatabase();
			$reference = $database->getReference('grocitorider')
		   ->getSnapshot()->getValue();
			///echo '<pre>';
			//print_r($reference); die;
			
			$lat=0;
			$long=0;
			$deviceArray=array();
			$dbDataList=array();
			foreach($reference as $ks=>$vs)
			{
				$id= $ks;
				
				foreach($vs as $ks1=>$vs1)
				{
				
					if(is_array($vs1))
					{
						$lat= $vs1[0];
						$long= $vs1[1];
					}
					else
					{
						$name=$vs1;
					}
				    
				}
			     $distance=round($this->distance($latlong->latitude, $latlong->longitude, $lat, $long, "K"),2);
				 
				 
				 if($distance<=5)
				 {
					 $data= User::where('role_id',4)->where('id',$id)->where('is_active',1)->get();
					 foreach($data as $vs)
					 {
						   
							if($vs->device_token!='')
							{
								 $dbData=array();
								 $dbData['order_id']=$order_details->id;
								 $dbData['distance']=$distance;
								 $dbData['seller_id']=$order_details->seller->id;
								 $dbData['type']="seller_to_warehouse";
								 $dbData['status']="requested";
								 $dbData['delivery_boy_id']=$vs->id;
								 $dbData['user_id']=$order_details->user_id;
								 $dbData['warehouse_id']=$order_details->warehouse_id;
								 $dbDataList[]=$dbData;
								 $deviceArray[]= $vs->device_token;
							}
					 }
				 }
			 
			}
				
			 //$data= User::where('role_id',4)->where('id',146)->where('is_active',1)->get();
			 DB::table('delivery_boy_notifications')->insert($dbDataList);
			 $wData= Warehouse::where('id',$order_details->warehouse_id)->first();
			 $notifyData['id']=$order_details->id;
			 $notifyData['order_id']=$order_details->order_id;
			 $notifyData['amount']=$item_details->total_amount+$order_details->shipping_charge;
			 $notifyData['payment_mode']=$order_details->payment_mode;
			 $notifyData['mobile']=$order_details->user->mobile;
			// $notifyData['address']=$order_details->address->house.",".$order_details->address->street.",".$order_details->address->pincode;
			 $notifyData['address']=$wData->address;
			 $notifyData['username']=$order_details->user->username;
			 $notifyData['seller_name']=$order_details->seller->username;
			 $notifyData['seller_address']=$order_details->seller_kyc->address_2;
			 $notifyData['delivery_date']=date("d-m-Y");
			 $notifyData['user_long']=$order_details->address->longitude;
			 $notifyData['user_lat']=$order_details->address->lattitude;
			 $notifyData['seller_lat']=$latlong->latitude;
			 $notifyData['seller_long']=$latlong->longitude;
			 $notifyData['seller_id']=$order_details->seller->id;
			 $notifyData['type']='rider_order_request';
			 $notifyData['count']=1;
			 //print_r($deviceArray);
			 $dt=Helper::send_push_notification($deviceArray,$notifyData);
			 Helper::updateOrderStatus($order_id,'assign_to_rider','To Be Accepted Successfully');

                              $data=DB::table('order_metas')->where('order_id', '=',$order_id)->where('seller_id', '=',Auth::User()->id)->update(['expected_delivery_date' =>  $orderData['expected_delivery_date'],'status'=>$orderData['status']]); 		 
                              DB::table('orders')->where('id', '=',$order_id)->update(['status'=>$orderData['status']]);
                              //$order=DB::table('orders')->where('id', '=',$order_id)->update(['status'=>$orderData['status']]); //$orderData['status']
							  Session::flash('success_message', 'Order has been assigned set successfully');
							  //send sms....
								/*$userDetails=User::where('id',$order_details->user_id)->first();
								$usersInfo=User::where('id',$userDetails['id'])->first();
								$mmsg="Hi ".$userDetails['username'].",  \n";
								$mmsg.="Here is you order number ".$order_details->order_id.". \n";
								$mmsg.=" Your order has been dispatched successfully. It will reach you by ".date('d-m-Y',strtotime($date))." \n";
								$mmsg.="Thanks Grocito";
								Helper::send_msg($userDetails['mobile'],$mmsg);
								
								//send mail....
								$msg="Hi ".$userDetails['username'].", <br><br>";
								$msg.="Here if you order number ".$order_details->order_id.". \n";
								$msg.="Your order has been dispatched successfully. It will reach you by ".date('d-m-Y',strtotime($date))." \n";
								$msg.="Thanks Grocito";
							
								$emailData = array(
									'to'        => array(strtolower($usersInfo['email'])),
									'from'      => 'support@grocito.com',
									'subject'   => 'Order Dispatched',
									'view'      => 'email.order-email',
									'content'=>$msg
								);
								Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
									$message
										->to($emailData['to'])
										->from($emailData['from'])
										->subject($emailData['subject']);

								});*/
								/////end/////
							  echo json_encode(array('status'=>true));  
					
				 } 
			catch (\Exception $e) {
			   
				return Response::json(array(
					'status_code' => 0,
					'message' => $e->getMessage()."Line-".$e->getLine(),
				), 500);
			}
			
		} else{
			 echo json_encode(array('not_login'=>true)); 
		}
		
	}
	function order_all_estimate_date(Request $request)
	{
		 if(Auth::user())
		{
			 
			 $date=$request->input('date');
			 $order_id=explode(",",$request->input('order_id'));
			 $orderData['expected_delivery_date']=date('Y-m-d',strtotime($date));
			 $orderData['status']="to_be_dispatched";
					 foreach($order_id as $vs)
					 {
					  $data=DB::table('order_metas')->where('order_id', '=',$vs)->where('seller_id', '=',Auth::User()->id)->update(['expected_delivery_date' =>  $orderData['expected_delivery_date'],'status'=>$orderData['status']]);
					 }
					Session::flash('success_message', 'Expected dispatch date as been set successfully'); 
			 	    echo json_encode(array('status'=>true));
		} else{
			 echo json_encode(array('not_login'=>true)); 
		}
	}
	
	function assign_to_rider_list()
	{
		
		 if(Auth::user())
		{
			$orders = OrderMeta::select('*', DB::raw('SUM(price*qty) AS amount'))->with('order')->where('seller_id',Auth::user()->id)
			->whereIn('status', array('assign_to_rider','assign_to_warehouse','assign_to_rider_to_deliverd','ready_to_shiped'))->groupBy('order_id')->orderBy('created_at','desc')->get();
			
			return view('seller.order.assign_to_rider',compact('orders'));
		}
		else
		{
			return redirect("/login");
		}	
	}
	
	 //delivery.................Method........ is dtdc...........
	 function order_api($order_id)
	 {
		
		  //DTDC.....................................
		$order_details=Order::with('user_kyc','address','user','seller_kyc','seller')->where('id',$order_id)->first();
        $seller_details= UserKyc::with('country','city','state','user')->where('user_id',$order_details->seller_id)->first();   		
		$item_details=OrderMeta::with('product')->where('order_id',$order_details->id)->get();
		//dd($item_details);
		$item_array=array();
		$weight=0;
		foreach($item_details as $vs)
		{
			$item['name']=$vs->product_name;
			$item['sku']=$vs->product->sku;
			$item['units']=$vs->qty;
			$item['selling_price']=$vs->price;
			$item['discount']="";
			$item['tax']="";
			$item['hsn']=123445;
			$item_array[]=$item;
			$weight= $weight+$vs->weight;
		}
		//dd($item_array);
		$weight= round($weight/1000,2);
					   $postData    = array(
				"order_id"    => $order_details->order_id,
				"order_date" =>date('d/m/Y') ,
				"pickup_location" => "Primary",
				"channel_id" => 230310,
				"comment" =>$order_details->seller_kyc->f_name." ".$order_details->seller_kyc->l_name ,
				"billing_customer_name" => $order_details->user_kyc->f_name." ".$order_details->user_kyc->l_name,
				"billing_last_name" => $order_details->user_kyc->l_name,
				"billing_address" => $order_details->address->street,
				"billing_address_2" => $order_details->address->street,
				"billing_city" => $order_details->address->city,
				"billing_pincode" => $order_details->address->pincode,
				"billing_state" => $order_details->address->state,
				"billing_country" => "india",
				"billing_email" => $order_details->user->email,
				"billing_phone" => $order_details->address->mobile,
				"shipping_is_billing" =>true,
				"shipping_customer_name" => "",
				"shipping_last_name" => "",
				"shipping_address" => "",
				"shipping_address_2" => "",
				"shipping_city" => "",
				"shipping_pincode" => "",
				"shipping_country" => "",
				"shipping_state" => "",
				"shipping_email" => "",
				"shipping_phone" => "",
				"order_items" => $item_array,
				"payment_method" =>'Prepaid',
				"shipping_charges" =>$order_details->shipping_charge,
				"transaction_charges" =>0,
				"giftwrap_charges" => 0,
				"total_discount" =>0,
				"sub_total" =>$order_details->payment_amount,
				"length" =>1,
				"breadth" =>1,
				"height" =>1,
				"weight" =>$weight,
			);

			 $response=json_decode(Helper::place_order($postData));
			// print_r($response); die;
			 return $response;
	 }
	 
	 
	function order_ready_to_ship(Request $request)
	{
		 if(Auth::user())
		{
			 $order_id=$request->input('order_id');
		     $order_details=Order::with('user_kyc','address','user','seller_kyc','seller')->where('id',$order_id)->first();
			 $response=$this->order_api($order_id); 
			 print_r($response);
			 if($response->order_id)
			 {
				                  //$trackData['order_id']=$order_id;
								  //$trackData['reason']="Ready To Ship Successfully";
								  // $trackData['date']=date('Y-m-d h:i:s');
								  // $trackData['type']="ready_to_ship";
								  // $trackObj= new OrderTracking($trackData);
								  // $trackObj->save();
								  
								  $rmsg="Order has been uploaded to Ship rocket successfully";
								  $order1=DB::table('orders')->where('id', '=',$order_id)->where('seller_id', '=',Auth::user()->id)->update(['courier_shipment_id'=>$response->shipment_id,'courier_shipment_id'=>$response->order_id,'shipped_by'=>'Ship Rocket']);	 
								  $orderData['status']="ready_to_ship";
								  $orderUpdate=DB::table('order_metas')->where('order_id',$order_id)->where('seller_id', '=',Auth::user()->id)->update(['status'=>'ready_to_ship']);
								  $order_details=Order::with('user_kyc','address','user','seller_kyc','seller')->where('id',$order_id)->first();
								   if($orderUpdate)
									 {

										  // send sms....
											$userDetails=User::where('id',$order_details->user_id)->first();
							            	$usersInfo=User::where('id',$userDetails['id'])->first();
											$msg="Hi ".$userDetails['username'].", <br><br>";
											$mmsg.="Here if you order number ".$order_details->order_id.". \n";
											$mmsg.=" Your order has been dispatched successfully. \n";
											$mmsg.="<a href='https://www.shiprocket.in/shipment-tracking/'>Click here</a> to track your order using tracking number ".$order_details->order_id." \n";
											$mmsg.="Thanks Gracito";
											Helper::send_msg($userDetails['mobile'],$mmsg);
											
											// send mail....
											$msg="Hi ".$userDetails['username'].", <br><br>";
											$msg.="Here if you order number ".$order_details->order_id.". \n";
											$msg.="Your order has been dispatched successfully \n";
											$msg.="<a href='https://www.shiprocket.in/shipment-tracking/'>Click here</a> to track your order using tracking number ".$order_details->order_id." \n";
											$msg.="Thanks Gracito";
										
											$emailData = array(
												'to'        => array(strtolower($usersInfo['email'])),
												'from'      => 'support@Gracito.com',
												'subject'   => 'Order Dispatched',
												'view'      => 'email.order-email',
												'content'=>$msg
											);
											 Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
												 $message
													 ->to($emailData['to'])
													 ->from($emailData['from'])
													 ->subject($emailData['subject']);

											 });
											
										  Session::flash('success_message', 'Order has been ready to shipped successfully');
										  echo json_encode(array('status'=>true,'message'=>'Order Has been shipped successfully'));
									 }  
			 }
			 else
			 {
							   Session::flash('success_message', 'Something went wrong. Please Try again');
							   echo json_encode(array('status'=>false,'message'=>'Please Tray Again'));
			 }
			 //echo json_encode(array('status'=>true));
			// print_r($response);
             //die;
			 
		} 
		else{
			 echo json_encode(array('not_login'=>true)); 
		}
	}
	
	function order_all_ready_to_ship(Request $request)
	{
		 if(Auth::user())
		{
			 $order_id=$request->input('order_id');
			 foreach($order_id as $vs)
			 {
			 $orderData['status']="ready_to_ship";
			 $data=DB::table('order_metas')->where('order_id', '=',$vs)->where('seller_id', '=',Auth::User()->id)->update(['status'=>$orderData['status']]);
			 }
			   Session::flash('success_message', 'Order has been ready to shipped successfully');
			   echo json_encode(array('status'=>true));
		} else{
			 echo json_encode(array('not_login'=>true)); 
		}
	}
	
	//Get ready to ship order list...............
	function ready_to_ship(Request $request)
	{
		 if(Auth::user())
		{
			$orders = OrderMeta::select('*', DB::raw('SUM(price*qty) AS amount'))->with('order')->where('seller_id',Auth::user()->id)->where('status','ready_to_ship')->groupBy('order_id')->orderBy('created_at','desc')->get();
			return view('seller.order.ready_to_shipped',compact('orders'));
		}
		else
		{
			return redirect("/login");
		}	
	}
	///end......................................
	
	function download_invoice_shipment($id)
	{
		$orders = OrderMeta::with('order','seller_kyc','order.address_details')->where('seller_id',Auth::user()->id)->where('order_id',$id)->where('status','delivered')->groupBy('order_id')->orderBy('created_at','desc')->first();
	
		$data['order']=$orders;
		$pdf = PDF::loadView('seller.order.pdf.slip', $data);
		//$paper_orientation = 'landscape';
		//$customPaper = array(0,0,950,950);
		//$pdf->set_paper($customPaper,$paper_orientation);
		$label= $orders->order->order_id;
		//echo storage_path()."<br>";
		
		//$pdf->save(storage_path().'_'.$label.'.pdf');
		return $pdf->download($label.'.pdf');
	}
	
	function cancel_order(Request $request)
	{
		 if(Auth::user())
		{
		    $order_id=$request->input('id');
			$updateOrderStatus = DB::table('orders')->where('id',$order_id)->update(['status' => 'cancelled']);
			$updateOrderMetaStatus = DB::table('order_metas')->where('seller_id',Auth::user()->id)->where('order_id',$order_id)->update(['status' => 'cancelled']);
			Helper::updateOrderStatus($order_id,'cancelled','Order Cancelled');
			 if($updateOrderMetaStatus)
			 {     
				  Session::flash('success_message', 'Order has been Cancelled');
				  echo json_encode(array('status'=>true));
			 }
			 else
			 {
				 echo json_encode(array('status'=>false)); 
			 }
		} else{
			 echo json_encode(array('not_login'=>true)); 
		}
	}
	
	function cancelled_order()
	{
		 if(Auth::user())
		{
			$orders = OrderMeta::select('*', DB::raw('SUM(price*qty) AS amount'))->with('order')->where('seller_id',Auth::user()->id)->where('status','cancelled')->groupBy('order_id')->orderBy('created_at','desc')->paginate(10);
			return view('seller.order.cancelled',compact('orders'));
		}
		else
		{
			return redirect("/login");
		}	
    }
	
	function delivered_order()
	{

		 if(Auth::user())
		{
			$orders = OrderMeta::select('*', DB::raw('SUM(price*qty) AS amount'))->with('order')->where('seller_id',Auth::user()->id)->whereIn('status',array('delivered','shipped'))->groupBy('order_id')->orderBy('created_at','desc')->paginate(10);
			return view('seller.order.shipped_order',compact('orders'));
		}
		else
		{
			return redirect("/login");
		}
	}

	function return_order(Request $request)
	{
		 if(Auth::user())
		{
			$orders = OrderMeta::with('order')->select('*', DB::raw('SUM(price*qty) AS amount'))->where('seller_id',Auth::user()->id)->where('status','return')->groupBy('order_id')->orderBy('created_at','desc')->get();
			return view('seller.order.return_order',compact('orders'));
		}
		else
		{
			return redirect("/login");
		}
	}

	function exchange_order(Request $request)
	{
		 if(Auth::user())
		{
			$orders = OrderMeta::select('*', DB::raw('SUM(price*qty) AS amount'))->with('order')->where('seller_id',Auth::user()->id)->where('status','exchange')->groupBy('order_id')->orderBy('created_at','desc')->get();
			return view('seller.order.exchange_order',compact('orders'));
		}
		else
		{
			return redirect("/login");
		}
	}
//END ---------------------
}