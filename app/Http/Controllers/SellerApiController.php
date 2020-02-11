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
use App\UserAddress;
use App\UserLocation;
use App\RiderCommission;
use App\PushNotification;
use App\State;
use App\City;
use App\Order;
use App\Payment;
use App\OrderMeta;
use App\Notice;
use App\SubCategory;
use App\Warehouse;
use App\Product;
use App\Category;
use App\Brand;
use App\Attribute;
use App\OrderRmaDetail;
use App\OrderExchange;
use App\ProductImage;
use App\ProductItem;
use App\Pincode;
use App\Agreement;
use App\SellerNotification;
use App\SellerDuplicateProduct;
use App\AdminNotification;
use DB;
use URL;
use Excel;
use Helper;
use Auth;
use File;
use Validator;
use Image;
//use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;
class SellerApiController  extends Controller
{
     public function __construct()
      {
	   parent::__construct(); 
      }
	/*register user......... .......................................................*/
	function add_seller(Request $request)
	{
		$mobile=$request->input('mobile');
		$users = array(
			'username' => $request->input('username'),//business name
			'mobile' => $request->input('mobile'),
			'email' => $request->input('email'),
			'role_id'     => 2,
		);
		$rules = array(
			//'email'     =>   'required|unique:users',
			'mobile'    =>   'required|unique:users',
		);
		$validator = Validator::make($users, $rules);
		if ($validator->fails()) {
			return Response::json(array(
				'status_code' => 0,
				'message' => 'validation error',
				'error_message' => $validator->errors()->first(),
			), 200);
		} else
			{
				$fullName = explode(" ", $request->input('username'));
				$num = count($fullName);
				if($num > 1)
				{
					$lastname = array_pop($fullName);
				}
				else
				{
					$lastname = '';
				}
				$firstname = implode(" ", $fullName);
				$user_kyc = array(
					'f_name'     => $firstname,
					'l_name'     => $lastname,
					'gender'     => $request->input('gender'),
					'country_id' => $request->input('country_id'),
					'city_id'    => $request->input('city_id'),
					'state_id'   => $request->input('state_id'),
					'address_2'    => $request->input('address_2'),
					'pincode'    => $request->input('pincode'),
					'business_name'    => $request->input('business_name'),
				);

				$password=str_random(3).rand(123,456);
				$users['password'] =    Hash::make($password);
				$users['simple_pass'] =    $password;
				$user = new User($users);
				$user->save();
				$user_kyc['user_id']= $user->id;
				$userkyc = new UserKyc($user_kyc);
				$userkyc->save();
				//add notification for admin ..........
			$adminnotifyObj = new AdminNotification;
			$adminnotifyObj->int_val = $user->id;//seller  id
			$adminnotifyObj->type = 'seller_join';
			$adminnotifyObj->message = 'New Seller Register';
			$adminnotifyObj->save();
				//************************************//
				$mmsg="Hi ".$request->input('name').", \n  Welcome to Grocito\n";
				$mmsg.="Your Account has been created successfully. Please login using below mobile and password \n";
				$mmsg.="Your mobile number is: ".($users['mobile'])."\n\n";
				$mmsg.="Your Password is : ".$password."\n\n";
				$mmsg.="Click on Below link to Login As seller \n";
				$mmsg.="https://www.grocito.com/seller/login \n";
				$mmsg.="\n\n Thanks Grocito";
				Helper::send_msg($users['mobile'],$mmsg);

				$msg="Hi ".$request->input('f_name').", <br><br>   Welcome to Saleplus<br><br>";
				$msg.="Your Account has been created successfully. Please login using below email and password<br>";
				$msg.="Your email address is: ".strtolower($request->input('email'))."<br><br>";
				$msg.="Your Password is : ".$password."<br><br>";
				$msg.="<a href='http://saleplus.in/seller/login'>Click</a> on Below link to login <br>";
				$msg.="<a href='http://saleplus.in/seller/login'>Login As seller</a><br>";
				$msg.="<br> <br>  Thanks Saleplus";

				$emailData = array(
					'to'        => array(strtolower($request->input('email'))),
					'from'      => 'support@saleplus.in',
					'subject'   => 'Account Created',
					'view'      => 'email.verification-email',
					'content'=>$msg
				);
				// Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
				// $message
				// ->to($emailData['to'])
				// ->from($emailData['from'])
				// ->subject($emailData['subject']);

				// });

				return Response::json(array(
						'status_code' => 1,
						'message' => 'successfully saved',
						'error_message'=>"saved successfully",
						'user_id'=>$user->id
					), 200);

			}

	}
	function update_seller_step_1(Request $request)
	{
		$input = $request->all();
		$seller_id = $input['user_id'];

		foreach($input as $key=>$vs)
		{
			if($vs == '')
			{
				unset($input[$key]);
			}
		}

		$user = User::find($input['user_id']);
		$user->fill($input)->save();
		unset($input['email']);
		unset($input['mobile']);
		unset($input['username']);
		$validator = Validator::make($request->all(),
			[
				//'username' => 'required|max:20|regex:/^[a-zA-Z .\']+$/',
				//'email' => 'required|max:100|email|unique:users,email'.($id != '' ? ','.$id:'').'',
				'mobile' => 'required|numeric|digits_between:8,10|unique:users,mobile'.($seller_id != '' ? ','.$seller_id:'').'',
			]);
		if ($validator->fails())
		{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'validation error',
				'error_message' => $validator->errors()->first(),
			), 200);
		}
		else
		{
			if(!empty($_FILES['profile_image']['name']))
			{
				$profile_image= time()."_".$_FILES['profile_image']['name'];
				move_uploaded_file($_FILES['profile_image']['tmp_name'],public_path() . '/admin/uploads/seller/'.$profile_image);
				$input['profile_image']=$profile_image;

			} else{ unset($input['profile_image']);}

			if(!empty($_FILES['seller_image']['name']))
			{
				$seller_image= time()."_".$_FILES['seller_image']['name'];
				move_uploaded_file($_FILES['seller_image']['tmp_name'],public_path() . '/admin/uploads/seller/'.$seller_image);
				$input['seller_image']=$seller_image;

			} else{
				unset($input['seller_image']);
			}
			UserKyc::where('user_id', $seller_id)->update($input);
			Session::flash('success_message', 'Successfully updated profile!');

			return Response::json(array(
				'status_code' => 1,
				'message' => 'updated successfully',
				'error_message'=>"updated successfully",
			), 200);
		}
	}

	function update_seller_step_2(Request $request)
	{

			$input = $request->all();
		$seller_id = $input['user_id'];
			foreach($input as $key=>$vs)
			{
				if($vs == '')
				{
					unset($input[$key]);
				}
			}
			$validator = Validator::make($request->all(),
				[]);
			if ($validator->fails())
			{
				return Response::json(array(
					'status_code' => 0,
					'message' => 'validation error',
					'error_message' => $validator->errors()->first(),
				), 200);
			}
			else
			{
				if(!empty($_FILES['pan_image']['name']))
				{
					$pan_image= time()."_".$_FILES['pan_image']['name'];
					move_uploaded_file($_FILES['pan_image']['tmp_name'],public_path() . '/admin/uploads/seller/'.$pan_image);
					$input['pan_image']=$pan_image;

				}
				else{ unset($input['pan_image']);}
				if(!empty($_FILES['cancel_cheque']['name']))
				{
					//echo $_FILES['cancel_cheque']['name']; die;

					$cancel_cheque= time()."_".$_FILES['cancel_cheque']['name'];
					move_uploaded_file($_FILES['cancel_cheque']['tmp_name'],public_path() . '/admin/uploads/seller/'.$cancel_cheque);
					$input['cancel_cheque']=$cancel_cheque;

				} else{ unset($input['cancel_cheque']);}



				if(!empty($_FILES['cin_image']['name']))
				{
					$cin_image= time()."_".$_FILES['cin_image']['name'];
					move_uploaded_file($_FILES['cin_image']['tmp_name'],public_path() . '/admin/uploads/seller/'.$cin_image);
					$input['cin_image']=$cin_image;

				} else{ unset($input['cin_image']);}
				UserKyc::where('user_id', $seller_id)->update($input);
				Session::flash('success_message', 'Successfully updated profile!');
				$sellerAgreement = Agreement::first();
				return Response::json(array(
					'status_code' => 1,
					'message' => 'updated successfully',
					'error_message'=>"updated successfully",
					'seller_agreement'=>$sellerAgreement,
				), 200);
			}


	}

	function update_seller_step_3(Request $request)
	{
		$seller_id = $request->input('user_id');
			$user = User::find($seller_id);
			$user->fill(['verify_status'=>"kyc_completed"])->save();
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Kyc Completed',
			'error_message'=>"Kyc Completed",
		), 200);

	}
	function delovery_pincode(Request $request){
		$city_id = $request->input('city_id');

		$deliveryPincode = Pincode::where('city_id',$city_id)->get();
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Delivery pincode',
			'error_message'=>"Delivery pincode",
			'data'=>$deliveryPincode,
		), 200);

	}
	//seller login....................................................................................
	    public function seller_login(Request $request){
			$mobile =$request->input('mobile');
			$password = $request->input('password');
			$data=User::where('mobile',$mobile)->first();
			if($data['banned']==1)
			{
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Your account has been blocked',
					'error_message'=>"Your account has been blocked",
				), 200);
			}
			else
			{
				if (Auth::attempt(array('mobile' => $mobile, 'password' => $password,'activated'=>1,'banned'=>0,'role_id'=>2))) {
					if(Auth::user()->verify_status=="verified"):
						return Response::json(array(
							'status_code' => 1,
							'message' => 'Login succssfully',
							'error_message'=>"Login succssfully",
							'data' => User::with('user_kyc')->where('mobile',$mobile)->get(),
						), 200);
					else:
						return Response::json(array(
							'status_code' => 1,
							'message' => 'Login succssfully',
							'error_message'=>"Login succssfully",
							'data' => User::with('user_kyc')->where('mobile',$mobile)->get(),
						), 200);
					endif;

				} else {
					Session::flash('error_message', 'Invalid mobile or password');
					return Response::json(array(
						'status_code' => 0,
						'message' => 'Invalid mobile or password',
						'error_message'=>"Invalid mobile or password",
						'data' => '',
					), 200);
				}
			}
        
       
    }
    public function sellerForgotPassword(Request $request){
        $mobile = $request->input('mobile');
                $checkMobileNumber = User::where('mobile', $mobile)->where('role_id',2)->count();
                if ($checkMobileNumber) {
                      $userData['otp']= rand(12,66).rand(67,89);
                    $mmsg=" Use ".$userData['otp']." as one time password(OTP) to verify your account.  Do not share this OTP to anyone for security reasons.";
                    Helper::send_msg($mobile,$mmsg);
                    	return Response::json(array(
							'status_code' => 1,
							'message' => 'Otp send successfully!',
							'otp' =>$userData['otp'],
						), 200);
                   
                } else {
                   	return Response::json(array(
							'status_code' => 0,
							'message' => 'Invalid mobile number',
						), 200);
                }
    }
	//change Password...............
	public function sellerChangePassword(Request $request)
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
			{
				$usersData=array();
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
			}else{
				return Response::json(array(
					'status_code' => 0,
					'message' => 'User Does not exists',
					'error_message'=>"User Does not exists",
				), 200);
			}

		}
	}
    public function verifyForgotPasswordOtp(Request $request){
        $mobile = $request->input('mobile');
         if($mobile){
            $sellerData = User::where('mobile',$mobile)->first();
            if($sellerData){
             $mmsg="Password: ".$sellerData->simple_pass."  Do not share this password to anyone for security reasons.";
             Helper::send_msg($mobile,$mmsg);
            Session::flash('success_message', '');
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Password sent to your mobile number',
				), 200);
            }else{
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Please try again',
				), 200);

            }
            }
          
    }
	//***************** Dashboard  **************************//
	public function dashboard_data(Request $request){
		$seller_id = $request->input('user_id');
		$notify_count= SellerNotification::where('seller_id',$seller_id)->where('status',0)->get()->count();
		$pendingOrder = OrderMeta::where('status','pending')->where('seller_id',$seller_id)->get()->count();
		//$pendingOrder = Order::with('order_meta_data')->where('seller_id',$seller_id)->where('status','pending')->get()->count();
		$total_sale=Payment::where('type','deposit')->where('user_id',$seller_id)->sum('amount');
		$todayDate = date('Y-m-d');
		$todayPaymentData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
              SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			order_metas.delivery_date = '$todayDate' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ");
		$total_today_payable_amount = 0;
		$tcsTax = 0;
		if($todayPaymentData){
			foreach($todayPaymentData as $td){
				$totalTodayAmount = $td->total;
				$totalTodayNetAmount = $td->net_amount;
				$totalTodayCommission = $td->total_admin_commission;
				$gstAmount =  ($totalTodayCommission * 18)/100;
				$totalAdminCmsn = $totalTodayCommission + $gstAmount;
				$tcsTax = 	($totalTodayNetAmount * 1)/100;
				$total_today_payable_amount += ($totalTodayAmount - $totalAdminCmsn - $tcsTax);
			}
		}
		$data= ['notify_count'=>$notify_count,'pending_order'=>$pendingOrder,'total_paid_amount'=>$total_sale,'today_payment'=>$total_today_payable_amount];
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Login succssfully',
			'error_message'=>"Login succssfully",
			'data' => $data,
		), 200);
	}
	//********** Product ******************//
	public function add_product(Request $request){
		$productData = array(
			'user_id' => $request->input('user_id'),
			'name' => $request->input('name'),
			'description' => $request->input('description'),
			'p_gst' => $request->input('p_gst'),
			'category_id' => $request->input('category_id'),
			'sub_category_id' => $request->input('sub_category_id'),
		);
		$rules = array(
			'user_id'    =>   'required',
			'name'    =>   'required',
			'description'    =>   'required',
			'p_gst'    =>   'required',
			'category_id'    =>   'required',
			'sub_category_id'    =>   'required',
		);
		$validator = Validator::make($productData, $rules);
		if ($validator->fails()) {
			return Response::json(array(
				'status_code' => 0,
				'message' => 'validation error',
				'error_message' => $validator->errors()->first(),
			), 200);
		} else {
			$seller_id= $request->input('user_id');
			$sellerData = UserKyc::where('user_id',$seller_id)->first();

			$name= $request->input('name');
			$description= $request->input('description');

			$brand_id= $request->input('brand_id');
			$category_id= $request->input('category_id');
			$sub_category= $request->input('sub_category_id');
			$p_gst= $request->input('p_gst');
			$p_color= $request->color;

			if($sub_category)
			{
				$slug=SubCategory::select('slug')->where('id',$sub_category)->first();
				$sub_category_slug= $slug->slug;
			}
			else
			{
				$sub_category_slug="";
			}

			$product_info=array(
				'user_id'=>$seller_id,
				'city_id'=>$sellerData->city_id,
				'name'=>$name,
				'brand_id'=>$brand_id,
				'description'=>$description,
				'category_id'=>$category_id,
				'sub_category_id'=>$sub_category,
				'sub_category_slug'=>$sub_category_slug,
				'p_gst'=>$p_gst,
				'color'=>$p_color,
			);
			$product = new Product($product_info);
			$product->save();

			$category= Category::select('slug')->where('id',$category_id)->first();
			$pInfoUpdate['slug']= str_slug($request->name." ".$product->id,"-");
			$pInfoUpdate['sku']= "GRCTO-".$product->id;
			$pInfoUpdate['category_slug']=$category['slug'];
			DB::table('products')->where('id',$product->id)->update($pInfoUpdate);
			$price_unit= json_decode($request->input('price_unit'));
			foreach($price_unit as $ks=>$value)
			{
				$price = $value->price;
				$weight = $value->weight;
				$offer = $value->offer;
				$qty = $value->qty;

				$salePrice = $price - $offer;
				$item=array();
				$item['product_id']=  $product->id;
				$item['seller_id']= $request->input('user_id');
				$item['weight']= $weight;
				$item['price']= $price;
				$item['offer'] = $offer;
				$item['sprice']= $salePrice;
				$item['qty']= $qty;
				$obj= new ProductItem($item);
				$obj->save();

			}

			$allFile = $request->file('product_image');

				$i=0;
				foreach ($allFile as $file) {
					// Valid extension
					$valid_ext = array('png','jpeg','jpg');
					$photo_name = time(). '-' .$file->getClientOriginalName();
					$propertyArray=array();
					//$photo_name = time() . '-' . $file['name'];
					$path_original = public_path() . '/admin/uploads/product/'.$photo_name;
					// file extension
					$file_extension = pathinfo($path_original, PATHINFO_EXTENSION);
					$file_extension = strtolower($file_extension);
					$this->compressImage($file->getPathName(),$path_original,50);
					$ratio=16/9;
					$img = Image::make(realpath($path_original));
					if($img->height()>512)
					{
						//$img->resize(intval($img->width() / $ratio),512);
						$img->resize(null,512,function ($constraint) {
							$constraint->aspectRatio();

						});
						$img->save($path_original);
					}
					$watermark = "C-".rand(10,99).rand(111,999);
					//$this->addTextWatermark($path_original, $watermark, $path_original);
					$propertyArray = [
						'product_id' => $product->id,
						'image' =>$photo_name,
					];
					DB::table('product_images')->insert($propertyArray);
					$i++;
				}

//add notification for admin ..........
			$adminnotifyObj = new AdminNotification;
			$adminnotifyObj->int_val = $product->id;//product  id
			$adminnotifyObj->type = 'product_upload';
			$adminnotifyObj->message = 'New Product Upload';
			$adminnotifyObj->save();
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Saved successfully',
				'error_message' => "Saved successfully",
				//'user_image_path' => url('/')."/public/admin/uploads/user/",
				//'data' => Product::with('product_image')->where('user_id',$seller_id)->get(),
			), 200);
		}
	}
	public function update_product(Request $request){
		$productData = array(
			'user_id' => $request->input('user_id'),
			'name' => $request->input('name'),
			'description' => $request->input('description'),
			'p_gst' => $request->input('p_gst'),
			'category_id' => $request->input('category_id'),
			'sub_category_id' => $request->input('sub_category_id'),
		);
		$rules = array(
			'user_id'    =>   'required',
			'name'    =>   'required',
			'description'    =>   'required',
			'p_gst'    =>   'required',
			'category_id'    =>   'required',
			'sub_category_id'    =>   'required',
		);
		$validator = Validator::make($productData, $rules);
		if ($validator->fails()) {
			return Response::json(array(
				'status_code' => 0,
				'message' => 'validation error',
				'error_message' => $validator->errors()->first(),
			), 200);
		} else {
			$id= $request->input('product_id');
			$price_unit= json_decode($request->input('price_unit'));
			foreach($price_unit as $ks=>$value)
			{
				$price = $value->price;
				$weight = $value->weight;
				$offer = $value->offer;
				$qty = $value->qty;
				$itemId = $value->item_id;

				$salePrice = $price - $offer;
				$item=array();
				$item['product_id']=  $id;
				$item['seller_id']= $request->input('user_id');
				$item['weight']= $weight;
				$item['price']= $price;
				$item['offer'] = $offer;
				$item['sprice']= $salePrice;
				$item['qty']= $qty;
				if($itemId > 0){
					$update_data = ProductItem::find($itemId)->fill($item);
					if($update_data){
						$update_data->update();
					}
				}else{
					$obj= new ProductItem($item);
					$obj->save();
				}



			}
			$seller_id= $request->input('user_id');
			//end similar product code.......
			$name= $request->input('name');

			$description= $request->input('description');
			$p_gst= $request->input('p_gst');

			$color= $request->input('color');
			$brand_id= $request->input('brand_id');
			$category= $request->input('category_id');
			$sub_category= $request->input('sub_category_id');


			if($sub_category)
			{
				$slug=SubCategory::select('slug')->where('id',$sub_category)->first();
				$sub_category_slug= $slug->slug;
			}
			else
			{
				$sub_category_slug="";
			}
			$product_info=array(
				'user_id'=>$seller_id,
				'name'=>$name,
				'brand_id'=>$brand_id,
				'description'=>$description,
				'category_id'=>$category,
				'sub_category_id'=>$sub_category,
				'sub_category_slug'=>$sub_category_slug,
				'p_gst'=>$p_gst,
				'color'=>$color,
			);

			$category= Category::select('slug')->where('id',$request->category_id)->first();
			$product_info['slug']= str_slug($request->name." ".$id,"-");
			$product_info['category_slug']=$category->slug;
			$update_data = Product::find($id)->fill($product_info);
			$update_data->update();

			$allFile = $request->file('product_image');
			if($allFile){


			$i=0;
			foreach ($allFile as $file) {
				// Valid extension
				$valid_ext = array('png','jpeg','jpg');
				$photo_name = time(). '-' .$file->getClientOriginalName();
				$propertyArray=array();
				//$photo_name = time() . '-' . $file['name'];
				$path_original = public_path() . '/admin/uploads/product/'.$photo_name;
				// file extension
				$file_extension = pathinfo($path_original, PATHINFO_EXTENSION);
				$file_extension = strtolower($file_extension);
				$this->compressImage($file->getPathName(),$path_original,50);
				$ratio=16/9;
				$img = Image::make(realpath($path_original));
				if($img->height()>512)
				{
					//$img->resize(intval($img->width() / $ratio),512);
					$img->resize(null,512,function ($constraint) {
						$constraint->aspectRatio();

					});
					$img->save($path_original);
				}
				$watermark = "C-".rand(10,99).rand(111,999);
				//$this->addTextWatermark($path_original, $watermark, $path_original);
				$propertyArray = [
					'product_id' => $id,
					'image' =>$photo_name,
				];
				DB::table('product_images')->insert($propertyArray);
				$i++;
			}
			}


			return Response::json(array(
				'status_code' => 1,
				'message' => 'Update successfully',
				'error_message' => "Update successfully",
				//'user_image_path' => url('/')."/public/admin/uploads/user/",
				//'data' => Product::with('product_image')->where('user_id',$seller_id)->get(),
			), 200);
		}
	}
	//To get duplicate product name list..
	public function get_duplicate_product(Request $request){
		$user_id = $request->user_id;
		$search_key = $request->search_key;
		if($search_key ==''){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Product List',
				'error_message' => "Product List",
				'data' => [],
			), 200);
		}
		$data=Product::with('product_image','main_category','sub_category','product_note','product_item')->where('user_id', '<>', $user_id)->where('status',1)->where('name', 'like','%'. $search_key . '%')->get();

		if($data){

			return Response::json(array(
				'status_code' => 1,
				'message' => 'Product List',
				'error_message' => "Product List",
				'data' => $data,
			), 200);
		}

	}
	//To add duplicate product...
	public function add_duplicate_product(Request $request){
		$id= $request->input('product_id');
		$sellerId= $request->input('user_id');
		if(empty($id)){
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Product id required',
				'error_message' => "Product id required",
			), 200);
		}
		$product_data = Product::where('id',$id)->first();
		$dupliacte['product_id'] = $product_data->id;
		$dupliacte['seller_id']  = $sellerId;

		$instance = SellerDuplicateProduct::firstOrNew(array('seller_id' => $sellerId,'product_id'=>$product_data->id));
		$instance->fill($dupliacte)->save();

		$price_unit= json_decode($request->input('price_unit'));
		foreach($price_unit as $ks=>$value)
		{
			$price = $value->price;
			$weight = $value->weight;
			$offer = $value->offer;
			$qty = $value->qty;
			@$itemId = @$value->item_id;

			$salePrice = $price - $offer;
			$item=array();
			$item['product_id']=  $id;
			$item['seller_id']= $sellerId;
			$item['weight']= $weight;
			$item['price']= $price;
			$item['offer'] = $offer;
			$item['sprice']= $salePrice;
			$item['qty']= $qty;
			if($itemId > 0){
				$update_data = ProductItem::find($itemId)->fill($item);
				if($update_data){
					$update_data->update();
				}
			}else{
				$obj= new ProductItem($item);
				$obj->save();
			}
		}
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Add successfully',
			'error_message' => "Add successfully",
			'data' => [],
		), 200);

	}
	public function duplicate_product_list(Request $request){
		$sellerId = $request->user_id;
		$p_list=SellerDuplicateProduct::with('product','product.product_item','product.product_image','product.main_category','product.sub_category','product.super_sub_category','product.product_note')->where('seller_id',$sellerId)->orderBy('id','desc')->get();
		if($p_list){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Product List',
				'error_message' => "Product List",
				'data' => $p_list,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Product List',
				'error_message' => "Product List",
				'data' => [],
			), 200);
		}

	}
	public function delete_duplicate_product(Request $request){
		$duplicateId = $request->duplicate_id;
		if(empty($duplicateId)){
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Duplicate id is required!',
				'data' => [],
			), 200);
		}
		$product=SellerDuplicateProduct::where('id', '=',$duplicateId)->first();
		$productId = $product->product_id;
		$sellerId = $product->seller_id;
		if($productId and $sellerId){
			$item= ProductItem::where('product_id',$productId)->where('seller_id',$sellerId)->get();
			if($item) {
				foreach ($item as $vs) {
					$delete = ProductItem::where('id', $vs->id)->delete();
				}
			}
		}
		$product->delete();
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Delete Successfully',
			'error_message' => "Delete Successfully",
			'data' => [],
		), 200);
	}
	public function product_list(Request $request){
		$seller_id = $request->user_id;
		$catatlog_list=Product::with('product_image','main_category','sub_category','product_note','product_item')->where('status',1)->where('user_id',$seller_id)->orderBy('id','desc')->get();
		if($catatlog_list){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Saved successfully',
				'error_message' => "Saved successfully",
				'data' => $catatlog_list,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Record not found',
				'error_message' => "Record not found",
				'data' => [],
			), 200);
		}
	}
	public function order_assign_to_rider(Request $request){
		try {
			$seller_id = $request->user_id;
			$order_id = $request->order_id;
			$date=date("Y-m-d");
			$orderData['expected_delivery_date']=date('Y-m-d',strtotime($date));
			$orderData['status']="assign_to_rider";
			$order_details=Order::with('user_kyc','address','user','seller_kyc','seller')->where('id',$order_id)->first();
			$item_details=OrderMeta::select(DB::raw('group_concat(product_name) as item_name'),DB::raw('group_concat(qty) as item_qty'),DB::raw('group_concat(weight) as weight'),DB::raw('sum(price*qty) as total_amount'))->where('order_id',$order_details->id)->where('seller_id',$seller_id)->first();
			$latlong= UserKyc::where('user_id',$seller_id)->select('latitude','longitude')->first();
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
			//print_r($reference);
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
			$notifyData['address']=$wData->address;
			//$notifyData['address']=$order_details->address->house.",".$order_details->address->street.",".$order_details->address->pincode;
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

			$data=DB::table('order_metas')->where('order_id', '=',$order_id)->where('seller_id', '=',$seller_id)->update(['expected_delivery_date' =>  $orderData['expected_delivery_date'],'status'=>$orderData['status']]);
			DB::table('orders')->where('id', '=',$order_id)->update(['status'=>$orderData['status']]);
			//$order=DB::table('orders')->where('id', '=',$order_id)->update(['status'=>$orderData['status']]); //$orderData['status']

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
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Order has been assigned set successfully',
				'error_message' => "Order has been assigned set successfully",
				'data' => [],
			), 200);

		}
		catch (\Exception $e) {

			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage()."Line-".$e->getLine(),
			), 500);
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
	public function order_cancel(Request $request){

		$order_id=$request->input('order_id');
		$updateOrderStatus = DB::table('orders')->where('id',$order_id)->update(['status' => 'cancelled']);
		$updateOrderMetaStatus = DB::table('order_metas')->where('order_id',$order_id)->update(['status' => 'cancelled']);
		Helper::updateOrderStatus($order_id,'cancelled','Order Cancelled');
		//update item qty if order cancel.....
		$getOrderItem=OrderMeta::where('order_id',$order_id)->get();
		foreach ($getOrderItem as $meta){
			$itemId = $meta['item_id'];
			$itemCancelQty = $meta['qty'];
			$getItemQty = DB::table('product_items')->where('id',$itemId)->first();
			$remainingQty = $getItemQty->qty;
			$updateQty = $itemCancelQty + $remainingQty;
			$updateItemQty =DB::table('product_items')->where('id',$itemId)->update(['qty' => $updateQty]);
		}
		//add notification for admin ..........
		$adminnotifyObj = new AdminNotification;
		$adminnotifyObj->int_val = $order_id;//order id
		$adminnotifyObj->type = 'order_cancel';
		$adminnotifyObj->message = 'Order Cancel';
		$adminnotifyObj->save();
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Order cancelled successfully',
			'error_message' => "Order cancelled successfully",
			'data' => [],
		), 200);

	}

	public function delete_product(Request $request){
		$productData = array(
			'product_id' => $request->input('product_id'),
		);
		$rules = array(
			'product_id'    =>   'required',
		);
		$validator = Validator::make($productData, $rules);
		if ($validator->fails()) {
			return Response::json(array(
				'status_code' => 0,
				'message' => 'validation error',
				'error_message' => $validator->errors()->first(),
			), 200);
		} else {
			$pId = $request->product_id;
			$product = Product::where('id', '=', $pId)->first();
			$productImage = ProductImage::where('product_id',$pId)->get();

			if ($product->delete()) {
				$path = public_path() . '/admin/uploads/product/';
				try {
					foreach ($productImage as $img){
						unlink($path . $img->image);
					}
				} catch (\Exception $e) {
				}
				//delete image from table
				$proImageDel = ProductImage::where('product_id',$pId)->delete();
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Catalog has been deleted successfully',
					'error_message' => "Catalog has been deleted successfully",
					'data' => [],
				), 200);
			} else {
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Please try again',
					'error_message' => "Please try again",
					'data' => [],
				), 200);
			}
		}

	}
	//*****************END Product ***********//

	public function color_list(){

		$colorList=Attribute::where('type','product')->where('name','color')->get();
		if($colorList){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Color List',
				'error_message' => "Color List",
				'data' => $colorList,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Record not found',
				'error_message' => "Record not found",
				'data' => [],
			), 200);
		}
	}
	public function cat_list(){
		$category_list=Category::where('status',1)->get();
		if($category_list){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Catgory List',
				'error_message' => "Catgory List",
				'data' => $category_list,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Record not found',
				'error_message' => "Record not found",
				'data' => [],
			), 200);
		}
	}
	public function sub_cat_list(Request $request){
		$catId = $request->input('cat_id');
		$sub_category_list=SubCategory::where("category_id",$catId)->get();
		if($sub_category_list){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Sub Catgory List',
				'error_message' => "Catgory List",
				'data' => $sub_category_list,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Record not found',
				'error_message' => "Record not found",
				'data' => [],
			), 200);
		}
	}
	public function sellerAddBrand(Request $request)
	{	
		try{
			$brandData['name'] =$request->input('name');
			if($brandData['name'] ==''){
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Please enter brand name',
				), 200);
			}
			$brandData['status'] =1;
			$brand = new Brand($brandData);
			if($brand->save()){
				return Response::json(array(
					'status_code' => 1,
					'message' => 'add successfully',
				), 200);
			}
		}catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
		
	}
	public function brand_list(){
		$brand=Brand::where('status',1)->orderBy('id','desc')->get();
		if($brand){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Brand List',
				'error_message' => "Brand List",
				'data' => $brand,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Record not found',
				'error_message' => "Record not found",
				'data' => [],
			), 200);
		}
	}
	public function state(Request $request){
		$country_id = $request->country_id;
		$stateData= State::where('country_id', $country_id)->get();
		//$stateData= City::with('state')->where('status',1)->groupBy('state_id')->get();
		if(count((array)$stateData)>0)
		{
			return Response::json(array(
				'status_code' => 1,
				'message' => 'state list',
				'error_message'=>'state list',
				'data'=>$stateData
			), 200);
		}
		else
		{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'state list',
				'error_message'=>'state list',
				'state_data'=>null
			), 200);
		}
	}
	public function city(Request $request){
		$state_id = $request->state_id;
		$cityData= City::where('state_id', $state_id)->where('status',1)->get();
		if(count((array)$cityData)>0)
		{
			return Response::json(array(
				'status_code' => 1,
				'message' => 'city list',
				'error_message'=>'city list',
				'data'=>$cityData
			), 200);
		}
		else
		{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'city list',
				'error_message'=>'city list',
				'data'=>null
			), 200);
		}
	}

	//************************* ORDER MANAGEMENT ***********************************//
	public function get_order_list(Request $request){
		$seller_id = $request->user_id;
		$status = $request->order_status;
		$list=array();
		$orderList = OrderMeta::select('*', DB::raw('SUM(price*qty) AS amount'))->with('order','order.address')->where('status',$status)->where('seller_id',$seller_id)->groupBy('order_id')->orderBy('created_at','desc')->get();
		//$orderList = Order::with('order_meta','address')->where('seller_id',$seller_id)->where('status',$status)->orderBy('created_at','DESC')->get();
		foreach ($orderList as $vs){
			$array= array();
			$array['id']=$vs->order->id;
			$array['order_id']=$vs->order->order_id;
			$array['total_amount']=$vs->amount;
			$array['delivery_boy_id']=$vs->delivery_boy_id;
			$array['shipping_charge']=$vs->order->shipping_charge;
			$array['wallet_amount']=$vs->order->wallet_amount;
			$array['payment_mode']=$vs->order->payment_mode;
			$array['delivery_type']=$vs->order->delivery_type;
			$array['delivery_date']=$vs->order->delivery_date;
			$array['delivery_time']=$vs->order->delivery_time;
			$array['shipped_date']=$vs->delivery_date;
			$array['status']=$vs->status;
			$array['created_at']=date('d-m-Y',strtotime($vs->created_at));
			$array['qty']= Helper::get_number_of_qty($vs->order_id,$seller_id,$status);
			$array['number_of_products']= Helper::get_number_of_product($vs->order_id,$seller_id);
			$array['product_image']=Helper::get_order_image($vs->order_id,$seller_id,$status);
			$array['delivery_address']=$vs->order->address;

			$list[]= $array;
			//$sum= $sum+$vs->amount;
		}
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Order List',
			'error_message'=>'Order List',
			'image_path'=>URL::asset('public/admin/uploads/product/'),
			'data'=>$list
		), 200);
	}
	public function get_order_list_old(Request $request){
		$seller_id = $request->user_id;
		$status = $request->order_status;
		$list=array();
		$orderList = Order::with('order_meta','address')->where('seller_id',$seller_id)->where('status',$status)->orderBy('created_at','DESC')->get();
		foreach ($orderList as $vs){
			$array= array();
			$array['id']=$vs->id;
			$array['order_id']=$vs->order_id;
			$array['total_amount']=round($vs->total_amount,2);
			$array['delivery_boy_id']=$vs->delivery_boy_id;
			$array['shipping_charge']=$vs->shipping_charge;
			$array['wallet_amount']=$vs->wallet_amount;
			$array['payment_mode']=$vs->payment_mode;
			$array['delivery_type']=$vs->delivery_type;
			$array['delivery_date']=$vs->delivery_date;
			$array['delivery_time']=$vs->delivery_time;
			$array['shipped_date']=$vs->shipped_date;
			$array['status']=$vs->status;
			$array['created_at']=date('d-m-Y',strtotime($vs->created_at));
			$array['qty']= Helper::get_number_of_qty($vs->id,$seller_id,$status);
			$array['number_of_products']= Helper::get_number_of_product($vs->id,$seller_id);
			$array['product_image']=Helper::get_order_image($vs->id,$seller_id,$status);
			$array['delivery_address']=$vs->address;

			$list[]= $array;
			//$sum= $sum+$vs->amount;
		}
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Order List',
				'error_message'=>'Order List',
				'image_path'=>URL::asset('public/admin/uploads/product/'),
				'data'=>$list
			), 200);
	}
	public function order_details(Request $request){
		$order_id = $request->order_id;
		$list=array();
		$sum=0;
		$orders = OrderMeta::with('order')->where('order_id',$order_id)->get();
		$order_info = Order::with('address')->where('id',$order_id)->first();
		foreach ($orders as $vs){
			 if($vs->return_status==1)
			 {
			 $sum= $sum+($vs->price*$vs->qty);
			 }
			$array= array();
			$array['id']=$vs->order_id;

			$array['status']=$vs->order->status;
			$array['created_at']=date('d-m-Y',strtotime($vs->delivery_date));
			$array['name']= $vs->product_name;
			$array['qty']= $vs->qty;
			$array['price']= $vs->price;
			$array['weight']= $vs->weight;
			$array['product_image']=$vs->product_image;
			$array['return_status']=$vs->return_status;
			$array['exchange_status']=$vs->exchange_status;
			$array['meta_status']=$vs->status;
			$list[]= $array;
		}
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Seller Amount',
			'error_message' => "Seller Amount",
			'total_amount' => $order_info->total_amount-$sum,
			'delivery_type' => $order_info->delivery_type,
			'shipping_charge' => $order_info->shipping_charge,
			'payment_mode' => $order_info->payment_mode,
			'delivery_date' => $order_info->delivery_date,
			'delivery_time' => $order_info->delivery_time,
			'data' => $list,
			'delivery_address' => $order_info->address,

		), 200);

	}
	public function return_order_list(Request $request){
		$sellerId = $request->user_id;
		$orders = OrderMeta::with('order')->select('*', DB::raw('SUM(price*qty) AS amount'))->where('seller_id',$sellerId)->where('status','return')->groupBy('order_id')->orderBy('created_at','desc')->get();

		//$returnOrders = OrderMeta::with('order')->where('seller_id',$sellerId)->where('status','return')->groupBy('order_id')->orderBy('created_at','desc')->get();
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Return order list',
			'error_message' => "Return order list",
			'data' => $orders,
		), 200);

	}
	public function exchange_order_list(Request $request){
		$sellerId = $request->user_id;
		$exchangeOrders = OrderMeta::with('order')->where('seller_id',$sellerId)->where('status','exchange')->groupBy('order_id')->orderBy('created_at','desc')->get();
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Exchange order list',
			'error_message' => "Exchange order list",
			'data' => $exchangeOrders,
		), 200);
	}
	//***************** Inventory ****************************//
	public function inventory_in_stock_list(Request $request){
		$seller_id = $request->user_id;
		$catatlog_list = DB::table('categories')
			->join('products', 'categories.id', '=', 'products.category_id')
			->where('products.status', '=', 1)
			->where('products.stock_status', '=', 1)
			->where('products.user_id', '=', $seller_id)
			->where('products.is_admin_approved',1)
			->groupBy('products.category_id')
			->select('categories.*',DB::raw("products.id as product_id"),DB::raw("count(products.id) as count"))
			->get();
		if($catatlog_list){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Inventory in stock List',
				'error_message' => "Inventory List",
				'data' => $catatlog_list,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Record not found',
				'error_message' => "Record not found",
				'data' => [],
			), 200);
		}

	}
	public function inventory_in_stock_product_list(Request $request){
		$seller_id = $request->user_id;
		$category_id = $request->cat_id;
		$catatlog_list=Product::with('product_image','main_category','sub_category','product_item')->where('status',1)->where('is_admin_approved',1)->where('category_id',$category_id)->where('stock_status',1)->where('user_id',$seller_id)->orderBy('id','desc')->get();

		if($catatlog_list){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Inventory Product List',
				'error_message' => "Inventory Product List",
				'data' => $catatlog_list,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Record not found',
				'error_message' => "Record not found",
				'data' => [],
			), 200);
		}

	}
	public function inventory_out_of_stock_list(Request $request){
		$seller_id = $request->user_id;
		$catatlog_list = DB::table('categories')
			->join('products', 'categories.id', '=', 'products.category_id')
			->where('products.status', '=', 1)
			->where('products.stock_status', '=', 0)
			->where('products.user_id', '=', $seller_id)
			->groupBy('products.category_id')
			->select('categories.*',DB::raw("products.id as product_id"),DB::raw("count(products.id) as count"))
			->get();
		if($catatlog_list){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Inventory out of stock List',
				'error_message' => "Inventory out of stock List",
				'data' => $catatlog_list,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Record not found',
				'error_message' => "Record not found",
				'data' => [],
			), 200);
		}

	}
	public function inventory_out_of_stock_product_list(Request $request){
		$seller_id = $request->user_id;
		$category_id = $request->cat_id;
		$catatlog_list=Product::with('product_image','main_category','sub_category','product_item')->where('status',1)->where('category_id',$category_id)->where('stock_status',0)->where('user_id',$seller_id)->orderBy('id','desc')->get();

		if($catatlog_list){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Inventory out of stock product list',
				'error_message' => "Inventory out of stock product list",
				'data' => $catatlog_list,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Record not found',
				'error_message' => "Record not found",
				'data' => [],
			), 200);
		}

	}
	public function getProductItem(Request $request)
	{
		try{
			$sellerId = $request->input('seller_id');
			$proId = $request->input('product_id');
			$item_details=ProductItem::where('product_id',$proId)->where('seller_id',$sellerId)->get();
			if(count($item_details)){
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Product item list',
					'error_message' => "Product item list",
					'data' => $item_details,
				), 200);
			}else{
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Record not found',
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
	public function sellerUpdateItemQty(Request $request)
	{
		try{
			$product_id = $request->input('product_id');
			$qty= json_decode($request->input('qty'));
			$item_id= json_decode($request->input('item_id'));
			if(empty($product_id)){
				return Response::json(array(
					'status_code' => 0,
					'message' => 'Product id is required!',
					'error_message' => "Product id is required!",
				), 200);
			}
			foreach($qty as $ks=>$vs)
			{
				$item=array();
				$item['qty']= $vs;
				$itemCount = count($item_id);

				if(!empty($item_id) && $itemCount > $ks ){
					$itemId = $item_id[$ks];
					if($itemId){
						$update_data = ProductItem::find($itemId)->fill($item);
						if($update_data){
							$update_data->update();
						}
					}
				}else{
					return Response::json(array(
						'status_code' => 0,
						'message' => 'Please try again',
						'error_message' => "Please try again!",
					), 200);
				}

			}
			$product_info['stock_status']=1;
			$update_data = Product::find($product_id)->fill($product_info);
			if($update_data->update())
			{
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Update successfully',
					'error_message' => "Update successfully",
				), 200);
			}

		}	catch (\Exception $e){
			return Response::json(array(
				'status_code' => 0,
				'message' => $e->getMessage(),
			),200);
		}
	}
	public function update_stock_status(Request $request){

		$product_id=$request->input('product_id');
		$type=$request->input('type');
		if(empty($type)){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Type is required',
				'error_message' => "Type is required",
				'data' => [],
			), 200);
		}
		if($type=="in_stock")
		{
			$product_info['stock_status']=1;
			$update_data = Product::find($product_id)->fill($product_info);
			if($update_data->update())
			{
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Product Marked in stock successfully',
					'error_message' => "Product Marked in stock successfully",
					'data' => $update_data,
				), 200);

			}
		}
		if($type=="out_stock")
		{
			$product_info['stock_status']=0;
			$update_data = Product::find($product_id)->fill($product_info);
			if($update_data->update())
			{
				return Response::json(array(
					'status_code' => 1,
					'message' => 'Product Marked out of stock successfully',
					'error_message' => "Product Marked out of stock successfully",
					'data' => $update_data,
				), 200);

			}
		}
	}
//******************************* Seller Payment ***********************************//
	public function payment(Request $request){
		$seller_id = $request->user_id;
		$paid_amount= 0;
		$total_today_payable_amount = 0;
		$total_seller_pending_amount = 0;
		$paid_amount = Payment::where('user_id',$seller_id)->where('type','deposit')->sum('amount');
		$todayDate = date('Y-m-d');
		$todayPaymentData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
              SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			order_metas.delivery_date = '$todayDate' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ");

		if($todayPaymentData){
			$tcsTax= 0;
			foreach($todayPaymentData as $td){
				$totalTodayAmount = $td->total;
				$totalTodayCommission = $td->total_admin_commission;
				$gstAmount =  ($totalTodayCommission * 18)/100;
				$totalAdminCmsn = $totalTodayCommission + $gstAmount;
				$tcsTax = 	($td->net_amount * 1)/100;
				$total_today_payable_amount += ($totalTodayAmount - $totalAdminCmsn - $tcsTax);
			}
		}
		$seller_total_amount = DB::select("SELECT order_metas.delivery_date as shippedDate, 
               SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			 order_metas.seller_id='$seller_id'  and (order_metas.status='delivered')
		 ");


		if($seller_total_amount){
			$tcsSTax =0;
			foreach($seller_total_amount as $tsm){
				$totalSAmount = $tsm->total;
				$totalSCommission = $tsm->total_admin_commission;
				$gstSAmount =  ($totalSCommission * 18)/100;
				$totalSAdminCmsn = $totalSCommission + $gstSAmount;
				$tcsSTax = 	($tsm->net_amount * 1)/100;
				$returnAmount = Helper::getSellerReturnPenaltyAmount($seller_id,$tsm->shippedDate);
				$exchangePenalty = Helper::getSellerExchangePenaltyAmount($seller_id,$tsm->shippedDate);
				$total_seller_payable_amount = ($totalSAmount - $totalSAdminCmsn - $tcsSTax);
				$total_seller_pending_amount = 	$total_seller_payable_amount - $paid_amount;
			}
		}
		$paymentData =['today_payemnt'=>round($total_today_payable_amount,2),'pending_amount'=>round($total_seller_pending_amount,2),'paid_amount'=>round($paid_amount,2)];
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Seller Amount',
			'error_message' => "Seller Amount",
			'data' => $paymentData,
		), 200);

	}
	//Paid payemet
	public function paid_payment_list(Request $request){
		$seller_id = $request->user_id;
		$payment_list=Payment::select('*',DB::raw("SUM(amount) as total_amount,SUM(commission) as total_commission"))->where('user_id',$seller_id)->where('type','deposit')->groupBy('order_date')->get();
		//$payment_list= $data->paginate(15);
		$list=array();
		$total=Payment::where('user_id',$seller_id)->where('type','deposit');
		$total= $total->sum('amount');
		foreach ($payment_list as $vs){
			$array= array();
			$array['order_date']=$vs->order_date;
			$array['total_commission']=round($vs->total_commission,2);
			$array['amount']=$vs->total_amount;
			$array['transaction_id']=$vs->transaction_id;
			$array['created_at']=date('d-m-Y',strtotime($vs->created_at));
			$list[]= $array;
		}
		$data = ['list'=>$list,'total_amount'=>$total];
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Paid payment list',
			'error_message' => "Paid payment list",
			'data' => $data,
		), 200);

	}
	public function paid_payment_details(Request $request){
		$orderDate = $request->order_date;
		$sellerId = $request->user_id;
		$paidAmount= Payment::whereDate('order_date','=',$orderDate)->where('user_id',$sellerId)->where('type','deposit')->first();

		$paid_amount=$paidAmount->amount;
		$adminCommission=$paidAmount->commission;
		$tcsCommission=$paidAmount->tcs_amount;
		//$payableAmount = $paid_amount - $adminCommission - $tcsCommission;
		$returnAmount = Helper::getSellerReturnPenaltyAmount($sellerId,$orderDate);
		$exchangePenalty = Helper::getSellerExchangePenaltyAmount($sellerId,$orderDate);
		$recCharge = $returnAmount + $exchangePenalty;

		//$orderSum = OrderMeta::select(DB::raw('sum(qty * price) AS total'))->where('delivery_date', $orderDate)->where('status','delivered')->where('seller_id',$sellerId)->get();
		//print_r($orderSum);die;
		$sum_data= OrderMeta::with('order')->where('seller_id',$sellerId)->where('delivery_date',$orderDate)->where('status','delivered')->groupBy('order_id');
		$sum=0;
		foreach($sum_data->get() as $vs)
		{
			$sum=$sum+($vs->qty * $vs->price);
		}
		$orderNetAmount = Order::whereDate('shipped_date','=', $orderDate)->where('status','delivered')->where('seller_id',$sellerId)->sum('net_amount');
		$tcs = round(($orderNetAmount* 1)/100 ,2);
		//To get delivered order
		$order_data= OrderMeta::with('order')->where('seller_id',$sellerId)->where('delivery_date',$orderDate)->where('status','delivered')->groupBy('order_id');
		$delivered_list= $order_data->get();
		//To get cancled order
		$cancelled_order= Order::with('order_meta_data')->where('status','cancelled')->where('seller_id',$sellerId)->whereDate('updated_at', '=', $orderDate)->get();
		/*..........Return Orders.......*/
		$return_data= OrderRmaDetail::with('order')->whereDate(DB::raw('DATE(order_rma_details.approved_date)'),'=', $orderDate)->WhereHas('order', function ($query) use($sellerId)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',$sellerId);
		})->where('is_approved',1);
		$return_data= $return_data->get();

		/*........Exchange Orders.........*/
		$exchange_data= OrderExchange::with('order')->where('approved_date', '=', $orderDate)->WhereHas('order', function ($query) use ($sellerId)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',$sellerId);
		})->where('is_approved',1);
		$exchange_data= $exchange_data->get();
		$data = ['order_amount'=>$sum,'tcs'=>$tcs,'rec_amount'=>$recCharge,'admin_commission'=>$adminCommission,'payable_amount'=>$paid_amount,'delivered_order'=>$delivered_list,'cancelled_order'=>$cancelled_order,'return_order'=>$return_data,'exchange_order'=>$exchange_data];
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Paid payment details',
			'error_message' => "Paid payment details",
			'data' => $data,
		), 200);
	}
	//pending payment api
	public function pending_payment_list(Request $request){
		$seller_id = $request->user_id;
		$pendindOrderDate = DB::select("SELECT order_metas.delivery_date as pending_order_date
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id 
			left join seller_payments on seller_payments.user_id= orders.seller_id
		WHERE 
			 order_metas.seller_id='$seller_id' and order_metas.status='delivered' and  NOT EXISTS
    		(SELECT * FROM seller_payments D2  WHERE D2.order_date = order_metas.delivery_date and D2.user_id='$seller_id' )
		GROUP BY
			(order_metas.delivery_date) ");

		foreach ($pendindOrderDate as $vs) {
			$totalPendingAmount = 0;
			$totalPendingNetAmount = 0;
			$totalAdminCmsn = 0;
			$tcsTax = 0;
			$orderData = Helper::getPendingOrderByDateNew($vs->pending_order_date, $seller_id);
			foreach ($orderData as $o_data) {
				$totalPendingAmount += $o_data->total;
				$totalPendingNetAmount += $o_data->net_amount;
				$totalCommission = $o_data->total_admin_commission;
				$gstAmount = ($totalCommission * 18) / 100;
				$totalAdminCmsn += $totalCommission + $gstAmount;
			}
			$tcsTax = ($totalPendingNetAmount * 1) / 100;
			$total_pending_payable_amount = ($totalPendingAmount - $totalAdminCmsn - $tcsTax);
			$array= array();
			$array['order_date']=$vs->pending_order_date;
			$array['total_commission']=$totalAdminCmsn;
			$array['tcs']=$tcsTax;
			$array['total_pending_amount']=$totalPendingAmount;
			$array['total_payable_amount']=$total_pending_payable_amount;
			$list[]= $array;
		}

		$data = ['list'=>$list];
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Pending payment list',
			'error_message' => "Pending payment list",
			'data' => $data,
		), 200);

	}
	public function pending_payment_details(Request $request){

		$orderDate = $request->order_date;
		$sellerId = $request->user_id;
		$returnAmount = Helper::getSellerReturnPenaltyAmount($sellerId,$orderDate);
		$exchangePenalty = Helper::getSellerExchangePenaltyAmount($sellerId,$orderDate);
		$recCharge = $returnAmount + $exchangePenalty;
		$todayPaymentData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
              SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			order_metas.delivery_date = '$orderDate' and order_metas.seller_id='$sellerId' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ");
		$total_today_payable_amount = 0;
		$totalTodayAmount = 0;
		$totalAdminCmsn = 0;
		$tcsTax = 0;
		if($todayPaymentData){
			foreach($todayPaymentData as $td){
				$totalTodayAmount = $td->total;
				$totalTodayCommission = $td->total_admin_commission;
				$gstAmount =  ($totalTodayCommission * 18)/100;
				$totalAdminCmsn = $totalTodayCommission + $gstAmount;
				$tcsTax = 	($td->net_amount * 1)/100;
				$total_today_payable_amount = ($totalTodayAmount - $totalAdminCmsn - $tcsTax);
			}
		}
		$order_data= OrderMeta::with('order')->where('seller_id',$sellerId)->where('delivery_date',$orderDate)->where('status','delivered')->groupBy('order_id');
		$order_list= $order_data->get();
		/*..........Return Orders.......*/
		$return_data= OrderRmaDetail::with('order')->whereDate(DB::raw('DATE(order_rma_details.approved_date)'),'=', $orderDate)->WhereHas('order', function ($query) use($sellerId)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',$sellerId);
		})->where('is_approved',1);
		$return_data= $return_data->get();

		/*........Exchange Orders.........*/
		$exchange_data= OrderExchange::with('order')->where('approved_date', '=', $orderDate)->WhereHas('order', function ($query) use ($sellerId)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',$sellerId);
		})->where('is_approved',1);
		$exchange_data= $exchange_data->get();
		$cancelled_order= Order::with('order_meta_data')->where('status','cancelled')->where('seller_id',$sellerId)->whereDate('updated_at', '=', $orderDate)->get();

		$data = ['order_amount'=>$totalTodayAmount,'tcs'=>$tcsTax,'rec_amount'=>$recCharge,'admin_commission'=>$totalAdminCmsn,'payable_amount'=>$total_today_payable_amount,'delivered_order'=>$order_list,'cancelled_order'=>$cancelled_order,'return_order'=>$return_data,'exchange_order'=>$exchange_data];
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Pending payment details',
			'error_message' => "Pending payment details",
			'data' => $data,
		), 200);
	}
	//pending payment api
	public function today_payment_list(Request $request){
		$seller_id = $request->user_id;
		$todayDate = date('Y-m-d');
		$todayPaymentData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
              SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			order_metas.delivery_date = '$todayDate' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ");

		$total_today_payable_amount = 0;
		$totalAdminCmsn = 0;
		if($todayPaymentData){
			$tcsTax = 0;
			foreach($todayPaymentData as $td){
				$totalTodayAmount = $td->total;
				$totalTodayCommission = $td->total_admin_commission;
				$gstAmount =  ($totalTodayCommission * 18)/100;
				$totalAdminCmsn = $totalTodayCommission + $gstAmount;
				$tcsTax = 	($td->net_amount * 1)/100;
				$total_today_payable_amount = ($totalTodayAmount - $totalAdminCmsn - $tcsTax);
				$array= array();
				$array['order_date']=$todayDate;
				$array['total_amount']=$totalTodayAmount;
				$array['total_commission']=$totalAdminCmsn;
				$array['tcs']=$tcsTax;
				$array['total_payable_amount']=$total_today_payable_amount;
				$list[]= $array;
			}
		}

		$data = ['list'=>$list];
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Today payment list',
			'error_message' => "Today payment list",
			'data' => $data,
			'total_amount'=>$total_today_payable_amount
		), 200);

	}
	public function today_payment_details(Request $request){

		$todayDate = date('Y-m-d');
		$seller_id = $request->user_id;
		$returnAmount = Helper::getSellerReturnPenaltyAmount($seller_id,$todayDate);
		$exchangePenalty = Helper::getSellerExchangePenaltyAmount($seller_id,$todayDate);
		$recCharge = $returnAmount + $exchangePenalty;
		$todayPaymentData = DB::select("SELECT order_metas.delivery_date as shippedDate, 
              SUM(order_metas.price * order_metas.qty) AS total,
              SUM(order_metas.product_commission) AS total_admin_commission,
              SUM(order_metas.net_amount) AS net_amount
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			order_metas.delivery_date = '$todayDate' and order_metas.seller_id='$seller_id' and (order_metas.status='delivered' or order_metas.status='return' or order_metas.status='exchange')
		GROUP BY
			(order_metas.delivery_date) ");
		$total_today_payable_amount = 0;
		$totalTodayAmount = 0;
		$totalAdminCmsn = 0;
		if($todayPaymentData){
			$tcsTax =0;
			foreach($todayPaymentData as $td){
				$totalTodayAmount = $td->total;
				$totalTodayCommission = $td->total_admin_commission;
				$gstAmount =  ($totalTodayCommission * 18)/100;
				$totalAdminCmsn = $totalTodayCommission + $gstAmount;
				$tcsTax = 	($td->net_amount * 1)/100;
				$total_today_payable_amount = ($totalTodayAmount - $totalAdminCmsn - $tcsTax);
			}
		}
		$order_data= OrderMeta::with('order')->where('seller_id',$seller_id)->where('delivery_date',$todayDate)->where('status','delivered')->groupBy('order_id');

		$order_list= $order_data->get();

		/*..........Return Orders.......*/
		$return_data= OrderRmaDetail::with('order')->whereDate(DB::raw('DATE(order_rma_details.approved_date)'),'=', $todayDate)->WhereHas('order', function ($query) use($seller_id)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',$seller_id);
		})->where('is_approved',1);
		$return_data= $return_data->get();

		/*........Exchange Orders.........*/
		$exchange_data= OrderExchange::with('order')->where('approved_date', '=', $todayDate)->WhereHas('order', function ($query) use ($seller_id)
		{
			$query->where('shipped_date',"!=","0000-00-00")->where('seller_id',$seller_id);
		})->where('is_approved',1);
		$exchange_data= $exchange_data->get();
		$cancelled_order= Order::with('order_meta_data')->where('status','cancelled')->where('seller_id',$seller_id)->whereDate('updated_at', '=', $todayDate)->get();
		$data = ['order_amount'=>$totalTodayAmount,'tcs'=>$tcsTax,'rec_amount'=>$recCharge,'admin_commission'=>$totalAdminCmsn,'payable_amount'=>$total_today_payable_amount,'delivered_order'=>$order_list,'cancelled_order'=>$cancelled_order,'return_order'=>$return_data,'exchange_order'=>$exchange_data];
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Today payment details',
			'error_message' => "Today payment details",
			'data' => $data,
		), 200);
	}
	public function payment_order_details(Request $request){
		$order_id = $request->order_id;
		$seller_id = $request->user_id;
		$list=array();
		$sum=0;
		$netSum=0;
		$orders = OrderMeta::with('order')->where('seller_id',$seller_id)->where('order_id',$order_id)->get();
		$order_info = Order::with('address')->where('id',$order_id)->first();
		$totalItem = Helper::get_number_of_product($order_info->id,$seller_id);
		foreach ($orders as $vs){
			$array= array();
			$array['id']=$vs->order_id;
			$array['order_id']=$vs->order->order_id;
			$array['status']=$vs->order->status;
			$array['created_at']=date('d-m-Y',strtotime($vs->order->created_at));
			$array['name']= $vs->product_name;
			$array['qty']= $vs->qty;
			$array['price']= $vs->price;
			$array['weight']= $vs->weight;
			$array['product_image']=$vs->product_image;
			$sum= $vs->order->total_amount;
			$netSum= $vs->order->net_amount;

			$list[]= $array;
		}
		$commission= $order_info->admin_commission;
		$withGstCommission= ($order_info->admin_commission *18)/100; //18% gst on grocito commission amount.
		$totalGstCmsn = $commission +$withGstCommission;
		$tcs_amount = ($netSum * 1)/100;//tcs tax 1% on total amount.
		$admnTtlAmnt = $totalGstCmsn +$tcs_amount;
		return Response::json(array(
			'status_code' => 1,
			'message' => 'Seller Amount',
			'error_message' => "Seller Amount",
			'order_amount' => $order_info->total_amount,
			'gst_amount' => $totalGstCmsn,
			'total_amount' => $order_info->total_amount - $admnTtlAmnt,
			'shipped_date' => $order_info->shipped_date,
			'delivery_type' => $order_info->delivery_type,
			//'shipping_charge' => $order_info->shipping_charge,
			'payment_mode' => $order_info->payment_mode,
			'total_item' => $totalItem,
			//'delivery_date' => $order_info->delivery_date,
			//'delivery_time' => $order_info->delivery_time,
			'data' => $list,
			'delivery_address' => $order_info->address,

		), 200);
	}
	//******* NOtice List *****************//
	public function notice_list(){
		$list = Notice::get();
		if($list){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Notice List',
				'error_message' => "Notice List",
				'data' => $list,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Notice List',
				'error_message' => "Notice List",
				'data' => [],
			), 200);
		}
	}
	public function notification_list(Request $request){
		$seller_id = $request->user_id;
		$notify_count= SellerNotification::where('seller_id',$seller_id)->where('status',0)->get()->count();
		$After7Days = \Carbon\Carbon::today()->addDays(7);
		$sellerNotification= SellerNotification::where('seller_id',$seller_id)->whereDate('created_at','<=',$After7Days)->get();
		$data =[];
		foreach($sellerNotification as $vs){
			if($vs->type =='product_verify'){
				$productData = Helper::getProductById($vs->int_val);
				if($productData){
					$title = $productData->name;
				}else{
					$title = '';
				}

			}
			if($vs->type =='order_placed'){
				$orderData = Helper::getOrderById($vs->int_val);
				if($orderData){
					$title = $orderData->order_id;
				}else{
					$title ='';
				}

			}
			$data[]=['message'=>$title.' '.$vs->message,'created_at'=>date('d-m-Y h:m:i',strtotime($vs->created_at))];
		}

		if($sellerNotification){
			return Response::json(array(
				'status_code' => 1,
				'message' => 'Notification List',
				'error_message' => "Notification List",
				'data' => $data,
				'notify_count' => $notify_count?$notify_count:0,
			), 200);
		}else{
			return Response::json(array(
				'status_code' => 0,
				'message' => 'Notification List',
				'error_message' => "Notification List",
				'data' => [],
				'notify_count' => 0,
			), 200);
		}

	}
	public function update_notification_status(Request $request){
		$seller_id = $request->input('user_id');
		$update = DB::table('seller_notifications')->where('seller_id', $seller_id)->update(['status' => 1]);
		return Response::json(array(
				'status_code' => 1,
				'message' => 'Update successfully',
			), 200);

	}
    //update profile....................................................................................
    public function update_profile(Request $request)
    {
         $profile = array(
            'username' => $request->input('name'),
            'city' => $request->input('city'),
            'id' => $request->input('user_id'),
         );
        $rules = array();
        $validator = Validator::make($profile, $rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message' => $validator->errors()->first(),
            ), 200);
        } else {

            $data = $request->all();
            $profile = User::findOrFail($request->input('user_id'));
            $profile->fill($data)->save();
            if(!empty($_FILES['user_image']['name'])){
            $image = $request->file('user_image');
                $path_original = public_path() . '/admin/uploads/user';
                $file = $request->user_image;
                $photo_name = time(). '-' .$file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $userProfile['profile_image'] = $photo_name;
            }
            $userProfile['city']=$request->input('city');
            DB::table('user_profiles')->where('user_id', $request->input('user_id'))->update($userProfile);

            return Response::json(array(
                'status_code' => 1,
                'message' => 'Successfully Updated',
                'error_message' => "Saved successfully",
                'user_image_path' => url('/')."/public/admin/uploads/user/",
                'data' => User::with('user_profile')->find($request->input('user_id')),
            ), 200);
        }
    }
	function imagettfstroketext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px) {
		for($c1 = ($x-abs($px)); $c1 <= ($x+abs($px)); $c1++)
			for($c2 = ($y-abs($px)); $c2 <= ($y+abs($px)); $c2++)
				$bg = imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
		return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
	}
	// Function to add text water mark over image
	public function addTextWatermark($src, $watermark, $save=NULL) {
		list($width, $height) = getimagesize($src);
		$image_color = imagecreatetruecolor($width, $height);
		$gray = imagecolorallocate($image_color, 0xDD, 0xDD, 0xDD);
		$image = imagecreatefromjpeg($src);
		imagecopyresampled($image_color, $image, 0, 0, 0, 0, $width, $height, $width, $height);
		$txtcolor = imagecolorallocate($image_color, 230, 225, 217);
		$font = realpath("public/front/image/MONOFONT.ttf");
		$font_size = 10;
		$bbox = imagettfbbox($font_size, 0, $font, $watermark);
		$x = $bbox[0] + (imagesx($image)) - ($bbox[4] / 2) - 130;
		$y = $bbox[1] + (imagesy($image)) - ($bbox[5] / 2) - 30;
		//imagettftext($image_color, $font_size, 0, $x, $y, $txtcolor, $font, $watermark);
		//imagefttext($image_color, 30, 0, $x, $y, $gray, $font, $watermark);
		$font_color = imagecolorallocate($image_color, 255, 255, 255);
		$stroke_color = imagecolorallocate($image_color, 0, 0, 0);
		$this->imagettfstroketext($image_color, 20, 10, $x, $y, $font_color, $stroke_color, $font, $watermark, 2);

		if ($save<>'') {
			imagejpeg ($image_color, $save, 100);
		} else {
			header('Content-Type: image/jpeg');
			imagejpeg($image_color, null, 100);
		}
		imagedestroy($image);
		imagedestroy($image_color);
	}
	// Compress image
	function compressImage($source, $destination, $quality) {

		$info = getimagesize($source);

		if ($info['mime'] == 'image/jpeg')
			$image = imagecreatefromjpeg($source);

		elseif ($info['mime'] == 'image/gif')
			$image = imagecreatefromgif($source);

		elseif ($info['mime'] == 'image/png')
			$image = imagecreatefrompng($source);

		imagejpeg($image, $destination, $quality);

	}
	///END////////////////
}
?>