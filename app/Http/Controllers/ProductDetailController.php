<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use Helper;
use App\Slider;
use App\GeneralSetting;
use App\SubCategory;
use App\ProductImage;
use App\ProductItem;
use App\Dip;
use App\Wishlist;
use App\Cart;
use App\Gallery;
use App\UserKyc;
use App\Category;
use App\Product;
use App\User;
use Response;
use DB;
use Session;
class ProductDetailController extends Controller
{
	protected $encrypt;
    public function __construct()
    {
       parent::__construct();
		$this->encrypt=md5($_SERVER['REMOTE_ADDR'].$_SERVER['SERVER_ADDR'].$_SERVER['SERVER_PORT'].$_SERVER['HTTP_USER_AGENT']);
    }
    
    public function index(Request $request)
    {
    	   $segment= $request->segment(2);
           $product_details= Product::with('user_name','product_image','main_category','brand','product_item')->where('slug',$segment)->first();
		   $wishlist=array();
           $all_image= ProductImage::where('product_id',$product_details->id)->get();

		return view('front.product.product_details',compact('product_details','all_image','wishlist'));
    }
	public function getItemList(Request $request){
		$sellerId = $request->input('sellerId');
		$productId = $request->input('productId');
		$item = ProductItem::where('seller_id',$sellerId)->where('product_id',$productId)->get();
		return view('front.product.item_dropdown',compact('item'));
	}
	public function getSellerName(Request $request){
		$sellerId = $request->input('sellerId');
		$seller = User::where('id',$sellerId)->first();
		echo json_encode(array('status'=>true,'message'=>'successfully','seller_name'=>$seller->username));
	}
	public function checkItemStock(Request $request){
		$itemId = $request->input('itemId');
		$quantity = $request->input('quantity');
		$itemQty = ProductItem::where('id',$itemId)->first()->qty;

		if($itemQty >= $quantity){
			echo json_encode(array('status'=>1,'message'=>'successfully'));
		}else if($itemQty == 0){
			echo json_encode(array('status'=>2,'message'=>'error'));
		}else{
			echo json_encode(array('status'=>3,'message'=>'error'));
		}

	}
	public function check_delivery(Request $request)
	{
	    $delivery_pincode= $request->input('pincode');
		$seller_id= $request->input('seller_id');
		$pickup_postal_code= UserKyc::select('pincode')->where('user_id',$seller_id)->first();
				$product_weight= round($request->input('product_weight')/1000,2);
				$status= Helper::check_pincode($pickup_postal_code->pincode,$delivery_pincode,$product_weight);
				if($status)
				{ 
				     return Response::json(array(
						  'status' => 1,
						  'message' => 'Service is avialble at this location',
						  'error_message'=>"Service is avialble at this location",
					  ), 200); 
				}
				else
				{
					 return Response::json(array(
						  'status' => 0,
						  'message' => 'Service is not avialble at this location',
						  'error_message'=>"Service is not avialble at this location",
					  ), 200); 
			
				} 
		}
    
	 function add_to_cart(Request $request)
    {
         $data['product_id']= $request->product_id;
		 $product_data= Product::with('product_image')->where('id',$data['product_id'])->first();
         $data['qty']= $request->qty;
         $data['weight']= $product_data->weight;
         $data['seller_id']= $product_data->user_id;
         $data['size']= $request->size;
         $data['product_name']= $product_data->name;
         $data['product_image']=$product_data->product_image[0]->image;
         $data['system_address']= $this->encrypt;
		
					 $check_exists= Cart::select('id','qty')->where('system_address',$data['system_address'])->where('size',$data['size'])->where('product_id',$data['product_id'])->first();
					 if($check_exists)
					 {
						  $qty['qty']= $check_exists->qty+$data['qty'];
						  $cartObj= Cart::findOrFail($check_exists->id);
						  if($cartObj->fill($qty)->save())
						  {
							 return Response::json(array(
								'status' => 1,
								'message' => 'Added to cart Successfully',
								'cart_count' => Cart::where('system_address',$data['system_address'])->get()->count(),
								'error_message'=>"Added to cart Successfully",
							   ), 200); 
						  }
						  else
						  {
							  return Response::json(array(
								'status' => 1,
								'message' => 'Added to cart Successfully',
								'cart_count' => Cart::where('system_address',$data['system_address'])->get()->count(),
								'error_message'=>"Added to cart Successfully",
							), 200); 

						  }
					 
					 }
					 else
					 {
						 $obj= new Cart($data); 
						 if($obj->save($data))
						 {
							 return Response::json(array(
								'status' => 1,
								'message' => 'Added to cart Successfully',
								'cart_count' => Cart::where('system_address',$data['system_address'])->get()->count(),
								'error_message'=>"Added to cart Successfully",
							), 200); 
						 }
						 else
						 {

							 return Response::json(array(
								'status' => 0,
								'message' => 'Please Try again',
								'error_message'=>"Please Try again",
							), 200); 

						 }

					}
			 
		 

     }



     function cart()
     {

     	  //$system_address= md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
     	  //$cart_data= Cart::with('cart_product')->where('system_address',$system_address)->get();
     	  return view('front.product.cart');

     }
	 
	 function clear_user_cart(Request $request)
	 {
			 $system_address= $this->encrypt;
			 $data['product_id']= $request->product_id;
			 $product_data= Product::with('product_image')->where('id',$data['product_id'])->first();
			 $data['qty']= $request->qty;
			 $data['weight']= $product_data->weight;
			 $data['seller_id']= $product_data->user_id;
			 $data['size']= $request->size;
			 $data['product_name']= $product_data->name;
			 $data['product_image']=$product_data->product_image[0]->image;
			 $data['system_address']= $this->encrypt;
			 DB::table('carts')->where('system_address',$system_address)->delete();
			 $obj= new Cart($data); 
			 if($obj->save($data))
			 {
				 return Response::json(array(
					'status' => 1,
					'message' => 'Added to cart Successfully',
					'cart_count' => Cart::where('system_address',$data['system_address'])->get()->count(),
					'error_message'=>"Added to cart Successfully",
				), 200); 
			 }
			 else
			 {

				 return Response::json(array(
					'status' => 0,
					'message' => 'Please Try again',
					'error_message'=>"Please Try again",
				), 200); 

			 }
	 }



     function update_cart(Request $request)
     {
           
        if(isset($_POST['update_cart']))
        {
          $cart_id=$request->input('cart_id');
          $quantity=$request->input('quantity');
          foreach($cart_id as $ks=>$vs)
          {
               DB::table('carts')->where('id',$vs)->update(['qty'=>$quantity[$ks]]);
          }
		  Session::flash('success_message', 'Qty has been updated successfully');
        }

        if(isset($_POST['delete_cart']))
        {
           $system_address=$this->encrypt;
           DB::table('carts')->where('system_address',$system_address)->delete();   
		   Session::flash('success_message', 'Cart has been cleared');
        }
		
        return redirect()->back();
     }



     public function quick_view(Request $request)
    {
        $segment= $request->input('product_id');
        $product_details= Product::with('product_image')->where('id',$segment)->first();
        return view('front.popup.product_popup',compact('product_details'));
    }

    function delete_cart(Request $request)
    {
        $cart_id= $request->input('cart_id');
        $product_details= Cart::where('id',$cart_id)->delete();
        if($product_details)
        { 
         return Response::json(array(
                  'status' => 1,
                  'message' => 'Deleted Successfully',
                  'error_message'=>"Deleted Successfully",
              ), 200); 
        }
        else
        {
             return Response::json(array(
                  'status' => 0,
                  'message' => 'Please Try again',
                  'error_message'=>"Please Try again",
              ), 200); 
    
        } 
    } 
	
	function wishlist_add(Request $request)
    {
		if(Auth::check())
		{
			if(Auth::user()->role_id==3)
			{
        $product_id= $request->input('product_id');
        $user_id= Auth::user()->id;
		$data['user_id']= $user_id;
		$data['product_id']= $product_id;
		$obj= new Wishlist($data);
			if($obj->save())
			{ 
			   return Response::json(array(
					  'status' => 1,
					  'message' => 'Added to wishlist',
					  'error_message'=>"Added to wishlist",
				  ), 200); 
			}
			else
			{
				 return Response::json(array(
					  'status' => 0,
					  'message' => 'Please Try again',
					  'error_message'=>"Please Try again",
				  ), 200); 
		
			} 
			}
			else
			{
				return Response::json(array(
                  'status' => 2,
                  'message' => 'Please Try again',
                  'error_message'=>"Please Try again",
              ), 200); 
			}
		}
		else
		{
			return Response::json(array(
                  'status' => 2,
                  'message' => 'Please Try again',
                  'error_message'=>"Please Try again",
              ), 200); 
		}
    } 
}