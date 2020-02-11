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
use App\Wallet;
use App\Enquiry;
use App\OrderCancel;
use App\Category;
use Carbon\Carbon;
use App\UserProductShare;
use App\ProductCategory;
use App\Product;
use App\ProductImage;
use App\UserWallet;
use App\SubCategory;
use App\Cart;
use App\Order;
use App\OrderMeta;
use App\DeliveryPincode;
use App\ResellerPayment;
use App\MerchantCategory;
use DB;
use URL;
use Helper;
use Excel;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class CatalogApiController extends Controller
{
	public function __construct()
	{
	  parent::__construct();
	}


    public function get_category()
    {
        
         $category= MerchantCategory::where('status',1)->get();
         if($category->count()>0)
         {
            return Response::json(array('status' => 1, 'data' =>$category), 200);
         }
         else
         {
            return Response::json(array('status' => 0, 'data' =>array()), 200);
         }
    }

	/*..................product view...................................................................*/
    public function product_view_api(Request $request){
        $product_id=$request->input('product_id');
		$catatlog_details=Product::with('product_image','product_category')->where('id',$product_id)->first();
		$catalog_url= url('/').'/public/uploads/seller/catalog/';
		$category_url= url('/').'/public/admin/uploads/category/';
		$catalog_images = Helper::get_catalog_images($product_id);
		$ratingDetails = Helper::get_rating($product_id);
		//print_r($catatlog_details); die;
		
		if($catatlog_details) {
            return Response::json(array('status' => 1,'img_category_path'=>$category_url,'rating'=>$ratingDetails,'catalog_images'=>$catalog_images,'product_image_path'=>$catalog_url, 'data' => $catatlog_details), 200);
        }
        else
        {
            return Response::json(array('status' => 0, 'data' =>array(),'category'=>array()), 200);
        }
    }
	
	/*..................add to cart...................................................................*/
	 public function add_to_cart(Request $request)
     {
		 $product_id= $request->input('product_id');
		 $pInfo=Product::where('id',$product_id)->select('name','weight','user_id','is_shipping_free','shipping_free_amount','is_return','is_in_exchange')->first();
		 $pImage=ProductImage::where('is_default',1)->select('image')->first();
         $users = array(
            'product_id'  => $request->input('product_id'),
            'user_id'     => $request->input('user_id'),
            'qty'         =>$request->input('qty'),
            'size'         =>$request->input('size'),
            'weight'         =>$pInfo['weight'],
            'is_shipping_free' =>$pInfo['is_shipping_free'],
            'shipping_free_amount' =>$pInfo['shipping_free_amount'],
			'product_name'=>$pInfo['name'],
			'product_image'=>$pImage['image'],
			'is_return'=>$pInfo['is_return'],
			'is_in_exchange'=>$pInfo['is_in_exchange'],
			'seller_id'=>$pInfo['user_id'],
        );
		
        $rules = array(
            'product_id' =>   'required',
             'user_id'    =>   'required',
            'weight'    =>   'required',
            'qty'        =>   'required',
            'size'        =>   'required',
        );
        $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
			
			$cartQuery=Cart::where('user_id',$request->input('user_id'))->where('size',$request->input('size'));
			if($cartQuery->count()>0)
			{
				$data= $cartQuery->first();
				$qty= $data->qty + $request->input('qty');
		        DB::table('carts')->where('user_id', $request->input('user_id'))->update(['qty' =>$qty]);
                 return Response::json(array(
                'status_code' => 2,
                'message' => 'Product is updated into cart',
                'error_message'=>"Product is updated into cart",
                ), 200);
			}
			else
			{
              $user = new Cart($users);
              $user->save();
				  return Response::json(array(
					'status_code' => 1,
					'message' => 'Product is added into cart',
					'error_message'=>"Product is added into cart",
				), 200);
			}
            
        }
    }
	
	public function clear_cart(Request $request)
	{
		 $users = array(
            'user_id'  => $request->input('user_id'),
			  );
		
        $rules = array(
            'user_id' =>   'required',
	       );
		    $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
	      $cartRemove = Cart::where('user_id', $users['user_id']);
	      $cartRemove->delete(); 
		   return Response::json(array(
                'status_code' => 1,
                'message' => 'Cart has been Cleared',
                'error_message'=>"Cart has been Cleared",
            ), 200);
		}
	}
	/*..................update cart...................................................................*/
	public function update_cart(Request $request)
     {
        $users = array(
            'product_id'  => $request->input('product_id'),
            'user_id'     => $request->input('user_id'),
            'size'         => $request->input('size'),
            'qty'         => $request->input('qty'),
            'cart_id'     => $request->input('cart_id'),
        );
		
        $rules = array(
            'product_id' =>   'required',
            'user_id'    =>   'required',
            'qty'        =>   'required',
            'cart_id'    =>   'required',
        );
        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
			
            DB::table('carts')->where('user_id', $request->input('user_id'))->where('product_id', $request->input('product_id'))->where('id', $request->input('cart_id'))->update(['qty' =>$request->input('qty'),'size'=>$request->input('size')]);
            return Response::json(array(
                'status_code' => 1,
                'message' => 'Qty has been updated',
                'error_message'=>"Qty has been updated successfully",
            ), 200);
        }
    }	
       /*..................get cart...................................................................*/
	public function get_cart(Request $request)
     {
        $users = array(
            'user_id'     => $request->input('user_id'),
        );
		
        $rules = array(
             'user_id'    =>   'required',
        );
        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
            
			$cartList= Cart::with('cart_product','cart_image')->where('user_id',$request->input('user_id'))->get();
			$cartCount= Cart::where('user_id',$request->input('user_id'))->get()->count();
			$weight= Cart::where('user_id',$request->input('user_id'))->sum('weight');
            $sum=0;
            $weightSum=0;
            $ssum=0;
            $use_wallet=0;
            $delivery_charge=0;
			if($cartCount>0)
			{
				foreach($cartList as $vs)
				{
					if(!is_null($vs->cart_product))
					{
						if($vs->cart_product->sell_price)
						{
						 $sell_price=$vs->cart_product->sell_price+$vs->cart_product->shipping_free_amount;
						  $sum= $sum+ $sell_price *$vs->qty;
						}
						else
						{
						  $sum= $sum+ $vs->cart_product->starting_price*$vs->qty;	
						}
						 if($vs->is_shipping_free==0)
						 {
						   $weightSum= $weightSum+ $vs->weight * $vs->qty;
						 }
					}
					$s=$vs->cart_product->starting_price+$vs->cart_product->shipping_free_amount;
                    $ssum= $ssum + $s*$vs->qty;
					
					$product_amount=($vs->cart_product->a_sell_price+$vs->cart_product->shipping_free_amount)*$vs->qty;
                    $wallet_amount= $product_amount*$vs->cart_product->w_commission/100;
                    $use_wallet= $use_wallet+$wallet_amount;
				}
			}
			
			$wallet_amount= Wallet::where('user_id',$users['user_id'])->where('status','approved')->get()->sum('amount');
			
			return Response::json(array(
                'status_code' => 1,
                'message' => 'List Of Cart Products',
                'data' => $cartList,
                'count' => $cartCount,
                'mrp' => $ssum,
                'weight' =>  $weightSum,
                'delivery_charge' => $delivery_charge,
                'wallet_amount' => $wallet_amount,
                'use_wallet' =>$use_wallet,
                'sub_total' => $sum,
                'error_message'=>"List Of Cart Products",
				'product_image_path'=>url('/').'/public/uploads/seller/catalog/',
            ), 200);
        }
    }

    /*..................get cart...................................................................*/
	public function get_cart_count(Request $request)
     { 
        $users = array(
            'user_id'     => $request->input('user_id'),
        );
		
        $rules = array(
             'user_id'    =>   'required',
        );
        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
            $userDeleteStatus= User::where('id',$users['user_id'])->where('role_id',3)->get()->count();
            $userActiveStatus= User::where('id',$users['user_id'])->where('role_id',3)->first();
			$cartCount= Cart::where('user_id',$request->input('user_id'))->get()->count();
			$seller= Cart::where('user_id',$request->input('user_id'))->pluck('seller_id');
			$version= GeneralSetting::pluck('app_version')->first();
			//$wallet_request= UserWallet::where('user_id',$users['user_id'])->where('status',0)->get()->count();
			$sum=0;
			return Response::json(array(
                'status_code' => 1,
                'message' => 'Cart Count',
                'cart_count' =>$cartCount,
                'seller_id' =>@$seller[0],
                'version' =>$version,
                //'wallet_request' =>$wallet_request,
                'user_delete_status' =>(($userDeleteStatus>0)?1:0),
                //'user_active_status' =>(($userActiveStatus->banned==0)?1:0),
            ), 200);
        }
    }
	
	
	/*............................delete cart product...............................*/
	public function delete_cart(Request $request)
     {
        $users = array(
            'user_id'     => $request->input('user_id'),
            'cart_id'     => $request->input('cart_id'),
        );
		
        $rules = array(
             'user_id'    =>   'required',
             'cart_id'    =>   'required',
        );
        $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
            $data=Cart::where('user_id', $request->input('user_id'))->where('id', $request->input('cart_id'))->delete();
            return Response::json(array(
                'status_code' => 1,
                'message' => 'product deleted from cart',
                'error_message'=>"product deleted from cart",
            ), 200);
        }
    }	
	
	public function getNextOrderNumber()
   {
		// Get the last created order
		$lastOrder = Order::orderBy('id', 'desc')->first();
		if ( ! $lastOrder )
		{
			
			$number = 0;
		}
		else 
		{ 
		  $number = $lastOrder->id;
		  return 'CORD' . sprintf('%06d', intval($number));
		}
 }

   /*..................place order...................................................................*/
	public function place_order(Request $request)
     {	 
        $users = array(
            'user_id'    =>$request->input('user_id'),
            'address_id' =>$request->input('address_id'),
            'payment_amount'=>$request->input('payment_amount'),
            'payment_mode'=>$request->input('payment_mode'),
            'wallet_amount'=>$request->input('wallet_amount'),
            'seller_id'=>$request->input('seller_id'),
            'shipping_charge'=>$request->input('shipping_charge'),
        );
		
        $rules = array(
            'user_id' =>'required',
            'address_id'=>'required',
            'payment_amount'=>'required',
        );
        $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
			DB::beginTransaction();
			try { 
                    $user_id=$request->input('user_id');
					$orderData=Cart::with('cart_product')->where('user_id',$user_id)->get();
					$orderCountData=Cart::with('cart_product')->where('user_id',$user_id)->get()->count();
					if($orderCountData>0)
					{
						$user = new Order($users);
					    $user->save();
					    $order_number="#".$this->getNextOrderNumber();
					    DB::table('orders')->where('id',$user->id)->update(['order_id' =>$order_number]);
					
						$orderArray=array();
						foreach($orderData as $vs):
						$ordData=array(
						'order_id'=>$user->id,
						'product_id'=>$vs->product_id,
						'size'=>$vs->size,
						'price'=>(($vs->cart_product->sell_price>0)?$vs->cart_product->sell_price:$vs->cart_product->starting_price),
						'qty'=>$vs->qty,
						'seller_id'=>$vs->seller_id,
						'weight'=>$vs->weight,
						'product_image'=>$vs->product_image,
						'shipping_free_amount'=>$vs->shipping_free_amount,
						'is_return'=>$vs->is_return,
						'is_in_exchange'=>$vs->is_in_exchange,
						'product_name'=>$vs->product_name,
						);
						$orderArray[]=$ordData;  
						endforeach;
						OrderMeta::insert($orderArray);
						DB::commit();
						if($users['payment_mode']=="cod")
						{
								 //remove cart...
						$cartRemove = Cart::where('user_id',$user_id);
						$cartRemove->delete(); 
						DB::table('order_metas')->where('order_id',$user->id)->update(['status' =>'pending']);
						DB::table('orders')->where('id',$user->id)->update(['payment_status' =>'cod']);
						$reseller_payment['user_id']=$users['user_id'];
						$reseller_payment['order_id']=$user->id;
						$reseller_payment['amount']=$users['margin_amount'];
						$reseller_payment['type']='margin_amount';
						$reseller_payment['seller_id']=$users['seller_id'];
						$paymentR= new ResellerPayment($reseller_payment);
						$paymentR->save();
						
						}
						if($users['payment_mode']=="cod")
						{
								
								
								//send sms....
								$userDetails=UserSender::where('id',$request->input('sender_id'))->first();
								$usersInfo=User::where('id',$userDetails['user_id'])->first();
								$mmsg="Hi ".$userDetails['name'].", \n thanks for placing your order with Cartlay. \n";
								$mmsg.="Here if you order number $order_number. \n";
								$mmsg.=" It will be dispatched soon. \n";
								$mmsg.="\n\n Thanks Cartlay";
								Helper::send_msg($userDetails['mobile'],$mmsg);
								
								//send mail....
								$msg="Hi ".$userDetails['name'].", <br><br>  Thanx for placing order on Cartlay<br><br>";
								$msg.="Here if you order number $order_number. \n";
								$msg.="It will be dispatched soon. \n";
								$msg.="\n\n Thanks Cartlay";
							
							$emailData = array(
								'to'        => array(strtolower($usersInfo['email'])),
								'from'      => 'support@cartlay.com',
								'subject'   => 'Order Placed',
								'view'      => 'email.order-email',
								'content'=>$msg
							);
							// Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
								// $message
									// ->to($emailData['to'])
									// ->from($emailData['from'])
									// ->subject($emailData['subject']);

							// });
						 }
					
						return Response::json(array(
							'status_code' => 1,
							'message' => 'Order has been placed successfully',
							'order_id' => $order_number,
							'order_number' =>$user->id,
						), 200);
					}
				} 
				catch (\Exception $e) {
                   DB::rollBack();
					return Response::json(array(
						'status_code' => 0,
						'message' => $e->getMessage(),
					), 500);
				}
        }
    }
	
	
	public function change_order_status(Request $request)
	{
		           
			try { 		
					$order_number= $request->input('order_number');
					$transaction_id= $request->input('transaction_id');
					$user_id= $request->input('user_id');
					$status= $request->input('status');
					if($status=="pending")
					{	
					$ordData=DB::table('orders')->where('id',$order_number)->update(['transaction_id' =>$transaction_id,'payment_status'=>'success']);
                    $data=DB::table('order_metas')->where('order_id',$order_number)->update(['status' =>$status]); 					
					//remove cart...
					$cartRemove = Cart::where('user_id',$user_id);
					$cartRemove->delete();
                    /*---------------------mail and msg -----------------*/
                     					//send sms....
						   $ordData= Order::select('address_id','order_id')->where('id',$order_number)->first();		
						   $userDetails=UserAddress::where('id',$ordData->address_id)->first();
						   $usersInfo=User::where('id',$userDetails['user_id'])->first();
						   $mmsg="Hi ".$userDetails['name'].", \n thanks for placing your order with Saleplus. \n";
						   $mmsg.="Here if you order number ".$ordData->order_id.". \n";
						   $mmsg.=" It will be dispatched soon. \n";
						   $mmsg.=" Thanks Saleplus";
						   Helper::send_msg($userDetails['mobile'],$mmsg);
							//send mail....
							$msg="Hi ".$userDetails['name'].", <br><br>  Thanx for placing order on Saleplus<br><br>";
							$msg.="Here if you order number ".$ordData->order_id.". \n";
							$msg.="It will be dispatched soon. \n";
							$msg.="\n\n Thanks Saleplus";
						
						$emailData = array(
							'to'        => array(strtolower($usersInfo['email'])),
							'from'      => 'support@saleplus.com',
							'subject'   => 'Order Placed',
							'view'      => 'email.order-email',
							'content'=>$msg
						);
						// Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
							// $message
								// ->to($emailData['to'])
								// ->from($emailData['from'])
								// ->subject($emailData['subject']);

						// });
						
					}
					else
					{
					$data=DB::table('order_metas')->where('order_id',$order_number)->update(['status' =>$status]);
					DB::table('orders')->where('id',$order_number)->update(['payment_status' =>'faild']);
					}
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Order has been placed successfully',
					), 200);
				} 
				catch (\Exception $e) {
                   //DB::rollBack();
					return Response::json(array(
						'status_code' => 0,
						'message' => $e->getMessage(),
					), 500);
				}
			
	
	}
	
	/*..................get order...................................................................*/
	public function get_order(Request $request)
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
					 $orders = Order::with('user_kyc','address')->orderBy("id",'desc')->where('user_id',$users['user_id'])->where('payment_status','!=','faild')->get();
					 $orderList=array();
				     foreach($orders as $vs)
					 {
						 $orderData=array();
						 $orderData['id']=$vs->id;
						 $orderData['display_id']=$vs->order_id;
                         $orderData['total_amount']=$vs->payment_amount;
                         $orderData['payment_mode']=$vs->payment_mode;
                         $orderData['margin_amount']=$vs->margin_amount;
                         $orderData['extra_amount']=$vs->extra_amount;
						 $orderData['status']=Helper::get_order_item_status($vs->id);
                         $orderData['shipping_charge']=$vs->shipping_charge;
                         $orderData['created_at']=date('d M,h:i a', strtotime($vs->created_at));
                         $orderData['user_name']= $vs->address->name;				 
 					     $orderData['product_name']= Helper::get_product_name($vs->id);
 					     $orderData['product_image']= Helper::get_product_image($vs->id);
 					     $orderList[]=$orderData;
					 } 
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Order List',
						'data'=>$orderList,
						'product_image_path'=>url('/').'/public/uploads/seller/catalog/',
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
	
	/*..................get order Details...................................................................*/
	public function get_order_details(Request $request)
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
					    $vs = Order::with('user_kyc','sender','address','product')->where('id',$users['order_id'])->first();
					   
						$orderData=array();
						 $orderData['id']=$vs['id'];
						 $orderData['display_id']=$vs['order_id'];
                         $orderData['total_amount']=$vs['payment_amount'];
                         $orderData['margin_amount']=$vs['margin_amount'];
                         $orderData['shipping_charge']=$vs['shipping_charge'];
                         $orderData['awb_no']=$vs['dock_no'];
						 $orderData['shipped_by']= $vs['shipped_by'];
                         $orderData['extra_amount']=$vs['extra_amount'];
                        
                         $orderData['shipped_time']= round((strtotime(date('Y-m-d h:i')) - strtotime($vs['shipped_date']))/3600, 1);
                         $orderData['payment_mode']=$vs['payment_mode'];
                         $orderData['created_at']=date('d M,h:i a', strtotime($vs['created_at']));
                         $orderData['user_name']= $vs['user_kyc']['f_name']." ".$vs['user_kyc']['l_name'];				 
                         $orderData['product_list']= $vs['product'];
						  
                         $orderData['sender']= $vs['sender'];				 
                         $orderData['address']= $vs['address'];				 
                         
						 
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Order Details',
						'data'=>$orderData,
						'product_image_path'=>url('/').'/public/uploads/seller/catalog/',
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
	
	/*..................update order status...................................................................*/
	public function update_order_status(Request $request)
     {
        $users = array(
            'id'    =>$request->input('id'),
            'order_id'    =>$request->input('order_id'),
            'reason'    =>$request->input('reason'),
            'comment'    =>$request->input('comment'),
            'status'    =>$request->input('status'),
            'product_id'    =>$request->input('product_id'),
        );
        $rules = array(
            'order_id' =>'required',
            'product_id' =>'required',
            'reason' =>'required',
            'id' =>'required',
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
			       
				    $response=DB::table('order_metas')->where('order_id', $users['order_id'])->where('product_id', $users['product_id'])->where('status','!=','shipped')->where('id', $users['id'])->update(['status' =>$users['status']]);
					if($response)
					{
						
						$cancelData['order_id']=  $users['order_id'];
						$cancelData['order_meta_id']=  $users['id'];
						$cancelData['reason']=  $users['reason'];
						$cancelData['comment']=  $users['comment'];
						$obj= new OrderCancel($cancelData);
						$obj->save();
					}
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Order status has been changed successfully',
					), 200);
				} 
				catch (\Exception $e) {
                   return Response::json(array(
						'status_code' => 0,
						'message' => $e,
					), 500);
				}
        }
    }
	
	
	public function track_order_status($order_id,$product_id)
	{
		$orders = OrderMeta::with('order')->where('product_id',$product_id)->where('order_id',$order_id)->first();
		  $data = array (
				   'DocketNo' => $orders->order->dock_no,
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
	
	/*..................update order status...................................................................*/
	public function track_order(Request $request)
     {
        $users = array(
            'order_id'    =>$request->input('order_id'),
            'product_id'    =>$request->input('product_id'),
        );
        $rules = array(
            'order_id' =>'required',
            'product_id' =>'required',
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
				    $data=OrderMeta::select('status','created_at')->where('order_id', $users['order_id'])->where('product_id', $users['product_id'])->first();      
					return Response::json(array(
						'status_code' => 1,
						'message1' => 'Order has been placed successfully on'.date('d M,h:i a', strtotime($data['created_at'])),
						'message2' => 'You order status is '.str_replace("_"," ",$data['status']),
						'message3' => $this->track_order_status($users['order_id'],$users['product_id']),
						
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
	
	
	
	
	/*..................add sender...................................................................*/
	public function add_sender(Request $request)
	{
	    $user = array(
            'name'       => $request->input('name'),
            'user_id'    =>$request->input('user_id'),
            'mobile'     => $request->input('mobile'),
            'is_default' =>1,
        );
		
		$default_status=array(
		'is_default'=>0,
		);
		
        $rules = array(
            'name'    =>   'required',
            'mobile'  =>   'required',
            'user_id' =>   'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {
            
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
               

            ), 200);
            
        }else{
			   if(!empty($request->input('sender_id')))
			   {
					DB::table('user_senders')->where('user_id', $request->input('user_id'))->update(['is_default' => 0]);	
				    $update = UserSender::findOrFail($request->input('sender_id'));
                    $update->fill($user)->save();
			   }
			   else{
				    DB::table('user_senders')->where('user_id', $request->input('user_id'))->update(['is_default' => 0]);
                    $user = new UserSender($user);
                    $user->save();
			   }
			   
              return Response::json(array(
                'status_code' => 1,
                'message' => 'successfully saved',
                'error_message'=>"saved successfully",
            ), 200);
        }
	}
	
	/*..................get sender list...................................................................*/
	 public function get_sender(Request $request)
    {
        $user = array(
            'user_id'    =>$request->input('user_id'),
        );
        $rules = array(
            'user_id'    =>     'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {           
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
               

            ), 200);
            
        }else{
			   $data=UserSender::orderBy('updated_at', 'DESC')->where("user_id",$request->input('user_id'))->get();
			   $id= UserSender::where('user_id', $request->input('user_id'))->where('is_default', 1)->pluck('id')->first();
              return Response::json(array(
                'status_code' => 1,
				'default_id'=> $id,
                'message' => 'List of Senders',
                'data' => $data,
                'error_message'=>"List of Senders",
            ), 200);
        }
    }
	
	/*..................set default sender...................................................................*/
	public function default_sender(Request $request)
	{
		 $user = array(
            'sender_id'    =>$request->input('sender_id'),
            'user_id'    =>$request->input('user_id'),
        );
        $rules = array(
            'user_id'    =>     'required',
            'sender_id'    =>     'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {           
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
            
        }else{
			  DB::table('user_senders')->where('user_id', $request->input('user_id'))->update(['is_default' => 0]);  
			  DB::table('user_senders')->where('user_id', $request->input('user_id'))->where('id', $request->input('sender_id'))->update(['is_default' => 1]);
              return Response::json(array(
                'status_code' => 1,
                'message' => 'updated successfully',
                'error_message'=>"Updated successfully",
            ), 200);
        }
	}
	
	/* delivery....................pincode check............ */
	function check_delivery_pincode($pincode)
	{
	    $curl = curl_init();
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://track.delhivery.com/c/api/pin-codes/json/?token=236d8546e58918e5f1c5d296357a79fca288af0d&filter_codes=$pincode",
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
	
	/*..................check pincode existance...................................................................*/
	public function check_pincode(Request $request)
	{
		 $user = array(
             'pincode'    =>$request->input('pincode'),
        );
        $rules = array(
             'pincode'    =>     'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {           
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
            
        }else{
			 $status= json_decode(Helper::check_pincode_api($user['pincode']));
			 if(count($status))
			 {
				  return Response::json(array(
					'status_code' => 1,
					'message' => 'Delivery is avaible at this location',
				), 200);
			 }
			 else
			 {
				   if($this->check_delivery_pincode($user['pincode'])=="Yes")
				   {
					   return Response::json(array(
							'status_code' => 1,
							'message' => 'Delivery is avaible at this location',
						), 200); 
				   }
				   else
				   {
					   return Response::json(array(
						'status_code' => 0,
						'message' => 'Unable to deliver at this location',
					  ), 200);
				   }
			 }
        }
	}
	
	/*..................check pincode existance...................................................................*/
	public function check_pincode_with_payment(Request $request)
	{
		
		 $user = array(
             'type'    =>$request->input('type'),
             'address_id'    =>$request->input('address_id'),
        );
        $rules = array(
             'type'    =>     'required',
             'address_id'    =>     'required',
            );
        $validator = Validator::make($user,$rules);
        if ($validator->fails()) {           
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
            
        }else{
			
			 $address= UserAddress::where('id',$user['address_id'])->first();
			 $status= json_decode(Helper::check_pincode_api_with_payment($address->pincode,$user['type']));
			 if(count($status))
			 {
				  return Response::json(array(
					'status_code' => 1,
					'message' => 'Delivery is available at this location',
				), 200);
			 }
			 else
			 {
				 
				 $type= ($request->input('type')=="prepaid")?'pre_paid':'cod';
				 $response=Helper::check_pincode_api_with_payment_delivery($address->pincode,$type);
				 if($response=="Yes")
				 {
					  return Response::json(array(
					'status_code' => 1,
					'message' => 'Delivery is available at this location',
				      ), 200);
				 }
                 else
				 {					 
						   return Response::json(array(
							'status_code' => 0,
							'message' => $user['type']." is not available at this location",
						), 200);
				 }
			 }
        }
	}
	/*----sharing Count ----*/
	public function get_sharing_count(Request $request)
     {
        $users = array(
            'id'     => $request->input('id'),
            'user_id'     => $request->input('user_id'),
        );
		
        $rules = array(
             'id'    =>   'required',
             'user_id'    =>   'required',
        );
        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
            
			$shareCount= Product::where('id',$request->input('id'))->first();
			$shareCountValue= $shareCount['share_count']+1;
			$product_info['share_count']=$shareCountValue;
			$update=Product::find($users['id'])->fill($product_info);
			$update->update();
			if (!UserProductShare::where('user_id', '=', $users['user_id'])->where('product_id', '=', $users['id'])->count() > 0) {
            $userShare['user_id']= $users['user_id'];
			$userShare['product_id']= $users['id'];
			$obj= new UserProductShare($userShare);
			$obj->save();
            }
			return Response::json(array(
                'status_code' => 1,
                'message' => 'Share Count added successfully',
                'data' =>$shareCountValue,
            ), 200);
        }
    }
	
	/*----sharing Count for popular----*/
	// public function get_collection(Request $request)
     // {
        // $product_list = Product::with('product_image')->where('status',1)->orderBy('share_count','desc')->take(10)->get();
		// $jsonData=array();
		
		// foreach($product_list as $vs)
		// {
			 // $json=array();

			 // $json['id']=$vs->id;
             // $json['name']=$vs->name;
             // $json['description'] = $vs->description;
             // $json['links'] = $vs->links;
             // $json['price'] = $vs->starting_price;
             // $json['sell_price'] = $vs->sell_price;
             // $json['image'] = @$vs->product_image[0]->image;
             // $json['catalog_images'] = Helper::get_catalog_images($vs->id);
             // $jsonData[]= $json; 
		// }			
     	    // $catalog_url= url('/').'/public/uploads/seller/catalog/';
            // return Response::json(array('status' => 1,'img_catalog_path'=>$catalog_url,'catalog'=>$jsonData), 200);
       
    // }
	
	/*----- collection api ----*/
	public function get_collection()
     {
		$category = DB::table('categories')
			->join('products', 'categories.id', '=', 'products.category_id')
			->where('products.status', '=', 1)
			//->where('products.is_collection', '=', 1)
			->groupBy('products.category_id')
			->select('categories.*',DB::raw("products.id as product_id"))
			->get();
			     $data= GeneralSetting::select('popular_image')->first();
			     $jsonData=array();
				 $jsonArray=array();
				 //get popular category......
				 $count=Product::with('product_image')->where('status',1)->orderBy('share_count','desc')->take(10)->count();
			     $json['id']='popular';
				 $json['name']="Popular";
				 $json['image'] = $data->popular_image;
				 $json['new_count'] = $count;
				 $json['sub_cat_status'] = 0;
				 $jsonData[]= $json;
				 
			foreach($category as $ks=>$vs)
			{
				 $json=array();
				 $json['id']=$vs->id;
				 $json['name']=$vs->name;
				 $json['image'] = $vs->image;
				 $json['new_count'] = Helper::get_new_product($vs->id);
				 $json['sub_cat_status'] = 0;
				 $jsonData[]= $json;
			}
               			   
			$slider_url=  url('/').'/public/admin/uploads/slider_image/'; 
			$category_url= url('/').'/public/admin/uploads/category/';
			$catalog_url= url('/').'/public/uploads/seller/catalog/';
			
				return Response::json(array('status' => 1 ,'img_category_path'=>$category_url,'img_catalog_path'=>$catalog_url ,'category'=>$jsonData), 200);
			
	 }
	
	/*----- Subcategory api ----*/
	public function get_sub_category(Request $request)
     {
		    $id=$request->input('category_id');
		    $category = DB::table('sub_categories')
			   ->join('products', 'sub_categories.id', '=', 'products.sub_category_id')
			   ->where('sub_categories.category_id', '=', $id)
			   ->groupBy('products.sub_category_id')
			   ->select('sub_categories.*',DB::raw("products.id as product_id"),DB::raw("count(products.id) as design"),DB::raw("min(products.starting_price) as starting_price"))
			   ->get();
		   
			$jsonData=array();
			foreach($category as $vs)
			{
				 $json=array();
				 $json['id']=$vs->id;
				 //$json['sub_category']=Helper::get_sub_category($vs->id);
				 $json['name']=$vs->name;
				 $json['description'] = $vs->description;
				 $json['image'] = $vs->image;
				 $json['status'] = $vs->status;
				 $json['catalog_images'] = Helper::get_catalog_images($vs->product_id);
				 $json['design'] = $vs->design;
				 $json['starting_price'] = $vs->starting_price;
				 $jsonData[]= $json;
			}			
			$category_url= url('/').'/public/admin/uploads/category/';
			$catalog_url= url('/').'/public/uploads/seller/catalog/';
		    return Response::json(array('status' => 1,'img_category_path'=>$category_url,'img_catalog_path'=>$catalog_url,'category'=>$jsonData), 200);
	 }
	 
	 /*..................My Payment...................................................................*/
	public function my_payment(Request $request)
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

			$payments = DB::table('reseller_payments')
				->join('orders', 'orders.id', '=', 'reseller_payments.order_id')
				->where('orders.user_id', '=',$users['user_id'])
				->where('orders.payment_mode', '=','cod')
				->groupBy('reseller_payments.order_id')
				->orderBy('reseller_payments.order_id','desc')
				->select(DB::raw('sum(reseller_payments.amount+reseller_payments.extra_amount+reseller_payments.shipping_charge+reseller_payments.return_amount) AS sum'),'orders.created_at','orders.shipped_date','orders.order_id','orders.id')
				->get();
			   
				   $payment_array_total=array();
				   $total_sum=0;
				   foreach($payments as $vs)
				   {
					   $payment_array=array();
					   $payment_array['total_amount']= $vs->sum;
					   if($vs->shipped_date=="0000-00-00")
					   {
					   $payment_array['status']= "pending";
					   }
					   else
					   {
				       $payment_array['status']= (round((strtotime(date('Y-m-d h:i')) - strtotime($vs->shipped_date))/3600, 1)>=48)?'completed':'pending';
				          if((round((strtotime(date('Y-m-d h:i')) - strtotime($vs->shipped_date))/3600, 1)>=48))
					      {
						    $total_sum=$total_sum+ $vs->sum; 
					      }
					   }
					   
					  
				      
					   $payment_array['created_at']= $vs->created_at;
					   $payment_array['order_id']= $vs->order_id;
					   $payment_array['id']= $vs->id;
					   $payment_array_total[]=$payment_array;
					   
				   }				   
				
				
				$payments_taken = DB::table('user_wallets')
				->where('user_id', '=',$users['user_id'])
				->where('status', '=',1)
				->groupBy('user_id')
				->select(DB::raw('sum(amount) AS sum'))
				->first();
				$taken= (!is_null($payments_taken)?$payments_taken->sum:'');
				
				// $payment_total = DB::table('orders')
				// ->where('orders.user_id', '=',$users['user_id'])
				// ->where('orders.shipped_date', '!=','0000-00-00')
				// ->groupBy('orders.user_id')
				// ->select(DB::raw('sum(orders.margin_amount) AS sum'))
				// ->first();
				
				   $amount=$total_sum;
				   return Response::json(array(
						'status_code' => 1,
						'message' => 'Payment List',
						'data' => $payment_array_total,
						'total' =>  $amount-$taken,
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

	
	
	/*.....................................................................................*/
	public function product_rating(Request $request)
     {
        $users = array(
            'user_id'    =>$request->input('user_id'),
            'product_id' =>$request->input('product_id'),
            'order_id' =>$request->input('order_id'),
            'item_id' =>$request->input('item_id'),
            'rating'     =>$request->input('rating'),
            'message'    =>$request->input('message'),
			
        );
        $rules = array(
            'user_id' =>'required',
            'product_id' =>'required',
            'order_id' =>'required',
            'item_id' =>'required',
            'rating' =>'required',
            'message' =>'required',
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
				  $data=ProductRating::where('order_id',$users['order_id'])->where('product_id',$users['product_id'])->where('user_id',$users['user_id'])->get()->count();
				  if($data>0)
				  {
					  return Response::json(array(
						'status_code' => 0,
						'message' => 'You have already rated to this product',
					), 200);
				  }
				  else
				  {
				   $user = new ProductRating($users);
				   $user->save();
				   return Response::json(array(
						'status_code' => 1,
						'message' => 'Rating has been added successfully',
					), 200);
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
	
	
	function get_previous_product(Request $request)
	{
		    $page= $request->input('page');
		    $limit=10;
			$skip=$page-1;
			$offset= $limit * $skip;
		    $product_list = Product::with('product_image')->where('status',1)->where('is_admin_approved',1)->orderBy('id','desc')->where('stock_status',1)->where('updated_at', '>=', Carbon::now()->subHours(190)->toDateTimeString())->skip($offset)->take($limit)->get();
			$jsonData=array();
			foreach($product_list as $vs)
			{
				 $json=array();
				 $json['id']=$vs->id;
				 $json['name']=$vs->name;
				 $json['description'] = $vs->description;
				 $json['weight'] = $vs->weight;
				 $json['price'] = $vs->starting_price;
				 $json['sell_price'] = $vs->sell_price;
				 $json['is_shipping_free'] = $vs->is_shipping_free;
				 $json['is_return'] = $vs->is_return;
				 $json['is_cod'] = $vs->is_cod;
				 $json['product_rating'] = $vs->product_rating->avg('rating');
				 $json['image'] = $vs->image; 
				 $json['date'] = $vs->created_at; 
				 $json['catalog_images'] = Helper::get_catalog_images($vs->id);
				 $jsonData[]= $json;
			}			
		    
			$catalog_url= url('/').'/public/uploads/seller/catalog/';
			if(sizeof($product_list)>0) {
				return Response::json(array('status' => 1,'img_catalog_path'=>$catalog_url,'catalog'=>$jsonData), 200);
			}
		
	}
	
}
?>