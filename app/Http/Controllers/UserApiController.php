<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\User;
use App\UserKyc;
use App\Enquiry;
use App\Cms;
use App\Reason;
use App\UserAddress;
use App\UserLocation;
use App\ReferralSetting;
use App\Category;
use App\SubCategory;
use App\Slider;
use App\Product;
use App\ProductImage;
use App\Pincode;
use App\DeliveryTime;
use App\SellerNotification;
use App\Cashback;
use App\Order;
use App\Warehouse;
use App\OrderMeta;
use App\Cart;
use App\ProductItem;
use App\Wallet;
use App\CallRequest;
use App\RaisingComplaint;
use App\Faq;
use App\OrderCancel;
use App\OrderRmaDetail;
use App\OrderExchange;
use App\ContactUs;
use App\UserNotification;
use App\AdminNotification;
use DB;
use URL;
use Excel;
use Helper;
use Auth;
use File;
use Keygen;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class UserApiController extends Controller
{
     public function __construct()
      {
	   parent::__construct(); 
      }
	//Step 1 for send otp to user
	public function user_reg_step_1(Request $request)
	{
		try {

			$mobile = $request->input('mobile');
			$userData = array(
				'mobile' => $request->input('mobile'),
				'f_name' => $request->input('f_name'),
				'l_name' => $request->input('l_name'),
				'city_name' => $request->input('city_name'),
				'email' => $request->input('email'),
			);
			$rules = array(
				'mobile' => 'required|unique:users,mobile',
				'f_name' => 'required|max:20|regex:/^[a-zA-Z .\']+$/',
				'l_name' => 'required|max:20|regex:/^[a-zA-Z .\']+$/',
				'email' => 'required|unique:users',
			);
			$validator = Validator::make($userData, $rules);
			if ($validator->fails()) {
				return Response::json(array(
					'fail' => true,
					'status_code' => 0,
					'error_message' => $validator->getMessageBag()->toArray()
				));
			} else {

				$reffCode = $request->input('reff_code');

				if (!empty($reffCode) and !$reffCode == "") {
					$ifRefExists = User::where('reff_code', $reffCode)->where('is_otp_varified', '1')->first();
					if (!$ifRefExists) {
						return Response::json(array(
							'status_code' => 0,
							'message' => 'Invalid Referral Code',
							'error_message' => "Invalid Referral Code",
						), 200);

					}

				}
				$userData['otp'] = rand(12, 66) . rand(67, 89);
				$mmsg = " Use " . $userData['otp'] . " as one time password(OTP) to verify your account.  Do not share this OTP to anyone for security reasons.";
				Helper::send_msg($mobile,$mmsg);
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Otp send successfully',
					'otp_code' => $userData['otp'],
					'error_message' => "saved successfully",
				), 200);
			}
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	//Step 2 for store data in db
	public function user_reg_step_2(Request $request)
	{
		try{
		$userData = array(
			'mobile' => $request->input('mobile'),
			'f_name' => $request->input('f_name'),
			'l_name' => $request->input('l_name'),
			'email' => $request->input('email'),
			'username' => $request->input('f_name') . " " . $request->input('l_name'),
			'city_name' =>$request->input('city_name'),
			'device_token' =>$request->input('device_token'),
			'role_id' => 3,
		);
		$rules = array(
			'f_name' => 'required|max:20|regex:/^[a-zA-Z .\']+$/',
			'l_name' => 'required|max:20|regex:/^[a-zA-Z .\']+$/',
			'email' => 'required|unique:users',
			'mobile' => 'required|unique:users,mobile',
		);
		$validator = Validator::make($userData, $rules);
		if ($validator->fails()) {
			return Response::json(array(
				'fail' => true,
				'status_code' => 0,
				'error_message' => $validator->getMessageBag()->toArray()
			));
		} else {

			$reffCode = $request->input('reff_code');
			$referrerId = 0;
			if(!empty($reffCode)){
				$ifRefExists = User::where('reff_code',$reffCode)->where('is_otp_varified','1')->first();
				if($ifRefExists){
					$userData['ref_by']= $ifRefExists->reff_code;
					$referrerId= $ifRefExists->id;
				}else{
					return Response::json(array(
						'status_code' => 0,
						'message' => 'Invalid Referral Code',
						'error_message'=>"Invalid Referral Code",
					), 200);
				}

			}
			/*$firstName = substr($request->input('f_name'), 0, 2);
			$userData['reff_code'] = $firstName . Helper::unique_code(4);*/
			$uid = Keygen::numeric(4)->generate();
			$userData['reff_code'] = strtoupper("GRO".rand(11,99).rand(99,11).$uid);
			$userData['is_otp_varified'] = '1';
			$obj = new User($userData);
			$obj->save();
			if ($obj->id) {
				$userData['user_id'] = $obj->id;
				$objKyc = new UserKyc($userData);
				$objKyc->save();

				/*if ($referrerId) {
					//update referrer amount wallet balance.....
					$referreWallet['user_id'] = $referrerId;
					$referreWallet['ref_id'] = $obj->id;
					$referreWallet['amount'] = ReferralSetting::first()->referrer_amount;
					$referreWallet['type'] = 'deposit';
					$referreWallet['payment_type'] = 'refer_and_earn';
					DB::table('wallets')->insert($referreWallet);
					//update referral amount wallet balance.....
					$referreWallet['user_id'] = $obj->id;
					$referreWallet['ref_id'] = $referrerId;
					$referreWallet['amount'] = ReferralSetting::first()->referral_amount;
					$referreWallet['type'] = 'deposit';
					$referreWallet['payment_type'] = 'refer_and_earn';
					DB::table('wallets')->insert($referreWallet);
				}*/
				$userData = User::with('user_kyc')->where('id',$obj->id)->get();
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Register successfully',
					'data' => $userData,
					'error_message'=>"Register successfully",
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
	public function resend_otp(Request $request)
	{
		try {
			$userData = array(
				'mobile' => $request->input('mobile'),
			);
			$rules = array(
				'mobile' => 'required',
			);
			$validator = Validator::make($userData, $rules);
			if ($validator->fails()) {
				return Response::json(array(
					'fail' => true,
					'status_code' => 0,
					'error_message' => $validator->getMessageBag()->toArray()
				));
			} else {
				$mobile = $request->mobile;
				$userData['otp'] = rand(12, 66) . rand(67, 89);
				$mmsg = " Use " . $userData['otp'] . " as one time password(OTP) to verify your account.  Do not share this OTP to anyone for security reasons.";
				Helper::send_msg($mobile, $mmsg);
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Otp send successfully',
					'otp_code' => $userData['otp'],
					'error_message' => "saved successfully",
				), 200);
			}
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}

	function login_api(Request $request)
	{
		try {
			$userData = array(
				'mobile' => $request->input('mobile'),
				'device_token' => $request->input('device_token'),
			);
			$rules = array(
				'mobile' => 'required',
			);
			$validator = Validator::make($userData, $rules);
			if ($validator->fails()) {
				return Response::json(array(
					'fail' => true,
					'status_code' => 0,
					'error_message' => $validator->getMessageBag()->toArray()
				));
			} else {
				$mobile = $request->input('mobile');
				$user = User::with('user_kyc')->where('mobile', $mobile)->where('role_id', '3')->get();
				$userDetails = User::with('user_kyc')->where('mobile', $mobile)->where('role_id', '3')->first();
				if (count($user)) {
					$userData['otp'] = rand(12, 66) . rand(67, 89);
					$mmsg = " Use " . $userData['otp'] . " as one time password(OTP) to verify your account.  Do not share this OTP to anyone for security reasons.";
					Helper::send_msg($mobile, $mmsg);
					DB::table("users")->where('id',$userDetails->id)->update(['device_token'=>$userData['device_token']]);
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Otp send successfully',
						'otp_code' => $userData['otp'],
						'data' => $user,
						'error_message' => "Otp send successfully",
					), 200);
				} else {
					return Response::json(array(
						'status_code' => 0,
						'message' => 'This Mobile No. is not registered with us',
						'otp_code' => '',
						'error_message' => "This Mobile No. is not registered with us",
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
	
	public function FacebookLogin(Request $request)
    {
		try {
       		    $user['device_token'] = $request->device_token;
                $user['email'] = $request->email;
                $user['social_id'] = $request->social_id;
                $user['image']=$request->image;
                $user['login_type'] = "facebook";
                $user['role_id'] = 3;
                $user_kyc['f_name'] =$request->name;
				$rules = array(
					'social_id' => 'required',
				);
			$validator = Validator::make($user, $rules);
			if ($validator->fails()) {
				return Response::json(array(
					'fail' => true,
					'status_code' => 0,
					'error_message' => $validator->getMessageBag()->toArray()
				));
			} else {
				 $existing_user = User::where('social_id', $user['social_id'])->first();
                if (count((array)$existing_user)>0) {
					$user = User::with('user_kyc')->where('social_id', $user['social_id'])->where('role_id', '3')->get();
				    $userDetails = User::with('user_kyc')->where('social_id', $user['social_id'])->where('role_id', '3')->first();
				    DB::table("users")->where('id',$userDetails->id)->update(['device_token'=>$user['device_token']]);
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Otp send successfully',
						'data' => $user,
						'error_message' => "Otp send successfully",
					), 200);
				}
				else
				{
					$object = new User($user);
                    $object->save();
					$user_kyc['user_id']=$object->id;
					$object2 = new UserKyc($user_kyc);
                    $object2->save();
                    Auth::login($object);
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Otp send successfully',
						'data' => $user,
						'error_message' => "Otp send successfully",
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
	
	//user profile
	public function updateProfile(Request $request)
	{
		try{
			$userId = $request->input('user_id');

			$userData = array(
				'f_name'      => $request->input('f_name'),
				'l_name'      => $request->input('l_name'),
				'email'       => $request->input('email'),
			);
			$rules = array(
				'f_name'      =>  'required|max:20|regex:/^[a-zA-Z .\']+$/',
				'l_name'     =>   'required|max:20|regex:/^[a-zA-Z .\']+$/',
				'email' => 'required|unique:users,email,'.$userId.',id'
			);
			$validator = Validator::make($userData,$rules);
			if($validator->fails()) {
				return Response::json(array(
					'fail' => true,
					'status_code' => 0,
					'error_message' => $validator->getMessageBag()->toArray()
				));
			}else {
				$checkUser =User::where('id',$userId)->first();
				if(!$checkUser){
					return Response::json(array(
						'status_code' => 0,
						'message' => 'Invalid user id',
					), 200);
				}
				$userProfile['email']= $request->input('email');
				$updateUser = User::find($userId)->fill($userProfile)->update();
				if($updateUser){
					$kycData['f_name'] = $request->input('f_name');
					$kycData['l_name'] = $request->input('l_name');
					$userKyc=UserKyc::where('user_id', '=',$userId)->first();
					$userKyc->update($kycData);
					return Response::json(array(
						'status_code' => 1,
						'message' => 'Update successfully',
					), 200);
				}
			}

		}catch (\Exception $e) {
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			), 200);
		}
	}
	//user profile
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
			$path_original=public_path() . '/front/user_profile';
			$file = $request->profile_image;
			$photo_name = time() . '-' . $file->getClientOriginalName();
			$file->move($path_original, $photo_name);
			$kycData['profile_image'] = $photo_name;
			$userKyc=UserKyc::where('user_id', '=',$userId)->first();
			$userKyc->update($kycData);

			return Response::json(array(
				'status_code' => 1,
				'image' => $photo_name,
				'message' => 'Update successfully',
			), 200);
		}catch (\Exception $e) {
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			), 200);
		}
	}
	//check pin availability..........................
	public function checkPinAvailability(Request $request)
	{
		try{
			$pincode = $request->input('pincode');
			$user_id = $request->input('user_id');
			if(!empty($pincode)){
				$data = \DB::table("users")
					->join('user_kyc', 'user_kyc.user_id', '=', 'users.id')
					->whereRaw("find_in_set($pincode,user_kyc.delivery_pincode)")
					->where('users.verify_status','verified')
					->get();
				if($data){
					//To get city and state by pincode.
					$cityStatedata = DB::table('pincodes')
						->join('cities', function ($join) use ($pincode) {
							$join->on('pincodes.city_id', '=', 'cities.id')
								->where('pincodes.pincode', '=', $pincode);
						})
						->join('states', 'cities.state_id', '=', 'states.id')
						->select('cities.name as city_name', 'states.name as state_name')
						->first();

					if($cityStatedata){
						$data = ['pincode'=>$pincode, 'city_name'=>$cityStatedata->city_name, 'state_name'=>$cityStatedata->state_name];
						DB::table('carts')->where('user_id',$user_id)->delete();
						return Response::json(array(
							'status_code' => 1,
							'data' => $data,
							'message' => 'Successfully',
						), 200);
					}else{
						return Response::json(array(
							'status_code' => 0,
							'message' => 'Sorry, city not found.',
						), 200);
					}
				}else{
					return Response::json(array(
						'status_code' => 0,
						'message' => 'Sorry, Delivery is not available at this pincode.',
					), 200);
				}
			}else{
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Invalid pincode',
				), 200);
			}
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			), 200);
		}
	}
	public function availableCityList(Request $request){
		try{
			$availableCity = \DB::table("cities")
				->join('products', 'products.city_id', '=', 'cities.id')
				->where('cities.status',1)
				->select('cities.*')
				->distinct('cities.id')->get();
			return Response::json(array(
				'status_code' => 1,
				'data'=>$availableCity,
				'icon_path'=>'public/admin/uploads/city_icon/',
				'message' => 'City List',
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			), 200);
		}
	}
	public function catList(Request $request)
	{
		try{
			$pincode = $request->pincode;
			if ($pincode) {
				//cat data
				$catData = \DB::table("user_kyc")
					->join('products', 'products.user_id', '=', 'user_kyc.user_id')
					->join('categories', 'categories.id', '=', 'products.category_id')
					->select('categories.*')
					->whereRaw("find_in_set($pincode,user_kyc.delivery_pincode)")
					->where('categories.status', 1)
					->distinct('categories.id')->get();

			} else {
				$catData = \DB::table("categories")
					->select('categories.*')
					->where('status', 1)
					->get();
			}
			//cat with subcat
			$catAndSubCatList = [];
			foreach ($catData as $ct) {
				$subCatData = Helper::get_sub_category($ct->id);
				$subCatList = [];
				foreach ($subCatData as $subCatVal) {
					$subCatList[] = ['subCatId' => $subCatVal['id'], 'subCatSlug' => $subCatVal['slug'], 'subCatImage' => $subCatVal['image'], 'subCatName' => $subCatVal['name']];
				}
				$catAndSubCatList[] = ['cat_id' => $ct->id, 'cat_slu' => $ct->slug, 'cat_name' => $ct->name, 'cat_icon' => $ct->app_icon, 'subCatList' => $subCatList];
			}
			$data = ['catAndSubCatList' => $catAndSubCatList];
			return Response::json(array(
				'status_code' => 1,
				'message' => 'successfully',
				'data' => $data,
				'error_message' => "successfully",
				'product_img_url' => url('/') . "/public/admin/uploads/product/",
				'cat_icon_url' => url('/') . "/public/admin/uploads/category_icon/",
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			), 200);
		}

	}
// User Home Api...
	public function home(Request $request)
	{
		try {
			$pincode = $request->pincode;
			$userData= User::where('id',$request->user_id)->first();
			$isSpecial = Category::with('main_category_special')->where('is_special', 1)->first();
			$slider_list = Slider::with('main_category','product')->where('status', 1)->where('type', 'slider')->get();
			$firstOfferBanner= Slider::with('main_category','product')->where('status',1)->where('type','offer_banner_first')->take(15)->latest()->get();
			$middleBanner = Slider::with('main_category','product')->where('status', 1)->where('type', 'middle')->first();
			$bottomBanner = Slider::with('main_category','product')->where('status', 1)->where('type', 'bottom')->first();

			//to get only 4 category.
			if ($pincode) {
				//cat data
				$catData = \DB::table("user_kyc")
					->join('products', 'products.user_id', '=', 'user_kyc.user_id')
					->join('categories', 'categories.id', '=', 'products.category_id')
					->select('categories.*')
					->whereRaw("find_in_set($pincode,user_kyc.delivery_pincode)")
					->where('categories.status', 1)
					->distinct('categories.id')->get();

				//cat with product
				$catWithProduct = \DB::table("user_kyc")
					->join('products', 'products.user_id', '=', 'user_kyc.user_id')
					->join('categories', 'categories.id', '=', 'products.category_id')
					->select('categories.id as cat_id', 'categories.name as cat_name', 'categories.slug as cat_slug')
					->whereRaw("find_in_set($pincode,user_kyc.delivery_pincode)")
					->where('categories.status', 1)
					//->orderBy('products.id','DESC')
					->distinct('categories.id')->get();
			} else {
				$catData = \DB::table("categories")
					->select('categories.*')
					->where('status', 1)
					->get();
				//cat with product
				$catWithProduct = \DB::table("categories")
					->select('categories.id as cat_id', 'categories.name as cat_name', 'categories.slug as cat_slug')
					->where('status', 1)
					->get();
			}
			//cat with subcat
			$catAndSubCatList = [];
			foreach ($catData as $ct) {
				$subCatData = Helper::get_sub_category($ct->id);
				$subCatList = [];
				foreach ($subCatData as $subCatVal) {
					$subCatList[] = ['subCatId' => $subCatVal['id'], 'subCatSlug' => $subCatVal['slug'], 'subCatName' => $subCatVal['name']];
				}
				$catAndSubCatList[] = ['cat_id' => $ct->id, 'cat_slu' => $ct->slug, 'cat_name' => $ct->name, 'subCatList' => $subCatList];
			}
			//For product listing
			$catWithPro = [];
			foreach ($catWithProduct as $cPro) {
				$cat_id = $cPro->cat_id;
				$cat_name = $cPro->cat_name;
				$cat_slug = $cPro->cat_slug;
				$productByCat = Helper::getApiProductByCat($cPro->cat_id, $pincode);
				$is_scheme = 0;
				$productListData = [];
				foreach ($productByCat as $productList) {
					$ifScheme = Helper::checkSchemeOnProduct($productList->id);
					if ($ifScheme) {
						$is_scheme = 1;
					}
					$p_slug = $productList->slug;
					$p_name = $productList->name;
					$p_image = $productList->image;

					$productListData[] = ['is_scheme' => $is_scheme, 'p_slug' => $p_slug, 'p_name' => $p_name, 'p_image' => $p_image];
				}
				$catWithPro[] = ['cat_id' => $cat_id, 'cat_name' => $cat_name, 'cat_slug' => $cat_slug, 'productListData' => $productListData];
			}
			//sunday bazar product
			$sundayProduct = Helper::getApiProductByType('is_sunday_bazar',$pincode);
			$is_grocito_exclusive = Helper::getApiProductByType('is_grocito_exclusive',$pincode);
			$newArrival = Helper::getApiProductByType('new',$pincode);
			if($userData->is_clear_notification_date !=null ){
				$userNotifyCount =UserNotification::where('status',1)->where('created_at','>',$userData->is_clear_notification_date)->count();
			}else{
				$userNotifyCount =UserNotification::where('status',1)->count();
			}

			$data = ['userNotifyCount'=>$userNotifyCount,'catAndSubCatList' => $catAndSubCatList, 'catData' => $catData, 'catWithProduct' => $catWithPro, 'isSpecial' => $isSpecial, 'sliderList' => $slider_list, 'middleBanner' => $middleBanner, 'bottomBanner' => $bottomBanner,'sundayProduct'=>$sundayProduct,'isGrocitoExclusive'=>$is_grocito_exclusive,'newArrival'=>$newArrival,'firstOfferBanner'=>$firstOfferBanner];
			return Response::json(array(
				'status_code' => 1,
				'message' => 'successfully',
				'data' => $data,
				'error_message' => "successfully",
				'product_img_url' => url('/') . "/public/admin/uploads/product/",
				'cat_img_url' => url('/') . "/public/admin/uploads/category/",
			), 200);
		}catch (\Exception $e){
				return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
				),200);
		}
	}

	public function product_listing(Request $request)
	{
		try {
			$catId = $request->cat_id;
			$subCatId = $request->sub_cat_id;
			$pincode = $request->pincode;
			//filter data
			$brandId=$request->input('brand_id');
			$min_price=$request->input('min_price');
			$max_price=$request->input('max_price');
			$min_offer=$request->input('min_offer');
			$max_offer=$request->input('max_offer');


			$catdata = Category::where('id', $catId)->first();
			$catProduct = Helper::getApiFilterProductByCat($catId, $pincode, $subCatId,$brandId,$min_price,$max_price,$min_offer,$max_offer);
			$productList=[];
			$allSeller=[];
			$sellerListArray=[];
			$getProductPriceData=[];
			$defaultSeller=[];
			foreach($catProduct as $pList){
				$getProductPriceData = Helper::getProductItemBySellerId($pList->id,$pList->user_id);
				if(!empty($getProductPriceData)){
					$ifSchemeProduct = Helper::getIfSchemeProduct($pList->id, $getProductPriceData[0]['id']);
				}
				if($ifSchemeProduct ){
					$productName = $ifSchemeProduct->offer_name;
					$imageUrl = 'public/admin/uploads/scheme_product/'.$ifSchemeProduct->image;
				}else{
					$productName = $pList->name;
					$imageUrl = 'public/admin/uploads/product/'.$pList->image;
				}
				$productSeller = Helper::getProductSellerName($pList->id);
				$defaultSeller = array(array('seller_id'=>$productSeller->user_name['id'],'name'=>$productSeller->user_name['username']));
				$sellerList = Helper::getDuplicateSeller($pList->id);
				foreach ($sellerList as $sList){
					$defaultSeller[] = array('seller_id'=>$sList->get_seller['id'],'name'=>$sList->get_seller['username']);
				}

				//$productList[] = ['defaultProductName'=>$productName,'defaultImage'=>$imageUrl,'pList'=>$pList,'productPriceData'=>$getProductPriceData,'sellerList'=>$defaultSeller];
				$productList[] = ['defaultProductName'=>$productName,'defaultImage'=>$imageUrl,'id'=>$pList->id,'brand_name'=>$pList->brand_name,'product_slug'=>$pList->slug,'offerprice'=>$getProductPriceData[0]['offer'],'price'=>$getProductPriceData[0]['price'],'sprice'=>$getProductPriceData[0]['sprice']];

			}
			$data = ['catdata' => $catdata, 'productList' => $productList];
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Product Listing',
				'data' => $data,
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage().'Line No:-'.$e->getLine(),
			),200);
		}
	}
	public function product_type_listing(Request $request)
	{
		try {
			$type = $request->type;
			$pincode = $request->pincode;
			//filter data
			$brandId=$request->input('brand_id');
			$min_price=$request->input('min_price');
			$max_price=$request->input('max_price');
			$min_offer=$request->input('min_offer');
			$max_offer=$request->input('max_offer');

			$catProduct = Helper::getApiProductListingByTypeWithFilter($type,$pincode,$brandId,$min_price,$max_price,$min_offer,$max_offer);

			$productList=[];
			$allSeller=[];
			$sellerListArray=[];
			$getProductPriceData=[];
			$defaultSeller=[];
			foreach($catProduct as $pList){
				$getProductPriceData = Helper::getProductItemBySellerId($pList->id,$pList->user_id);
				if(!empty($getProductPriceData)){
					$ifSchemeProduct = Helper::getIfSchemeProduct($pList->id, $getProductPriceData[0]['id']);
				}
				if($ifSchemeProduct ){
					$productName = $ifSchemeProduct->offer_name;
					$imageUrl = 'public/admin/uploads/scheme_product/'.$ifSchemeProduct->image;
				}else{
					$productName = $pList->name;
					$imageUrl = 'public/admin/uploads/product/'.$pList->image;
				}
				$productSeller = Helper::getProductSellerName($pList->id);
				$defaultSeller = array(array('seller_id'=>$productSeller->user_name->id,'name'=>$productSeller->user_name->username));
				$sellerList = Helper::getDuplicateSeller($pList->id);
				foreach ($sellerList as $sList){
					$defaultSeller[] = array('seller_id'=>$sList->get_seller['id'],'name'=>$sList->get_seller['username']);
				}

				//$productList[] = ['defaultProductName'=>$productName,'defaultImage'=>$imageUrl,'pList'=>$pList,'id'=>$pList->id,'brand_name'=>$pList->brand_name,'product_slug'=>$pList->product_slug,'productPriceData'=>$getProductPriceData,'sellerList'=>$defaultSeller];
				$productList[] = ['defaultProductName'=>$productName,'defaultImage'=>$imageUrl,'id'=>$pList->id,'brand_name'=>$pList->brand_name,'product_slug'=>$pList->slug,'offerprice'=>$getProductPriceData[0]['offer'],'price'=>$getProductPriceData[0]['price'],'sprice'=>$getProductPriceData[0]['sprice']];
			}
			$data = [ 'productList' => $productList];
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Product Type Listing',
				'data' => $data,
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	//Filter data cat and brand
	public function filterData(Request $request){
		try{
			$catId =$request->cat_id;
			$subCatId =$request->sub_cat_id;
			if(empty($catId)){
				return Response::json(array(
					'status_code' => 0,
					'message' => 'category id required',
				), 200);
			}
			$category_filter= SubCategory::where('category_id',$catId)->get();
			$brand_filter= Product::with('brand')->where('category_id',$catId);
			if($subCatId)
			{
				$brand_filter=$brand_filter->where('sub_category_id',$subCatId);
			}
			$brand_filter=$brand_filter->groupBy('brand_id')->get();
			$data =['subCatData'=>$category_filter,'filter_brand' =>$brand_filter];
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Filter data',
				'data' => $data,
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	//Filter data product type cat and brand...........
	public function filterDataProductType(Request $request){
		try{
			$type= $request->product_type;
			$sessionPincode =$request->pincode;
			$brand = \DB::table("user_kyc")
				->join('products', 'products.user_id', '=', 'user_kyc.user_id')
				->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
				->select('brands.*')
				->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
				->where('products.is_admin_approved',1)

				->groupBy('brands.id')->distinct('brands.id');
			if($type != 'new' ){
				$brand->where("products.$type",1);
			}
			$brand_filter = $brand->get();
			$data =['filter_brand' =>$brand_filter];
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Product type filter data',
				'data' => $data,
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function product_details(Request $request)
	{
		try {
			$productSlug = $request->product_slug;
			$pincode = $request->pincode;
			$relatedProductList = array();
			$relatedId1=array();
			$product_details = Product::with('user_name', 'product_image', 'main_category', 'brand')->where('slug', $productSlug)->first();
			$all_image = ProductImage::where('product_id', $product_details->id)->get();
			//check scheme on product.......
			$ifSchemeProduct = Helper::getIfSchemeProduct($product_details->id, $product_details->product_item[0]['id']);
			if ($ifSchemeProduct) {
				$productName = $ifSchemeProduct->offer_name;
				$productImage = 'public/admin/uploads/scheme_product/' . $ifSchemeProduct->image;
			} else {
				$productName = $product_details->name;
				$productImage = 'public/admin/uploads/product/' . $product_details->image;
			}
			$sellerName = Helper::getApiSellerName($product_details->user_id);
			$defaultSeller = array(array('seller_id'=>$sellerName->id,'name'=>$sellerName->username));
			$defaultSellerItem = Helper::getProductItemBySellerId($product_details->id, $product_details->user_id);
			$sellerList = Helper::getDuplicateSeller($product_details->id);
			foreach ($sellerList as $sList){
				$defaultSeller[] = array('seller_id'=>$sList->get_seller->id,'name'=>$sList->get_seller->username);
			}
			$sellerCount = count($sellerList) + 1;
			$productItem = Helper::getProductItemBySellerId($product_details->id, $product_details->user_id);
			if (!empty($product_details->related_product) and ($product_details->related_product != 'null')) {
				$relatedId1 = explode(',', $product_details->related_product);
				//echo "<pre>";
				//print_r($relatedId1);
				foreach($relatedId1 as $vs) {

					$relatedProduct = Helper::getApiRelatedProduct($vs, $pincode);
					if($relatedProduct) {
						$relatedProductList[] = ['slug' => $relatedProduct->slug, 'p_name' => $relatedProduct->name, 'image' => $relatedProduct->image];
					}
					}
			}

			$ratingData = Helper::getProductAllRating($product_details->id);
			$avgRating = Helper::get_rating($product_details->id);
			$data = ['product_name' => $productName, 'productImage' => $productImage, 'sellerCount' => $sellerCount, 'defaultSellerItem' => $defaultSellerItem, 'sellerList' => $defaultSeller, 'product_details' => $product_details, 'productItem' => $productItem, 'all_image' => $all_image, 'relatedProductList' =>$relatedProductList,'ratingData'=>$ratingData,'avgRating'=>$avgRating];
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Product Details',
				'data' => $data,
				'error_message' => "Product Details",
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
				'message' => $e->getLine(),
			),200);
		}

	}
	//to get seller product price
	public function getSellerProductItem(Request $request)
	{
		try{
			$sellerId = $request->input('seller_id');
			$productId = $request->input('product_id');
			$item = ProductItem::where('seller_id',$sellerId)->where('product_id',$productId)->get();
			foreach ($item as $vs){
				$data = ProductItem::with('scheme_product','product','product_image')->where('id',$vs->id)->first();

				if($data) {
					if ($data->scheme_product && $data->scheme_product->offer_name) {
						$schemeName = $data->scheme_product->offer_name;
					} else {
						$schemeName = $data->product->name;
					}
					if ($data->scheme_product && $data->scheme_product->image) {
						$productImagePath = 'public/admin/uploads/scheme_product/' . $data->scheme_product->image;
					} else {
						$productImagePath = 'public/admin/uploads/product/' . $data->product_image->image;
					}
				}
				$data = ['item_data'=>$item,'schemeName'=>$schemeName,'productImagePath'=>$productImagePath];
			}
			return Response::json(array(
				'status_code' => 1,
				'message' => 'seller item list',
				'data' => $data,
			), 200);
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}

	}
	public function submitReviewRating(Request $request){
		try{
			$data['user_id'] = $request->user_id;
			$data['rating'] = $request->rating;
			$data['message'] = $request->review_msg;
			$data['product_id'] = $request->product_id;
			$data['order_id'] = $request->order_id;
			DB::table('product_ratings')->insert($data);
			return Response::json(array(
				'status_code' => 1,
				'message' => 'submit successfully',
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function search(Request $request)
	{
		try{
			$search = $request->input('key_words');
			if($search){
				$pincode = $request->input('pincode');
				$catData = \DB::table("categories")
					->where('categories.name', 'like', '%'. $search . '%')
					->where('status',1)
					->get();
				if (count($catData) > 0) {
					foreach ($catData as $keys => $vs) {
						$listurl=URL::to('/category/' . $vs->id);
						$result[] = ['id'=>$vs->id,'value' => $vs->name,'url'=>$listurl, 'search_type' => 'category'];
					}
				}
				$data = \DB::table("user_kyc")
					->join('products', 'products.user_id', '=', 'user_kyc.user_id')
					->whereRaw("find_in_set($pincode, user_kyc.delivery_pincode)")
					->where('products.name', 'like', '%'. $search . '%')
					->where('products.is_admin_approved',1)
					->distinct('products.id')->get();
				if (count($data) > 0) {
					foreach ($data as $key => $v) {
						$url=URL::to('/product/' . $v->slug);
						$result[] = ['id'=>$v->slug,'value' => $v->name,'url'=>$url, 'search_type' => 'product'];
					}
				}
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Search success',
					'data' => $result,
				), 200);
			}
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}

	}
//...................... Delivery Address ................................................
	public function addUserAddress(Request $request){
		try {
			$userAddress = array(
				'name' => $request->input('name'),
				'street' => $request->input('street'),
				'house' => $request->input('house'),
				'user_id' => $request->input('user_id'),
				'address' => $request->input('address'),
				'lattitude' => $request->input('lattitude'),
				'longitude' => $request->input('longitude'),
				'city' => $request->input('city'),
				'state' => $request->input('state'),
				'pincode' => $request->input('pincode'),
				'type' => $request->input('type'),
				'mobile' => $request->input('mobile'),
			);
			$objAddress = new UserAddress($userAddress);
			$objAddress->save();
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Add successfully',
				'error_message' => "Product Details",
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}

	public function getUserAddress(Request $request){
		try {
			$address_list = UserAddress::where('pincode', $request->input('pincode'))->where('user_id', $request->input('user_id'))->get();

			if (count($address_list)) {
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Address list',
					'data' => $address_list,
					'error_message' => "Product Details",
				), 200);
			} else {
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Record not found',
					'data' => [],
					'error_message' => "Record not found",
				), 200);
			}
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}

	public function updateUserAddress(Request $request){
		try {
			$userData = array(
				'name' => $request->input('name'),
				'street' => $request->input('street'),
				'house' => $request->input('house'),
				'address' => $request->input('address'),
				'type' => $request->input('type'),
				'mobile' => $request->input('mobile'),
			);
			DB::table('user_addresses')->where('id', $request->address_id)->update($userData);
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Update successfully',
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}

	public function deleteUserAddress(Request $request){
		try {
			$id = $request->address_id;
			$data = DB::table('user_addresses')->where('id', $id)->delete();
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Delete successfully',
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
//Checkout page
	public function userCheckout(Request $request)
	{
		try {
			$pincode = $request->input('pincode');
			if(empty($pincode)){
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Pincode is required',
				),200);
			}
			$userId = $request->input('user_id');
			$address_list = UserAddress::where('pincode', $pincode)->where('user_id', $userId)->get();
			$pincodeData = Pincode::where('pincode', $pincode)->first();
			$deliveryTime = DeliveryTime::where('city_id', $pincodeData->city_id)->first();
			$timeInterval = $deliveryTime ? $deliveryTime->time_interval * 60 : 120;//default value 2 hrs.
			$startTime = $deliveryTime ? $deliveryTime->start_time : '10:00AM'; //defauly value 10:00AM.
			$endTime = $deliveryTime ? $deliveryTime->end_time : '08:00PM'; //default value 08:00PM.
			$expressTime = $deliveryTime ? $deliveryTime->express_time : '45'; //default value 45 minute.
			//$expressString = "Order will be delivered within $expressTime minutes between $startTime to $endTime";
			$expressString = "Order will be delivered within $expressTime minutes";
			$timeslot = Helper::getServiceScheduleSlots($timeInterval, $startTime, $endTime);
			$currentTime= strtotime(date('h:i A'));
			$strEndTime = strtotime($endTime);
			if($strEndTime > $currentTime){
			    $expressStatus = 1;
			}else{
			    $expressStatus = 0;
			}
			$dateTimeSlot = [];
			foreach ($timeslot as $ks => $vs) {
				$dateTimeSlot[] = ['start_time' => $vs['start'], 'end_time' => $vs['end'],'second'=>strtotime($vs['start'])];
			}
			$wallet_amount = Helper::get_wallet($userId);
			$data = ['addressList' => $address_list, 'dateTimeSlot' => $dateTimeSlot,'expressTime'=>$expressTime, 'expressTimeString' => $expressString, 'walletAmount' => $wallet_amount];
			return Response::json(array(
				'status_code' => 1,
				'data' => $data,
				'message' => 'Checkout details',
				'expressStatus'=>$expressStatus
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function getDeliveryAmount(Request $request)
	{
		try {
			$id = $request->input('address_id');
			$delivery_type = $request->input('delivery_type');
			$amount = $request->input('amount');
			$delivery_type = (($delivery_type != "") ? $delivery_type : 'standard');
			$data = UserAddress::where('id', $id)->first();
			$pincode = $data->pincode;
			$check = DB::table("warehouses")->select("lattitude", 'longitude')->whereRaw("find_in_set($pincode,pincode)")->first();
			$delivery_charge = DB::table("delivery_charges")->where('type', $delivery_type)->first();
			//print_r($check);die;
			$lattitude = $check->lattitude?$check->lattitude:'26.9058312';
			$longitude = $check->longitude?$check->longitude:'75.7354066';
			$distance = round($this->distance($lattitude, $longitude, $data->lattitude, $data->longitude, "K"), 2);
			if($amount<=$delivery_charge->min_order)
			{
				if ($distance <= $delivery_charge->radius) {
					$delivery_charge = $delivery_charge->radius_charge;
				} elseif ($distance > $delivery_charge->radius) {
					$delivery_charge = $distance * $delivery_charge->out_of_radius_charge;
				}
			}
			else
			{
				$delivery_charge=0;
			}
			return Response::json(array(
				'status_code' => 1,
				'delivery_charge' => $delivery_charge,
				'message' => "deliver Here",
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function placeOrder(Request $request)
	{
		try {
			$my_wallet_amount = 0;
			$wallet_withdraw = 0;
			$userId = $request->input('user_id');
			$orders['address_id'] = $request->input('address_id');
			$pincode = $request->input('pincode');
			//to get warehouses id by pincode.
			$warehouses = \DB::table("warehouses")
				->whereRaw("find_in_set($pincode,pincode)")
				->first();
			if ($warehouses) {
				$warehousesId = $warehouses->id;
			} else {
				$warehousesId = 0;
			}
			DB::beginTransaction();
			try {
				$orderData = Cart::with('cart_product')->where('user_id', $userId)->get();
				$sellerData = Cart::where('user_id', $userId)->first();
				$orderCountData = Cart::with('cart_product')->where('user_id', $userId)->get()->count();
				if ($orderCountData > 0) {
					$orders['user_id'] = $userId;
					$orders['net_amount'] = $request->net_amount;
					$orders['sgst_amount'] = $request->sgst_amount;
					$orders['payment_mode'] = $request->payment_mode;
					$orders['delivery_date'] = $request->delivery_date;
					$orders['delivery_time'] = $request->delivery_time;
					$orders['delivery_type'] = $request->delivery_type;
					$orders['express_time'] = $request->express_time;
					$orders['wallet_amount'] = $request->withdraw_wallet_amount;
					$orders['status'] = 'incomplete';
					$orders['payment_status'] = 'faild';
					$orders['shipping_charge'] = $request->delivery_charge;
					$order = new Order($orders);
					$order->save();
					$order_number = "#" . $this->getNextOrderNumber();
					$ord_payment_id = uniqid() . $order->id;
					DB::table('orders')->where('id', $order->id)->update(['order_id' => $order_number, 'ord_payment_id' => $ord_payment_id]);
					$use_wallet = 0;
					$orderArray = array();
					$payment_amount = 0;
					$sum = 0;
					$ssum = 0;
					$commission = 0;
					$admin_amount = 0;
					$admin_comm = 0;
					foreach ($orderData as $vs):
						$gstCal = $vs->gst_percentage + 100;
						$netAmount = round((((($vs->sprice>0)?$vs->sprice:$vs->price)*$vs->qty) *100) / $gstCal,2);

						$ordData = array(
							'order_id' => $order->id,
							'seller_id' => $vs->seller_id,
							'product_id' => $vs->product_id,
							'price' => (($vs->sprice > 0) ? $vs->sprice : $vs->price),
							'qty' => $vs->qty,
							'weight' => $vs->weight,
							'item_id' => $vs->item_id,
							'product_image' => $vs->product_image,
							'product_name' => $vs->product_name,
							'product_commission' => ($vs->sprice * $vs->admin_commission) / 100,
							'is_return' => $vs->is_return,
							'is_exchange' => $vs->is_exchange,
							'attributes' => $vs->attributes,
							'sub_order_id'=>$this->getNextSubOrderNumber(),
							'net_amount'=>$netAmount

						);

						//$sell_price=$vs->cart_product->sell_price;
						$sell_price = $vs->sprice;
						$sum = $sum + $sell_price * $vs->qty;
						$commission = $commission + ($vs->sprice * $vs->admin_commission) / 100;
						$s = $vs->price;
						$ssum = $ssum + $s * $vs->qty;
						$SellerId = $vs->seller_id;
						$orderArray[] = $ordData;
						//get item qty for stock
						$itemData = ProductItem::where('id', $vs->item_id)->first();
						$itemQty = $itemData->qty;

						//update stock qty in item table.....
						if ($itemQty >= $vs->qty) {
							$updateQtyData['qty'] = $itemQty - $vs->qty;
							DB::table('product_items')->where('id', $vs->item_id)->update($updateQtyData);
						}
					endforeach;

					OrderMeta::insert($orderArray);
					DB::commit();
					DB::table('order_metas')->where('order_id', $order->id)->update(['status' => 'pending']);
					$orders['payment_amount'] = $payment_amount;
					$orders['total_amount'] = $sum;
					$orders['admin_commission'] = $commission;
					$orders['seller_id'] = $SellerId;
					$orders['warehouse_id'] = $warehousesId;
					DB::table('orders')->where('id', $order->id)->update($orders);
					//update user wallet
					if ($request->withdraw_wallet_amount > 0) {
						$walletDatap['amount'] = $request->withdraw_wallet_amount;
						$walletDatap['type'] = 'withdraw';
						$walletDatap['payment_type'] = 'placed_order';
						$walletDatap['user_id'] = $userId;
						DB::table('wallets')->insert($walletDatap);
					}
					//add welcome cashback to user wallet if user placed first order
					$userOrderCount = Order::where('user_id', $userId)->count();
					if ($userOrderCount < 2) {
						$cashbackSetting = Cashback::first();
						if ($sum >= $cashbackSetting->welcome_min_order_value) {
							if ($cashbackSetting->welcome_cashback_per > 0) {
								$cashbackAmount = ($sum * $cashbackSetting->welcome_cashback_per) / 100;
								if ($cashbackAmount > $cashbackSetting->upto_cashback) {
									$cashbackAmount = $cashbackSetting->upto_cashback;
								}
								$walletDatap['amount'] = $cashbackAmount;
								$walletDatap['type'] = 'deposit';
								$walletDatap['payment_type'] = 'first_order_cashback';
								$walletDatap['user_id'] = $userId;
								//DB::table('wallets')->insert($walletDatap);
							}
						}


					}
					//update order sataus....
					Helper::updateOrderStatus($order->id, 'pending', 'order placed');
					//add seller notification..........
					$notifyObj = new SellerNotification;
					$notifyObj->seller_id = $SellerId;
					$notifyObj->int_val = $order->id;//order id
					$notifyObj->type = 'order_placed';
					$notifyObj->message = 'New Order Placed';
					$notifyObj->save();

				}
			} catch (\Exception $e) {
				DB::rollBack();
				return Response::json(array(
					'status_code' => 0,
					'message' => $e->getMessage(),

				), 200);
			}
			$data = ['order_id' => $order->id];
			return Response::json(array(
				'status_code' => 1,
				'data' => Order::where('id',$order->id)->first(),
				'message' => "Order place successfully",
			), 200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function codSuccess(Request $request)
	{
		try {
			$user_id = $request->input('user_id');
			$order_id = $request->input('order_id');
			DB::table('orders')->where('id', $order_id)->update(['payment_status' => 'cod', 'status' => 'pending']);
			$order_details = Order::where('id', $order_id)->select('order_id')->first();
			$cartCount = Cart::where('user_id', $user_id)->get()->count();
			if ($cartCount > 0) {
				//send sms....
				$order_number = $order_details->order_id;
				$usersInfo = User::where('id', $user_id)->first();
				$mmsg = "Hi " . $usersInfo['username'] . ", \n thanks for placing your order with Grocito. \n";
				$mmsg .= "Here is your order number $order_number. \n";
				$mmsg .= " It will be dispatched soon. \n";
				$mmsg .= "\n\n Thanks Grocito";
				Helper::send_msg($usersInfo['mobile'], $mmsg);
				//send sms to seller
				$orderData = Cart::with('cart_product')->where('user_id', $user_id)->groupBy('seller_id')->get();
				foreach ($orderData as $meta){
					$order_number = $order_details->order_id;
					$sellerInfo = User::where('id', $meta->seller_id)->first();
					$mmsg = "Congratulations! " . $sellerInfo['username'] . ", \n you have received new order. \n";
					$mmsg .= "Order id: $order_number. \n";
					$mmsg .= "\n\n Thanks Grocito";
					Helper::send_msg($sellerInfo['mobile'], $mmsg);

				}

				//send mail....
				$msg = "Hi " . $usersInfo['email'] . ", <br><br>  Thanx for placing order on Grocito<br><br>";
				$msg .= "Here is your order number $order_number. \n";
				$msg .= "It will be dispatched soon. \n";
				$msg .= "\n\n Thanks Grocito";

				$emailData = array(
                    'to'        => array(strtolower($usersInfo['email'])),
                    'from'      => 'support@Grocito.com',
                    'subject'   => 'Order Successful',
                    'view'      => 'email.order-email',
                    'content'=>$msg
                );
                Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                        ->to($emailData['to'])
                        ->from($emailData['from'])
                        ->subject($emailData['subject']);

                });

				$cartRemove = Cart::where('user_id', $user_id);
				$cartRemove->delete();
				return Response::json(array(
					'status_code' => 1,
					'message' => "order success",
				), 200);
			} else {
				return Response::json(array(
					'status_code' => 0,
					'message' => "Invalid data",
				), 200);
			}
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function userRazorpaySuccess(Request $request){
		try{
			$order_id= $request->input('order_id');
			$user_id = $request->input('user_id');
			if($order_id) {
				DB::table('orders')->where('id', $order_id)->update(['payment_status' => 'success', 'status' => 'pending']);
				$order_details = Order::where('id', $order_id)->select('order_id')->first();
				$system_address = $this->encrypt;
				$cartCount = Cart::where('user_id', $user_id)->get()->count();
				if ($cartCount > 0) {

					//send sms....
					$order_number = $order_details->order_id;
					$usersInfo = User::where('id', $user_id)->first();
					$mmsg = "Hi " . $usersInfo['username'] . ", \n thanks for placing your order with Grocito. \n";
					$mmsg .= "Here is your order number $order_number. \n";
					$mmsg .= " It will be dispatched soon. \n";
					$mmsg .= "\n\n Thanks Grocito";
					Helper::send_msg(Session::get('mobile'), $mmsg);
						//send sms to seller
					$orderData = Cart::with('cart_product')->where('user_id', $user_id)->groupBy('seller_id')->get();
					foreach ($orderData as $meta){
						$order_number = $order_details->order_id;
						$sellerInfo = User::where('id', $meta->seller_id)->first();
						$mmsg = "Congratulations! " . $sellerInfo['username'] . ", \n you have receved new order. \n";
						$mmsg .= "Order id: $order_number. \n";
						$mmsg .= "\n\n Thanks Grocito";
						Helper::send_msg($sellerInfo['mobile'], $mmsg);

					}
					//send mail....
					$msg = "Hi " . $usersInfo['username'] . ", <br><br>  Thanx for placing order on Grocito<br><br>";
					$msg .= "Here is your order number $order_number. \n";
					$msg .= "It will be dispatched soon. \n";
					$msg .= "\n\n Thanks Grocito";

					$emailData = array(
                        'to'        => array(strtolower($usersInfo['email'])),
                        'from'      => 'support@Grocito.com',
                        'subject'   => 'Order Successful',
                        'view'      => 'email.order-email',
                        'content'=>$msg
                    );
                    Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                        $message
                            ->to($emailData['to'])
                            ->from($emailData['from'])
                            ->subject($emailData['subject']);

                    });

					$cartRemove = Cart::where('user_id', $user_id);
					$cartRemove->delete();
					return Response::json(array(
						'status_code' => 1,
						'order_number' =>$order_number,
						'message' => "order success",
					), 200);
				} else {

					return Response::json(array(
						'status_code' => 0,
						'message' => "Invalid Cart",
					), 200);
				}

			}else{
				return Response::json(array(
					'status_code' => 0,
					'message' => "Invalid Order Id",
				), 200);
			}
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function userRazorpayFaild(Request $request)
	{
		try{
			$order_id= $request->input('order_id');
			$user_id = $request->input('user_id');
			if($order_id) {

				DB::table('orders')->where('id', $order_id)->update(['payment_status' => 'faild', 'status' => 'incomplete']);
				$order_details = Order::where('id', $order_id)->select('order_id')->first();
				DB::table('order_metas')->where('order_id',$order_details->id)->update(['status'=>'incomplete']);
				$cartRemove = Cart::where('user_id', $user_id);
				$cartRemove->delete();
				//send sms....
				$order_number = $order_details->order_id;
				$usersInfo = User::where('id', $user_id)->first();
				$mmsg = "Hi " . $usersInfo['username'] . ", \n thanks for placing your order with Grocito. \n";
				$mmsg .= "Here is your order number $order_number. \n";
				$mmsg .= " It will be dispatched soon. \n";
				$mmsg .= "\n\n Thanks Grocito";
				Helper::send_msg(Session::get('mobile'), $mmsg);
				return Response::json(array(
					'status_code' => 1,
					'order_number' =>$order_number,
					'message' => "order faild",
				), 200);
			}else{
				return Response::json(array(
					'status_code' => 0,
					'message' => "Invalid Order Id",
				), 200);
			}

		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	//user placed order list
	public function getOrderList(Request $request)
	{
		try{
			$userId = $request->input('user_id');
			$data= Order::with('order_meta_data')->where('user_id',$userId)->orderBy('id', 'desc')->get();
			$orderData = [];
			foreach ($data as $or){
				$order_meta= OrderMeta::with('product','product.product_rating')->where('order_id',$or->id)->where('parent_id','0')->get();
				$sum= 0;
				$item_acount=0;
				$return_acount=0;
				$itemArray=array();
				foreach($order_meta as $vs)
				{
					$item_acount++;
					if($vs->return_status==1)
					{
						$return_acount++;
						$sum= $sum+($vs->price*$vs->qty);
					}
				}
				$orderData[]=['id'=>$or->id,'user_id'=>$or->user_id,'warehouse_id'=>$or->warehouse_id,'seller_id'=>$or->seller_id,'total_amount'=>$or->total_amount,'order_id'=>$or->order_id,'created_at'=>date('d-m-Y H:i:s', strtotime($or->created_at)),'status'=>$or->status,'shipped_date'=>$or->shipped_date,'shipping_charge'=>$or->shipping_charge,'total_return_amount'=>(($sum>0)?sprintf("%.2f",$sum):0),'item_count'=>$item_acount,'return_count'=>$return_acount ];
			}

				return Response::json(array(
					'status_code' => 1,
					'message' => 'order list',
					'data' =>$orderData,
				),200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function getOrderDetails(Request $request){
		try{
			$orderId = $request->input('order_id');
			$userId = $request->input('user_id');
			$seller_id = $request->input('seller_id');
			$data= Order::with('order_meta_data','address')->where('id',$orderId)->first();
			$userOtp =DB::table('delivery_boy_notifications')->select('delivery_code','date')->where('type','warehouse_to_customer')->whereIn('status',array('requested','accepted','delivered'))->where('order_id',$data->id)->first();
			$checkcon = DB::table('delivery_boy_rides')->select('date')->where('type','warehouse_to_customer')->where('order_id',$data->id)->where('seller_id',$seller_id)->first();
			//$checkNotify =DB::table('delivery_boy_notifications')->select('date')->whereIn('type',['warehouse_to_customer','seller_to_warehouse'])->where('order_id',$data->id)->where('seller_id',$seller_id)->first();
			
			$order_meta= OrderMeta::with('product','product.product_rating')

				->where('order_id',$orderId)->where('parent_id','0')
				/*->orWhereHas('product.product_rating', function($q) use($userId,$orderId){
					$q->where('user_id', $userId);
					$q->where('order_id', $orderId);
				})*/
				->get();
				if(count((array)$userOtp)>0)
				{
					$code=$userOtp->delivery_code;
				}
				else
				{
					$code=null;
				}
				
				 $sum= 0;
				 $item_acount=0;
				 $return_acount=0;
				 $itemArray=array();
				 foreach($order_meta as $vs)
				 {
					 $item_acount++;
					 if($vs->return_status==1)
					 {
						 $return_acount++;
					   $sum= $sum+($vs->price*$vs->qty);
					 } 
				 }
				 
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Order details',
				'data' =>$data,
				'return_reason' =>Reason::where('type','return')->get(),
				'exchange_reason' =>Reason::where('type','exchange')->get(),
				'date' =>((count((array)$checkcon)>0)?$checkcon->date:""),
				'order_otp' =>$code,
				'meta_data' =>$order_meta,
				'total_return_amount' =>(($sum>0)?sprintf("%.2f",$sum):0),
				'item_count' =>$item_acount,
				'return_count' =>$return_acount,
			),200);

		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
				'message1' => $e->getLine(),
			),200);
		}
	}
	public function userOrderCancle(Request $request){
		try{
			$orderId = $request->order_id;
			$orderData['reason'] = $request->reason;
			if($orderId && $orderData['reason']){
				$orderData['status'] = 'cancelled';
				$orderMetaData['status'] = 'cancelled';
				DB::table('orders')->where('id',$orderId)->update($orderData);
				DB::table('order_metas')->where('order_id',$orderId)->update($orderMetaData);
				Helper::updateOrderStatus($orderId, 'cancelled', $orderData['reason']);
				//update item qty if order cancel.....
				$getOrderItem=OrderMeta::where('order_id',$orderId)->get();
				foreach ($getOrderItem as $meta){
					$itemId = $meta['item_id'];
					$itemCancelQty = $meta['qty'];
					$getItemQty = DB::table('product_items')->where('id',$itemId)->first();
					$remainingQty = $getItemQty->qty;
					$updateQty = $itemCancelQty + $remainingQty;
					$updateItemQty =DB::table('product_items')->where('id',$itemId)->update(['qty' => $updateQty]);
				}
				//add wallet amount
				$orderData = Order::where('id',$orderId)->first();
				if($orderData->wallet_amount > 0){
					DB::table('wallets')->insert(['user_id'=>$orderData->user_id,'type'=>'deposit','amount'=>$orderData->wallet_amount,'reason'=>"Order Cancelled",'payment_type'=>'add_balance']);
				}
				//add notification for admin ..........
				$adminnotifyObj = new AdminNotification;
				$adminnotifyObj->int_val = $orderId;//order id
				$adminnotifyObj->type = 'order_cancel';
				$adminnotifyObj->message = 'Order Cancel';
				$adminnotifyObj->save();
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Order cancelled successfully.',
				),200);
			}else{
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Reason and order id required.',
				),200);
			}


		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	//return order........
	public function userReturnOrder(Request $request)
	{

		try{
			$orderId = $request->order_id;
			$order_id=$request->order_id;
			$metaId = $request->meta_id;
			$meta_id=$metaId;
			$reason = $request->reason;
			$check= OrderCancel::where('order_id',$orderId)->where('order_meta_id',$metaId)->get()->count();//order rma table.
			if(!$check>0)
			{
				$data['order_id']= $orderId;
				$data['order_meta_id']= $metaId;
				$data['reason']= $reason;
				$obj= new OrderCancel($data);
				if($obj->save())
				{
					$detailsData['order_id']=$orderId;
					$detailsData['order_meta_id']=$metaId;
					$detailsData['order_rma_id']=$obj->id;
					$detailsData['is_approved']=0;
					$rma= new OrderRmaDetail($detailsData);
					$rma->save();
					
					//update wallet
					$order_info= OrderMeta::with('order')->where('id',$metaId)->first();
					if($order_info){
						 DB::table('order_rma_details')->where('order_id',$order_id)->where('order_meta_id',$meta_id)->update(['is_approved'=>1,'approved_date'=>date('Y-m-d'),'shipping_charge'=>$order_info->order->shipping_charge]);
						 DB::table('order_metas')->where('id',$meta_id)->update(['status'=>'return']);
						//if payment mode online..
						if($order_info->order->payment_mode =='razorpay'){
							DB::table('wallets')->insert(['user_id'=>$order_info->order->user_id,'amount'=>($order_info->price * $order_info->qty),'type'=>'deposit','payment_type'=>'return_order']);
						}elseif($order_info->wallet_amount > 0 and $order_info->order->payment_mode =='cod'){
							DB::table('wallets')->insert(['user_id'=>$order_info->order->user_id,'amount'=>($order_info->wallet_amount),'type'=>'deposit','payment_type'=>'return_order']);
						}
						 DB::table('seller_penalties')->insert(['order_id'=>$order_id,'order_meta_id'=>$meta_id,'seller_id'=>$order_info->order->seller_id,'amount'=>(($order_info->price * $order_info->qty)+$order_info->order->shipping_charge),'type'=>'return']);
						//update product qty.....
						$itemId = $order_info->item_id;
						$itemCancelQty = $order_info->qty;
						$getItemQty = DB::table('product_items')->where('id',$itemId)->first();
						$remainingQty = $getItemQty->qty;
						$updateQty = $itemCancelQty + $remainingQty;
						$updateItemQty =DB::table('product_items')->where('id',$itemId)->update(['qty' => $updateQty]);
					}

					DB::table("order_metas")->where('id',$metaId)->update(['return_status'=>1]);
					return Response::json(array(
						'status_code' => 1,
						'message' => 'successfully saved',
					), 200);
				}
				else
				{
					return Response::json(array(
						'status_code' => 0,
						'message' => 'Please Try Again',
					), 200);
				}
			}else{
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Already return',
				), 200);
			}
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function userExchangeOrder(Request $request)
	{
		try{
			$orderId = $request->order_id;
			$metaId = $request->meta_id;
			$reason = $request->reason;
			$check= OrderCancel::where('order_id',$orderId)->where('order_meta_id',$metaId)->get()->count();//order rma table.
			if(!$check>0)
			{
				$data['order_id']= $orderId;
				$data['order_meta_id']= $metaId;
				$data['reason']= $reason;
				$obj= new OrderCancel($data);
				if($obj->save())
				{
					$detailsData['order_id']=$orderId;
					$detailsData['order_meta_id']=$metaId;
					$detailsData['order_rma_id']=$obj->id;
					$detailsData['is_approved']=0;
					$exchange= new OrderExchange($detailsData);
					$exchange->save();

					DB::table("order_metas")->where('id',$data['order_meta_id'])->update(['exchange_status'=>1]);
					return Response::json(array(
						'status_code' => 1,
						'message' => 'successfully',
					), 200);
				}
				else
				{
					return Response::json(array(
						'status_code' => 0,
						'message' => 'Please Try Again',
					), 200);
				}
			}else{
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Already exchange',
				), 200);
			}

		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function userOrderTracking(Request $request)
	{
		try{
			$orderId = $request->input('order_id');
			if($orderId){
				$trackingData = Helper::getOrderTrackingStatusForApp($orderId);
				return Response::json(array(
					'status_code'=>1,
					'data'=>$trackingData,
					'message'=>'success',
				),200);

			}else{
				return Response::json(array(
					'status_code' => 0,
					'mesaage' => 'Invalid order id',
				),200);
			}
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function userContactUs(Request $request)
	{
		try{
			$userData = array(
				'name' => $request->input('name'),
				'email' => $request->input('email'),
				'mobile' => $request->input('mobile'),
				'message' => $request->input('message')

			);
			$rules = array(
				'name' => 'required',
				'email' => 'required',
				'mobile' => 'required',
				'message' => 'required'
			);
			$validator = Validator::make($userData, $rules);
			if ($validator->fails()) {
				return Response::json(array(
					'fail' => true,
					'status_code' => 0,
					'error_message' => $validator->getMessageBag()->toArray()
				));
			} else {
				ContactUs::create($userData);
				return Response::json(array(
					'status_code'=>1,
					'message'=>'Your request has been submitted successfully'
				),200);
			}
		}	catch (\Exception $e){
			return Response::json(array(
				'status_code'=>0,
				'mesaage'=> $e->getMessage(),
			),200);
		}
	}
	//user notification list
	public function getUserNotification(Request $request){
		try{
			$userId = $request->user_id;
			$userData= User::where('id',$userId)->first();
			$notifydata =UserNotification::where('status',1)->get();
			//update status .....clear notification
			DB::table('users')->where('id', $userId)->update(['is_clear_notification_date' => date('Y-m-d H:i:s')]);
			if($userData->is_clear_notification_date !=null ){
				$notifyCount =UserNotification::where('status',1)->where('created_at','>',$userData->is_clear_notification_date)->count();
			}else{
				$notifyCount =UserNotification::where('status',1)->count();
			}

			return Response::json(array(
				'status_code' => 1,
				'message' => 'Order details',
				'notifyCount' =>$notifyCount,
				'notifydata' =>$notifydata,
			),200);
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function updateUserViewnotifyStatus(Request $request){
		try{
			$userId = $request->input('user_id');

			DB::table('users')
				->where('id', $userId)
				->update(['user_view_notification' => 1]);
			return Response::json(array(
				'status_code' => 1,
				'message' => 'update successfully',
			),200);
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
//User wallet
	public function getUserWallet(Request $request){
		try{
			$userId = $request->input('user_id');
			$total=0;
			$total= Helper::get_wallet($userId);
			$wallet_list= Wallet::with('user')->where('user_id',$userId)->whereNotIn('payment_type', ['refer_and_earn'])->orderBy('id','desc')->get();
			$ref_wallet= Wallet::with('user')->where('user_id',$userId)->where('payment_type','refer_and_earn')->orderBy('id','desc')->get();

			return Response::json(array(
				'status_code' => 1,
				'message' => 'Order details',
				'total_amount' =>$total,
				'grocito_wallet' =>$wallet_list,
				'refer_wallet' =>$ref_wallet
			),200);
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function addUserWalletAmount(Request $request){
		try{
			$data['user_id'] = $request->input('user_id');
			$data['amount'] = $request->input('amount');
			$data['transaction_id'] = $request->input('transaction_id');
			$data['type'] = 'deposit';
			$data['payment_type'] = 'add_balance';
			$data['status'] = 'razorpay';
			$obj = new Wallet($data);
			$obj->save();
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Wallet amount added successfully',
			),200);
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	//user support api
	public function userCallRequest(Request $request){
		try{
			$data['user_id'] =$request->input('user_id');
			$obj = new CallRequest($data);
			if($obj->save()){
				//add notification for admin ..........
				$adminnotifyObj = new AdminNotification;
				$adminnotifyObj->int_val = $request->input('user_id');//user  id
				$adminnotifyObj->type = 'user_call_request';
				$adminnotifyObj->message = 'User Call Request';
				$adminnotifyObj->save();
				return Response::json(array(
					'status_code' => 1,
					'message' => 'call request send successfully',
				),200);
			}
		}catch(\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function getRaisingComplaintList(Request $request){
		try{
			$userId = $request->input('user_id');
			$raising= RaisingComplaint::where('user_id',$userId)->get();
			return Response::json(array(
				'status_code' => 1,
				'message' => 'successfully',
				'data' => $raising,
			),200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	//static pages content......
	public function pages(Request $request){
		try{
			$aboutUs = Cms::find(7);
			$term_condition= Cms::find(2);
			$privacy_policy= Cms::find(6);
			$return_policy= Cms::find(11);
			$shipping_delivery= Cms::find(5);
			$payment_policy = Cms::find(12);
			$faq = Faq::where('banned',0)->get();
			$discount_information = Cms::find(10);
			$faqData=['faq'=>$faq];
			$data = ['about_us' => $aboutUs,'term_condition'=>$term_condition,'privacy_policy'=>$privacy_policy,'return_policy'=>$return_policy,'shipping_delivery'=>$shipping_delivery,'payment_policy'=>$payment_policy,'discount_information'=>$discount_information,'faq'=>$faqData];
			return Response::json(array(
				'status_code' => 1,
				'data' => $data,
				'faq' => $faq,
				'message' => 'Pages',
			),200);
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	//function ================================================
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
	public function getNextSubOrderNumber()
	{
		return '#SUBORD'.rand(12,34).rand(11,99).rand(1,9);
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
	//End--------------------------------
}
?>