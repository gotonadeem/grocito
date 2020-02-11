<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Order;
use App\Payment;
use App\ProductItem;
use App\Brand;
use Image;
use Helper;
use App\ProductNote;
use App\Product;
use App\Cart;
use App\Size;
use Validator;
use DateTime;
use DateInterval;
use DatePeriod;
use DB;
use Session;
use Response;
class CartController extends Controller
{
	protected $encrypt;
    public function __construct()
    {
		parent::__construct();
		$this->encrypt=md5($_SERVER['REMOTE_ADDR'].$_SERVER['SERVER_ADDR'].$_SERVER['SERVER_PORT'].$_SERVER['HTTP_USER_AGENT']);
    }
	//Add to cart.....
	function addToCart(Request $request)
	{
		$data['product_id']= $request->product_id;
		$data['is_return']= $request->is_return;
		$data['is_exchange']= $request->is_exchange;
		$data['seller_id']= $request->seller_id;//selected by user
		$product_data= Product::with('product_image')->where('id',$data['product_id'])->first();
		$item_data= ProductItem::where('id',$request->item_id)->first();
		//$data['seller_id']= $product_data->user_id;
		$data['qty']= $request->qty;
		//$data['size']= $request->size;
		$data['price']= $item_data->price;
		$data['sprice']= $item_data->sprice;
		$data['admin_commission']= $product_data->commission;
		$data['gst_percentage']= $product_data->p_gst;
		$data['weight']= $item_data->weight;
		$data['item_id']= $request->item_id;
		$data['product_name']= $product_data->name;
		$data['product_image']=$product_data->product_image[0]->image;
		$data['system_address']= $this->encrypt;
		$attrArray=array();
		$attrArray[]=array("color"=>$request->input("color"));
		$attr=json_encode($attrArray);
		$data['attributes']= $attr;
		
		$check_exists= Cart::select('id','qty')->where('system_address',$data['system_address'])->where('product_id',$data['product_id'])->where('item_id',$request->item_id)->first();
		//print_r($data);die;
		if($check_exists)
		{
				return Response::json(array(
					'status' => 2,
					'message' => 'Added to cart Successfully',
					'cart_count' => Cart::where('system_address',$data['system_address'])->get()->count(),
					'error_message'=>"Added to cart Successfully",
				), 200);
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
					'item_name' => $item_data->weight,
					'product_name' => $product_data->name,
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
	function getAjaxCart()
	{
		
		$system_address= $this->encrypt;
		$cart_data= Cart::with('cart_product','cart_product.brand')->where('system_address',$system_address)->get();
		return view('front.cart.product_details_cart',compact('cart_data'));

	}
	function cartCount(){
		$system_address= $this->encrypt;
		$count = Cart::where('system_address',$system_address)->get()->count();
		if($count)
		{
			return Response::json(array(
				'status' => 1,
				'message' => 'Successfully',
				'error_message'=>"Successfully",
				'cart_count' => $count,
			), 200);
		}
		else
		{
			return Response::json(array(
				'status' => 1,
				'message' => 'Successfully',
				'error_message'=>"Successfully",
				'cart_count' => 0,
			), 200);

		}
	}
	public function cartPlus(Request $request)
	{
		$cart_id=$request->cart_id;
		$qty=$request->qty;
		$itemId = $request->input('itemId');
		$system_address= $this->encrypt;
		$cart_data= Cart::with('cart_product','cart_image')->where('system_address',$system_address)->get();
		$check_exists= Cart::select('id','qty')->where('system_address',$system_address)->where('id',$cart_id)->first();
		if($check_exists)
		{
			$cartQty=array();
			//updated by nadeem.
			// $cartQty['qty']= $check_exists->qty+1;
			if($qty >= 0){
				$cartQty['qty']= $qty;
			}else{
				$cartQty['qty']= 0;
			}

			//To check item stock

			$itemQty = ProductItem::where('id',$itemId)->first()->qty;
			//echo $itemQty; echo $qty; die('dfdsdadad');
			if($itemQty >= $qty){
				$cartObj= Cart::findOrFail($check_exists->id);
				if($cartObj->fill($cartQty)->save())
				{
					echo json_encode(array('status'=>1,'message'=>'successfully'));
				}
				else
				{
					echo json_encode(array('status'=>0,'message'=>'successfully'));
				}

			}else{
				echo json_encode(array('status'=>2,'message'=>'successfully'));
			}

		}
	}
	public function cartMinus(Request $request)
	{

		$cart_id=$request->cart_id;
		$qty=$request->qty;
		$system_address= $this->encrypt;
		$check_exists= Cart::select('id','qty')->where('system_address',$system_address)->where('id',$cart_id)->first();
		if($check_exists)
		{
			$cartQty=array();
			$cartQty['qty']= $check_exists->qty-1;
			$cartObj= Cart::findOrFail($check_exists->id);
			if($cartObj->fill($cartQty)->save())
			{
				return Response::json(array(
					'status' => 1,
					'message' => 'Cart Updated',
					'error_message'=>"Cart Updated",
				), 200);
			}
			else
			{
				return Response::json(array(
					'status' => 0,
					'message' => 'Please Try Again',
					'error_message'=>"Please Try Again",
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
	function clearUserCart(Request $request)
	{
		$system_address= $this->encrypt;
		DB::table('carts')->where('system_address',$system_address)->delete();
			return Response::json(array(
				'status' => 1,
				'message' => 'Succssfully clear',
			), 200);
	}



	function updateCart(Request $request)
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

	function deleteCart(Request $request)
	{
		$cart_id= $request->input('cart_id');
		$product_details= Cart::where('id',$cart_id)->delete();
		$data['system_address']= $this->encrypt;
		if($product_details)
		{
			return Response::json(array(
				'status' => 1,
				'message' => 'Deleted Successfully',
				'error_message'=>"Deleted Successfully",
				'cart_count' => Cart::where('system_address',$data['system_address'])->get()->count(),
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

//END----------------------------------
}


