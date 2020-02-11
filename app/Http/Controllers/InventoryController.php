<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use DB;
use Session;
use Helper;
use App\Product;
use App\ProductItem;
use App\CategoryStockStatus;
class InventoryController extends Controller
{
    public function __construct()
    {
     parent::__construct();
    }
    public function index()
    {
		if(Auth::user())
		{
			$catatlog_list = DB::table('categories')
		->join('products', 'categories.id', '=', 'products.category_id')
		->where('products.status', '=', 1)
		->where('products.stock_status', '=', 1)
		->where('products.user_id', '=', Auth::user()->id)
		->where('products.is_admin_approved',1)
		->groupBy('products.category_id')
		->select('categories.*',DB::raw("products.id as product_id"),DB::raw("count(products.id) as count"))
		->get();
		  return view('seller.inventory.index',compact('catatlog_list'));
		}
		else
		{
			return redirect('/');
		}
        
    }
	
	function change_category_status(Request $request)
	{
		 if(Auth::user())
		 {
		    $id=$request->input('id');  
		    $type=$request->input('type');
            $user_id= Auth::user()->id; 
			if($type=="in_stock")
			{
				$status=CategoryStockStatus::where('category_id',$id)->delete();
				if($status)
				{
					 Session::flash('success_message', 'Marked in stock successfully');
					 echo json_encode(array('status'=>true));
				}
			}
			if($type=="out_stock")
			{
				$data['category_id']=$id;
				$data['user_id']=$user_id;
				$status= new CategoryStockStatus($data);
				$status->save();
				if($status)
				{
					 Session::flash('success_message', 'Marked out of stock successfully');
					 echo json_encode(array('status'=>true));
				}
			}				
		 }
		 else
		 {
			 echo json_encode(array('not_login'=>true));
		 }
	}
	
	function change_product_status(Request $request)
	{
		 if(Auth::user())
		 {
		    $id=$request->input('id');
			$sellerId = Auth::user()->id;

		    $type=$request->input('type');

			if($type=="in_stock")
			{
				 $product_info['stock_status']=1;
				 $update_data = Product::find($id)->fill($product_info);
					if($update_data->update())
					{
						 Session::flash('success_message', 'Product Marked in stock successfully');
						 echo json_encode(array('status'=>true));
					}
			}
			if($type=="out_stock")
			{
				 $product_info['stock_status']=0;
				 $update_data = Product::find($id)->fill($product_info);
					if($update_data->update())
					{
						//update product item......
						DB::table('product_items')->where('product_id', $id)->where('seller_id',$sellerId)->update(['qty' => 0]);
						 Session::flash('success_message', 'Product Marked out of stock successfully');
						 echo json_encode(array('status'=>true));
					}
			}				
		 }
		 else
		 {
			 echo json_encode(array('not_login'=>true));
		 }
	}
	function getProductItem(Request $request)
	{
		$item_details=ProductItem::where('product_id',$request->input('id'))->where('seller_id',Auth::user()->id)->get();
		return view('seller.inventory.catalog_item_ajax',compact('item_details'));
	}
	function updateItemQty(Request $request){
		$product_id = $request->input('product_id');
		$qty= json_decode($request->input('qty'));
		$item_id= json_decode($request->input('item_id'));
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
				Session::flash('error_message', 'Product Marked out of stock unsuccessfull');
				echo json_encode(array('status'=>false));
			}

		}
		$product_info['stock_status']=1;
		$update_data = Product::find($product_id)->fill($product_info);
		if($update_data->update())
		{
			Session::flash('success_message', 'Product Marked in stock successfully');
			echo json_encode(array('status'=>true));
		}
	}
	function product_list($id)
	{
		  $catatlog_list=Product::with('product_image','main_category','sub_category','super_sub_category')->where('status',1)->where('is_admin_approved',1)->where('category_id',$id)->where('stock_status',1)->where('user_id',Auth::user()->id)->orderBy('id','desc')->get();	
	      return view('seller.inventory.product_list',compact('catatlog_list'));
	}
	
	function product_list_out_of_stock($id)
	{
		  $catatlog_list=Product::with('product_image','main_category')->where('status',1)->where('category_id',$id)->where('stock_status',0)->where('user_id',Auth::user()->id)->orderBy('id','desc')->get();
		  return view('seller.inventory.product_list_out_of_stock',compact('catatlog_list'));
	}
	
	function out_of_stock()
	{
		
		$catatlog_list = DB::table('categories')
		->join('products', 'categories.id', '=', 'products.category_id')
		->where('products.status', '=', 1)
		->where('products.stock_status', '=', 0)
		->where('products.user_id', '=', Auth::user()->id)
		->groupBy('products.category_id')
		->select('categories.*',DB::raw("products.id as product_id"),DB::raw("count(products.id) as design"))
		->get();
		  return view('seller.inventory.out_of_stock_list',compact('catatlog_list'));
	}
	
}