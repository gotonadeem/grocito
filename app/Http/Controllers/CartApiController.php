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
use App\ProductItem;
use DB;
use URL;
use Helper;
use Excel;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class CartApiController extends Controller
{
	public function __construct()
	{
	  parent::__construct();

	}

	public function addToCart(Request $request)
	{
		try {
			$data['product_id'] = $request->product_id;
			$data['user_id'] = $request->user_id;
			$data['is_return'] = $request->is_return;
			$data['is_exchange'] = $request->is_exchange;
			$data['seller_id'] = $request->seller_id;//selected by user
			$product_data = Product::with('product_image')->where('id', $data['product_id'])->first();
			$item_data = ProductItem::where('id', $request->item_id)->first();
			$data['qty'] = $request->qty;
			$data['price'] = $item_data->price;
			$data['sprice'] = $item_data->sprice;
			$data['admin_commission'] = $product_data->commission;
			$data['gst_percentage'] = $product_data->p_gst;
			$data['weight'] = $item_data->weight;
			$data['item_id'] = $request->item_id;
			$data['product_name'] = $product_data->name;
			$data['product_image'] = $product_data->product_image[0]->image;
			$attrArray = array();
			$attrArray[] = array("color" => $request->input("color"));
			$attr = json_encode($attrArray);
			$data['attributes'] = $attr;

					$check_exists = Cart::select('id', 'qty')->where('user_id', $data['user_id'])->where('product_id', $data['product_id'])->where('item_id', $request->item_id)->first();
					if ($check_exists) {
						return Response::json(array(
							'status' => 1,
							'message' => 'Already In Cart.',
							'cart_count' => Cart::where('user_id', $data['user_id'])->get()->count(),
							'error_message' => "Already In Cart.",
						), 200);
					} else {
						$obj = new Cart($data);
						if ($obj->save($data)) {
							return Response::json(array(
								'status' => 1,
								'message' => 'Added to cart Successfully',
								'cart_count' => Cart::where('user_id', $data['user_id'])->get()->count(),
								'error_message' => "Added to cart Successfully",
								'item_name' => $item_data->weight,
								'product_name' => $product_data->name,
							), 200);
						} else {
							return Response::json(array(
								'status' => 0,
								'message' => 'Please Try again',
								'error_message' => "Please Try again",
							), 200);
						}
					}


		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function updateCartData(Request $request){
		try{
			$cart_id = $request->input('cart_id');
			$qty = $request->input('qty');
			$userId = $request->input('user_id');
			$itemId = $request->input('item_id');
			$cart_data= Cart::with('cart_product','cart_image')->where('user_id',$userId)->get();
			$check_exists= Cart::select('id','qty')->where('user_id',$userId)->where('id',$cart_id)->first();
			if($check_exists)
			{
				$cartQty=array();
				if($qty >= 0){
					$cartQty['qty']= $qty;
				}else{
					$cartQty['qty']= 0;
				}

				$cartObj= Cart::findOrFail($check_exists->id);

				$itemQty = ProductItem::where('id',$itemId)->first()->qty;
				if($itemQty >= $qty) {
					if ($cartObj->fill($cartQty)->save()) {
						return Response::json(array(
							'status' => 1,
							'message' => 'Cart Updated',
							'qty' => $qty,
							'available_qty' => $itemQty,
							'error_message' => "Cart Updated",
						), 200);
					} else {
						return Response::json(array(
							'status' => 0,
							'message' => 'Please Try Again',
							'error_message' => "Please Try Again",
						), 200);
					}
				}else{
					return Response::json(array(
						'status' => 0,
						'message' => 'Out of stock',
						'qty' => $qty,
						'available_qty' => $itemQty,
						'error_message' => "Out of stock",
					), 200);
				}

			}
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function getCartData(Request $request)
	{
		try {
			$userId = $request->user_id;
			if ($userId) {
				$cart_data = Cart::with('get_item','cart_product', 'cart_product.brand')->where('user_id', $userId)->get();
				return Response::json(array(
					'status' => 1,
					'message' => 'Cart Details',
					'data' => $cart_data,
					'cart_count' => Cart::where('user_id', $userId)->get()->count(),
					'error_message' => "Cart Details",
				), 200);
			} else {
				return Response::json(array(
					'status' => 0,
					'message' => 'Invalid user id',
					'error_message' => "Invalid user id",
				), 200);
			}
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	function cartCount(Request $request){
		try {

			$userId = $request->user_id;
			$count = Cart::where('user_id', $userId)->get()->count();
			$ref_id = User::where('id', $userId)->select('reff_code')->first();
			$refferSetting = Db::table('referral_settings')->first();
			if ($count) {
				return Response::json(array(
					'status' => 1,
					'message' => 'Successfully',
					'error_message' => "Successfully",
					'cart_count' => $count,
					'refferSetting' => $refferSetting,
					'ref_id' => $ref_id->reff_code,
				), 200);
			} else {
				return Response::json(array(
					'status' => 1,
					'message' => 'Successfully',
					'ref_id' => $ref_id->reff_code,
					'refferSetting' => $refferSetting,
					'error_message' => "Successfully",
					'cart_count' => 0,
				), 200);

			}
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	function deleteCart(Request $request)
	{
		try {
			$cart_id = $request->input('cart_id');
			$user_id = $request->input('user_id');
			$product_details = Cart::where('id', $cart_id)->delete();
			if ($product_details) {
				return Response::json(array(
					'status' => 1,
					'message' => 'Deleted Successfully',
					'error_message' => "Deleted Successfully",
					'cart_count' => Cart::where('user_id', $user_id)->get()->count(),
				), 200);
			} else {
				return Response::json(array(
					'status' => 0,
					'message' => 'Please Try again',
					'error_message' => "Please Try again",
				), 200);

			}
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	//all clear........
	function clearUserCart(Request $request)
	{
		try {
			$userId = $request->user_id;
			if (empty($userId)) {
				return Response::json(array(
					'status' => 0,
					'message' => 'User id required',
				), 200);
			}
			DB::table('carts')->where('user_id', $userId)->delete();
			return Response::json(array(
				'status' => 1,
				'message' => 'Succssfully clear',
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	//**************** END **********************
}
?>