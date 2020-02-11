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
use App\Country;
use App\State;
use App\UserProductShare;
use App\City;
use App\Category;
use App\GeneralSetting;
use App\SubCategory;
use App\Product;
use App\CategoryNotification;
use App\ProductRating;
use DB;
use URL;
use Helper;
use Excel;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class HomepageApiController extends Controller
{
	public function __construct()
	{
	  parent::__construct();
	  
	}
	
    public function home_api(Request $request){
        $count =  Slider::where('status',1)->get()->count();
        $data =   Slider::where('status',1)->get();
		$featured_product= Product::with('product_image')->where('is_featured',1)->where('is_admin_approved',1)->orderBy('id','desc')->take(20)->get();
		$category= Category::with("main_category","main_category.super_sub_cat")->get();
		//$product= Product::with("main_category")->get();
		$product= Category::with('product','product.product_image')->whereHas('product', function ($query)
            {
                $query->take(10);
            })->get();
		
     	$slider_url=  url('/').'/public/admin/uploads/slider_image/'; 
		$category_url= url('/').'/public/admin/uploads/category/';
		$catalog_url= url('/').'/public/uploads/admin/product/';
		if($count>0) {
            return Response::json(array('status' => 1,'slider_count'=>$count,'img_slider_path'=>$slider_url,'img_category_path'=>$category_url,'img_catalog_path'=>$catalog_url,'slider'=>$data ,'category'=>$category,'home_product'=>$product,'featured_product'=>$featured_product), 200);
        }
        else
        {
            return Response::json(array('status' => 0,'slider'=>array(),'category'=>array()), 200);
        }
    }

    public function get_child_category_api(Request $request){
		$category_id= $request->input('category_id');
	    $category_filter= SubCategory::with('super_sub_cat')->where('category_id',$category_id)->get();
	    if(count($category_filter)>0)
		{
		return Response::json(array('status' => 1,'message'=>'List of category','category'=>$category_filter), 200);
		}
		else
		{
		  return Response::json(array('status' =>0,'message'=>'not found','category'=>array()), 200);
		}
	 }
	
	 public function get_product(Request $request){
			     $page= $request->input('page');
				 $limit=10;
				 $skip=$page-1;
				 $offset= $limit * $skip;
				  $product_list = Product::with('product_image','user_name','product_rating')->where('status',1)->where('stock_status',1)->where('is_admin_approved',1)->where('super_sub_category_id', '=', $request->input('super_sub_category_id'))->orderBy('created_at', 'asc')->orderBy('id', 'asc')->skip($offset)->take($limit)->get(); //
				
				 
				$jsonData=array();
				foreach($product_list as $vs)
				{
					 $json=array();
					 $json['id']=$vs->id;
					 $json['name']=$vs->name;
					 $json['description'] = $vs->description;
					 $json['weight'] = $vs->weight;
					 $json['price'] = $vs->starting_price;
					 $json['is_return'] = $vs->is_return;
					 $json['is_cod'] = $vs->is_cod;
					 $json['sell_price'] = (($vs->is_shipping_free==1)?$vs->sell_price+$vs->shipping_free_amount:$vs->sell_price);
					 $json['is_shipping_free'] = $vs->is_shipping_free;
					 $json['product_rating'] = $vs->product_rating->avg('rating');
					 $json['image'] = ((count($vs->product_image)>0)?$vs->product_image[0]->image:"");
					 $json['catalog_images'] = Helper::get_catalog_images($vs->id);
					 $jsonData[]= $json;
				}			
			
				$catalog_url= url('/').'/public/admin/uploads/product/';
				if(sizeof($product_list)>0) {
					return Response::json(array('status' => 1,'catalog'=>$jsonData,'url'=>$catalog_url), 200);
				}
				else
				{
					return Response::json(array('status' => 0,'category_data'=>array(),'catalog'=>array()), 200);
				}
		 }
		 
		 
		  public function get_product_by_home_category(Request $request){
			     $page= $request->input('page');
				 $limit=10;
				 $skip=$page-1;
				 $offset= $limit * $skip;
				  $product_list = Product::with('product_image','user_name','product_rating')->where('status',1)->where('stock_status',1)->where('is_admin_approved',1)->where('category_id', '=', $request->input('category_id'))->orderBy('created_at', 'asc')->orderBy('id', 'asc')->skip($offset)->take($limit)->get(); //
				
				 
				$jsonData=array();
				foreach($product_list as $vs)
				{
					 $json=array();
					 $json['id']=$vs->id;
					 $json['name']=$vs->name;
					 $json['description'] = $vs->description;
					 $json['weight'] = $vs->weight;
					 $json['price'] = $vs->starting_price;
					 $json['is_return'] = $vs->is_return;
					 $json['is_cod'] = $vs->is_cod;
					 $json['sell_price'] = (($vs->is_shipping_free==1)?$vs->sell_price+$vs->shipping_free_amount:$vs->sell_price);
					 $json['is_shipping_free'] = $vs->is_shipping_free;
					 $json['product_rating'] = $vs->product_rating->avg('rating');
					 $json['image'] = ((count($vs->product_image)>0)?$vs->product_image[0]->image:"");
					 $json['catalog_images'] = Helper::get_catalog_images($vs->id);
					 $jsonData[]= $json;
				}			
			
				$catalog_url= url('/').'/public/admin/uploads/product/';
				if(sizeof($product_list)>0) {
					return Response::json(array('status' => 1,'catalog'=>$jsonData,'url'=>$catalog_url), 200);
				}
				else
				{
					return Response::json(array('status' => 0,'category_data'=>array(),'catalog'=>array()), 200);
				}
		 }
	
	public function get_product_details(Request $request)
	{
		
		 $product = array(
             'id'    =>$request->input('id'),
        );
        $rules = array(
             'id'    =>     'required',
            );
        $validator = Validator::make($product,$rules);
        if ($validator->fails()) {           
             return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
            
        }else{
			$vs = Product::with('product_image')->where('status',1)->where('is_admin_approved',1)->where('id',$request->input('id'))->first();
             $setting=Helper::get_setting();
			 $wallet_deduction= $vs->sell_price*$setting->wallet_deduction/100;
				        $jsonData=array();
						$wallet_amount= ($vs->a_sell_price*$vs->w_commission/100);
						
		                 $json['id']=$vs->id;
		 				 $json['name']=$vs->name;
		 				
						 $json['description'] = $vs->description;
						 $json['user_id'] = $vs->user_id;
						 $json['price'] = (($vs->is_shipping_free==1)?$vs->starting_price+$vs->shipping_free_amount:$vs->starting_price);
						 $json['sell_price'] = (($vs->is_shipping_free==1)?$vs->sell_price+$vs->shipping_free_amount:$vs->sell_price);
						 $json['is_shipping_free'] = $vs->is_shipping_free;
						 $json['is_return'] = $vs->is_return;
						 $json['is_cod'] = $vs->is_cod;
						 $json['wallet_pay']=$wallet_amount;
		 				 $json['online_pay']= $json['sell_price']-$wallet_amount;
						 $json['size'] = $vs->size;
						 $json['stock_status'] = $vs->stock_status;
						 $json['weight'] = $vs->weight;
						 $json['catalog_images'] = Helper::get_catalog_images($vs->id);
						 $jsonData= $json;
						    return Response::json(array(
							'status_code' => 1,
							'catalog_details' => $jsonData,
						), 200);
		}
	}
	
	
	 public function get_share_product(Request $request){
		    
			 $page= $request->input('page');
				 $limit=10;
				 $skip=$page-1;
				 $offset= $limit * $skip;
			      //echo $limit."<br>";
			      //echo $offset."<br>";
				  
				$product_list = UserProductShare::with('product')->where('user_id',$request->input('user_id'))->orderBy('created_at','desc')->skip($offset)->take($limit);
				$jsonData=array();
				 
				$data=$product_list->get(); 
				if($product_list->get()->count()>0)
				{
					$product_list=$data;
					foreach($product_list as $vs)
					{
						if(!is_null($vs->product))
						{
						 $json=array();
						 $json['id']=!is_null($vs->product)?$vs->product->id:'';
						 $json['name']=!is_null($vs->product)?$vs->product->name:'';
						 $json['description'] =!is_null($vs->product)?$vs->product->description:'';
						 $json['price'] = !is_null($vs->product)?$vs->product->starting_price:'';
						 $json['sell_price'] = !is_null($vs->product)?$vs->product->sell_price:'';
						 $json['weight'] = !is_null($vs->weight)?$vs->product->weight:0.00;
						 $json['is_return'] = !is_null($vs->product)?$vs->product->is_return:'';
						 $json['is_cod'] = !is_null($vs->product)?$vs->product->is_cod:'';
						 $json['is_shipping_free'] = !is_null($vs->is_shipping_free)?$vs->product->is_shipping_free:0;
						 $json['image'] = !is_null($vs->product)?$vs->product->image:''; //((count($vs->product_image)>0)?$vs->product_image[0]->image:"");
                          if(!is_null($vs->product))
						  {							  
						    $json['catalog_images'] = Helper::get_catalog_images($vs->product->id);
						  }
						 $jsonData[]= $json;
						}
					}			
				$catalog_url= url('/').'/public/uploads/seller/catalog/';
					return Response::json(array('status' => 1,'img_catalog_path'=>$catalog_url, 'catalog'=>$jsonData), 200);
			   }
			   else
			   {
			   return Response::json(array('status' => 0,'catalog'=>array()), 200);
			   }
		 
    }
	
	
	 
	
	 
	
	
	  public function join_us(Request $request)
     {
		  
		 $users=array(
		    'mobile'    => $request->input('mobile'),
            'email'    => $request->input('email'),
		    'username'     => $request->input('username'),
		    'role_id'     => 2,
          
		  );
		 
        $user_kyc = array(
            'f_name'     => $request->input('f_name'),
            'l_name'     => $request->input('l_name'),
            'gender'     => $request->input('gender'),
            'country_id' => $request->input('country_id'),
            'city_id'    => $request->input('city_id'),
            'state_id'   => $request->input('state_id'),
            'address_1'    => $request->input('address'),
            'pincode'    => $request->input('pincode'),
        );
		
        $rules = array(
            'username'   =>   'required|unique:users,username',
            'mobile'     =>   'required|unique:users,mobile',
            'email'      =>   'required|unique:users,email',
            'f_name'     =>   'required',
            'l_name'     =>   'required',
            'pincode'    =>   'required',
            'address'    =>   'required',
            'city_id'    =>   'required',
            'state_id'   =>   'required',
            'country_id' =>   'required',
        );
		
        $validator = Validator::make(Input::all(),$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
            $user = new User($users);
            $user->save();
			$user_kyc['user_id']= $user->id;
			$userkyc = new UserKyc($user_kyc);
            $userkyc->save();
            return Response::json(array(
                'status_code' => 1,
                'message' => 'You request has been submitted successfully.We will reach you shortly',
                'error_message'=>"You request has been submitted successfully.We will reach you shortly",
            ), 200);
        }
    }
	
	function get_country()
	{
	   	  $data=Country::get();
		  return Response::json(array(
                'status' => 1,
                'message' => 'List of country',
                'country_list' => $data,
            ), 200);
	}
	
	function get_state(Request $request)
	{
	   	  $data=State::where('country_id',$request->country_id)->get();
		  return Response::json(array(
                'status' => 1,
                'message' => 'List of state',
                'state_list' => $data,
            ), 200);
	}
	
	function get_city(Request $request)
	{
	   	  $data=City::where('state_id',$request->state_id)->get();
		  return Response::json(array(
                'status' => 1,
                'message' => 'List of city',
                'city_list' => $data,
            ), 200);
	}
	
	function get_new_update_thumbnail()
	{
	   	  $data= GeneralSetting::select('special_image','popular_image2','deal_of_the_day_image','more_image')->first();
		  
		  return Response::json(array(
                'data' => $data,
                'path' => url('/').'/public/admin/uploads/general_setting/',
            ), 200);
	}
	
	 public function get_special_product(Request $request){
		   $jsonData=array();
		   $count =  Slider::where('status',1)->get()->count();
           $data =   Slider::where('status',1)->get();
		   $colname = date("Y-m-d");
           $query = DB::table('products')
						 ->join('product_sponsors', 'products.id', '=', 'product_sponsors.product_id')
						 ->join('product_images', 'products.id', '=', 'product_images.product_id')
						 ->leftJoin('product_ratings', 'products.id', '=', 'product_ratings.product_id')
						 ->select("products.*",DB::raw("avg(product_ratings.rating) as product_rating"))
						 ->whereRaw('FIND_IN_SET(?,product_sponsors.date)', [$colname])
						 ->where('product_sponsors.admin_status', 1);
		    if($request->input('condition')=="all")
			{
									 $query=$query->groupBy('product_images.product_id');
			}
			elseif($request->input('condition')=="main_category_id")
			{
				 $id= $request->input('main_category_id');
				 $query=$query->groupBy('product_images.product_id')
				                              ->where('products.category_id',$id);
													 
			}
			elseif($request->input('condition')=="sub_category_id")
			{
				 $id= $request->input('sub_category_id');
				 $query=$query->groupBy('product_images.product_id')
				                              ->where('products.sub_category_id',$id);
													 
			}
            $product_list=$query->inRandomOrder()->get();			
				
					foreach($product_list as $vs)
					{
						
						 
						 $json=array();
						 $json['id']=$vs->id;
						 $json['name']=$vs->name;
						 $json['description'] = $vs->description;
						 $json['links'] = $vs->links;
						 $json['price'] = $vs->starting_price;
						 $json['weight'] = $vs->weight;
						 $json['sell_price'] = (($vs->is_shipping_free==1)?$vs->sell_price+$vs->shipping_free_amount:$vs->sell_price);
						 $json['product_rating'] = $vs->product_rating;
						 $json['is_return'] = $vs->is_return;
						 $json['is_cod'] = $vs->is_cod;
						 $json['is_shipping_free'] = $vs->is_shipping_free;
						 $json['image'] = $vs->image;
						 $json['catalog_images'] = Helper::get_catalog_images($vs->id);
						 $jsonData[]= $json;
					}			
				
					$catalog_url= url('/').'/public/uploads/seller/catalog/';
					$slider_url=  url('/').'/public/admin/uploads/slider_image/';
				    return Response::json(array('status' => 1,'slider_count'=>$count,'img_slider_path'=>$slider_url,'img_catalog_path'=>$catalog_url,'slider'=>$data,'catalog'=>$jsonData), 200);	
    }
	
	
	public function search_product(Request $request){
            $keyword= $request->input('search_query');
			
			$product_list = DB::table('products')
									 ->join('product_images', 'products.id', '=', 'product_images.product_id')
									 ->leftJoin('product_ratings', 'products.id', '=', 'product_ratings.product_id')
									 ->select("products.*",'product_images.image as image',DB::raw("avg(product_ratings.rating) as product_rating"))
									 ->groupBy('product_images.product_id');				 
		    if($request->input('condition')=="all")
			{
					$product_list->where('products.name', 'like', "%". $keyword . '%');
			}
			elseif($request->input('condition')=="main_category_id")
			{
				$id= $request->input('main_category_id');
				$product_list=$product_list->where('products.category_id',$id)->where('products.name', 'like', "%". $keyword . '%');
			}
            $product_list=$product_list->get();	
			   
					foreach($product_list as $vs)
					{
						
						 
						 $json=array();
						 $json['id']=$vs->id;
						 $json['name']=$vs->name;
						 $json['description'] = $vs->description;
						 $json['price'] = $vs->starting_price;
						 $json['weight'] = $vs->weight;
						 $json['sell_price'] = (($vs->is_shipping_free==1)?$vs->sell_price+$vs->shipping_free_amount:$vs->sell_price);
						 $json['image'] = $vs->image;
						 $json['is_shipping_free'] = $vs->is_shipping_free;
						 $json['is_return'] = $vs->is_return;
						 $json['is_cod'] = $vs->is_cod;
						 $json['product_rating'] = $vs->product_rating;
						 $json['catalog_images'] = Helper::get_catalog_images($vs->id);
						 $jsonData[]= $json;
					}			
				
					$catalog_url= url('/').'/public/uploads/seller/catalog/';
					$slider_url=  url('/').'/public/admin/uploads/slider_image/';
					if(sizeof($product_list)>0) {
						return Response::json(array('status' => 1,'img_catalog_path'=>$catalog_url,'catalog'=>$jsonData), 200);
					}
					else
					{
						return Response::json(array('status' => 0,'category_data'=>array(),'catalog'=>array()), 200);
					}
			
			
    }
	
	
	
}
?>