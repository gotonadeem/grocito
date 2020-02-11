<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use App\Category;
use Carbon\Carbon;
use App\Wishlist;
use DB;
use URL;
use Helper;
use Excel;
use Auth;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
class WishlistApiController extends Controller
{
	public function __construct()
	{
	  parent::__construct();
	}
	
	/*..................add to cart...................................................................*/
	 public function add_to_wishlist(Request $request)
     {
		 $users = array(
            'product_id'  => $request->input('product_id'),
            'user_id'     => $request->input('user_id'),
            'size'         =>$request->input('size'),
        );
		
        $rules = array(
            'product_id' =>   'required',
            'user_id'    =>   'required',
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
			
			$cartQuery=Wishlist::where('user_id',$request->input('user_id'))->where('product_id',$request->input('product_id'));
			if($cartQuery->count()>0)
			{
                 return Response::json(array(
                'status_code' => 2,
                'message' => 'Product is already in wishlist',
                'error_message'=>"Product is already in wishlist",
                ), 200);
			}
			else
			{
              $user = new Wishlist($users);
              $user->save();
				  return Response::json(array(
					'status_code' => 1,
					'message' => 'Product is added into wishlist',
					'error_message'=>"Product is added into wishlist",
				), 200);
			}
            
        }
    }
	
	public function delete_wishlist(Request $request)
	{
		 $users = array(
            'user_id'  => $request->input('user_id'),
            'product_id'  => $request->input('product_id'),
			  );
		
        $rules = array(
            'user_id' =>   'required',
            'product_id' =>   'required',
	       );
		    $validator = Validator::make($users,$rules);
        if ($validator->fails()) {
            return Response::json(array(
                'status_code' => 0,
                'message' => 'validation error',
                'error_message'=>$validator->errors()->first(),
            ), 200);
        }else{
	      $wishlist = Wishlist::where('user_id', $users['user_id'])->where('product_id',$users['product_id']);
	      $wishlist->delete(); 
		   return Response::json(array(
                'status_code' => 1,
                'message' => 'Wishlist has been deleted',
                'error_message'=>"Wishlist has been deleted",
            ), 200);
		}
	}
       /*..................get wishlist...................................................................*/
	public function get_wishlist(Request $request)
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
			$cartList= Wishlist::with('product','product_image')->where('user_id',$request->input('user_id'))->get();
			$cartCount= Wishlist::where('user_id',$request->input('user_id'))->get()->count();
			
			return Response::json(array(
                'status_code' => 1,
                'message' => 'List of Product',
                'data' => $cartList,
                'count' => $cartCount,
                'error_message'=>"List of Product in wishlist",
				'product_image_path'=>url('/').'/public/admin/uploads/product/',
            ), 200);
        }
    }
}