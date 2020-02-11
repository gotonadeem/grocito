<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Slider;
use DB;
use App\GeneralSetting;
use App\SubCategory;
use App\Dip;
use App\Gallery;
use App\Category;
use App\Product;
use App\ProductItem;
use App\Banner;
use URL;
class ProductListingController extends Controller
{

    public function __construct()
    {
       parent::__construct();
    }
    public function index(Request $request)
    {
        $categorySlug= $request->segment(2);
        $sub_category= $request->segment(3);
        $subCatId = '';
        $catData = Category::getCatBySlug($categorySlug);
        $catId = $catData->id;
        $category_filter= SubCategory::with('super_sub_cat')->where('category_slug',$categorySlug)->get();
        $brand_filter= Product::with('brand')->where('category_slug',$categorySlug);
        if($sub_category)
        {
            $brand_filter=$brand_filter->where('sub_category_slug',$sub_category);
            $subCatData = SubCategory::getSubCatBySlug($sub_category);
            if($subCatData){
                $subCatId = $subCatData->id;
            }
            
        }
        $brand_filter=$brand_filter->groupBy('brand_id')->get();
        return view('front.product.index',compact('catId','subCatId','catData','category_filter','brand_filter'));
    }
    public function getProductPrice(Request $request){
        $productItemId = $request->input('priceId');
        $data = ProductItem::with('scheme_product','product','product_image')->where('id',$productItemId)->first();
       
        if($data){
            if($data->scheme_product && $data->scheme_product->offer_name){
                $schemeName = $data->scheme_product->offer_name;
            }else{
                $schemeName = $data->product->name;
            }
            if($data->scheme_product && $data->scheme_product->image){
                $productImagePath = URL::asset('public/admin/uploads/scheme_product/'.$data->scheme_product->image);
            }else{
                $productImagePath = URL::asset('public/admin/uploads/product/'.$data->product_image->image);
            }
            echo json_encode(array('status'=>true,'message'=>'successfully','offer'=>$data->offer,'qty'=>$data->qty,'price'=>$data->price,'sprice'=>$data->sprice,'scheme_name'=>$schemeName,'image_path'=>$productImagePath));
        }else{
            echo json_encode(array('status'=>false,'message'=>'Sorry, record not found.'));
        }
    }
    //get product list with filter.....
    function filter_product(Request $request)
    {
        $sessionPincode = session('pincode');
        $brand=$request->input('brand');
        $min_price=$request->input('min_price');
        $offer=$request->input('offer');
        if($offer)
        {
            $offerValue= explode("-",$offer[0]);
            $min_offer= $offerValue[0];
            $max_offer= $offerValue[1];
        }
        $max_price=$request->input('max_price');
        $cat=$request->input('cat_url');
        $sCat=$request->input('s_cat_url');
        $query = \DB::table("user_kyc")
            ->join('products', 'products.user_id', '=', 'user_kyc.user_id')
            ->join('product_images', 'product_images.product_id', '=', 'products.id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->select('products.*','product_images.image','brands.name as brand_name')
            ->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
            ->where('products.category_slug',$cat)
            ->where('products.is_admin_approved',1)
            ->groupBy('products.id')->distinct('products.id');

        if($brand)
        {
            $query= $query->whereIn("products.brand_id",$brand);
        }
        if($sCat!="")
        {
            $query= $query->where("products.sub_category_slug",$sCat);
        }
        //join with product item.....
        $query= $query->join('product_items', function ($q) {
            $q->on('product_items.product_id', '=', 'products.id');
        });
        //..........
        if($min_price!=""){
		   
            $query=$query->where('product_items.sprice','>=',$min_price)->where('product_items.sprice','<=', $max_price);
        }
        if($offer){
            if($max_offer==0)
            {
                $query=$query->where('product_items.offer','>=',$min_offer);
            }
            else
            {
                $query=$query->where('product_items.offer','>=',$min_offer)->where('product_items.offer','<=', $max_offer);
            }

        }

        $query=$query->get();
        return view('front.product.filter_product')->with('product',$query);
    }
    //get product list with filter.....
    function get_filter_product_type(Request $request)
    {
        $sessionPincode = session('pincode');
        $brand=$request->input('brand');
        $min_price=$request->input('min_price');
        $offer=$request->input('offer');
        $product_type=$request->input('product_type');
        if($offer)
        {
            $offerValue= explode("-",$offer[0]);
            $min_offer= $offerValue[0];
            $max_offer= $offerValue[1];
        }
        $max_price=$request->input('max_price');
        $cat=$request->input('cat_url');
        $sCat=$request->input('s_cat_url');
        $query = \DB::table("user_kyc")
            ->join('products', 'products.user_id', '=', 'user_kyc.user_id')
            ->join('product_images', 'product_images.product_id', '=', 'products.id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->select('products.*','product_images.image','brands.name as brand_name')
            ->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
            ->orderBy('updated_at','desc')
            ->where('products.is_admin_approved',1)
            ->groupBy('products.id')->distinct('products.id');
        if($product_type != 'new' ){
            $query->where("products.$product_type",1);
        }
        if($brand)
        {
            $query= $query->whereIn("products.brand_id",$brand);
        }

        //join with product item.....
        $query= $query->join('product_items', function ($q) {
            $q->on('product_items.product_id', '=', 'products.id');
        });
        //..........
        if($min_price!=""){

            $query=$query->where('product_items.sprice','>=',$min_price)->where('product_items.sprice','<=', $max_price);
        }
        if($offer){
            if($max_offer==0)
            {
                $query=$query->where('product_items.offer','<=',$min_offer);
            }
            else
            {
                $query=$query->where('product_items.offer','>=',$min_offer)->where('product_items.offer','<=', $max_offer);
            }

        }

        $query=$query->get();
        return view('front.product.filter_product_type_list')->with('product',$query);
    }
    //product type product
    public function productTypeList(Request $request)
    {
        $type= decrypt($request->segment(2));
        $sessionPincode = session('pincode');
        $query = \DB::table("user_kyc")
            ->join('products', 'products.user_id', '=', 'user_kyc.user_id')
            ->join('product_images', 'product_images.product_id', '=', 'products.id')
            ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
            //->leftJoin('scheme_products', 'scheme_products.product_id', '=', 'products.id')
            ->select('products.*','product_images.image','brands.name as brand_name','brands.id as brand_id')
            ->whereRaw("find_in_set($sessionPincode,user_kyc.delivery_pincode)")
            ->where('products.is_admin_approved',1)
            ->orderBy('products.id','DESC')
            ->groupBy('products.id')->distinct('products.id');

         if($type != 'new' ){
            $query->orderBy('updated_at','desc');
            $query->where("products.$type",1);
        }
        $allCatProduct = $query->get();
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
        return view('front.product.product_type_list',compact('allCatProduct','brand_filter','type'));
    }
    public function whats_new(Request $request)
    {
         $query= Product::with('product_image')->where('is_admin_approved',1)->orderBy('id','desc')->take(20);
         if(isset($_GET['price']))
        {
            $price= explode("-",$_GET['price']);
            $min= $price[0];
            $max= $price[1];
             $query =  $query->where('starting_price','>=',$min);
             if($max!="")
             {
               $query =  $query->where('starting_price','<=',$max);
             }
        }
        if($request->segment(2))
        {
          $query= $query->where('category_slug',$request->segment(2));
        }
          $new_list= $query->get();
          $category= Product::with('main_category')->where('is_admin_approved',1)->groupBy('category_id')->take(20)->get();
        return view('front.product.whats_new',compact('new_list','category'));
    }
    public function search_product()
    {
         $keywords= $_GET['q'];
		 $category_filter=array();
         $product= Product::with('product_image')->where('is_admin_approved',1)->orderBy('id','desc')->take(20)->Where('name', 'like', $keywords . '%')->get();
		 
         $category_filter= Product::with('main_category')->where('is_admin_approved',1)->groupBy('category_id')->Where('name', 'like', $keywords . '%')->take(20)->get();
          
		  
        return view('front.product.search_product')
		->with('category_filter',$category_filter)
		->with('product',$product);
    }
	
	 public function display_suggestion(Request $request)
    {
        $sessionPincode = session('pincode');
        $search = $request->input('term');
        $catData = \DB::table("categories")
            ->where('categories.name', 'like', '%'. $search . '%')
            ->where('status',1)
            ->get();
        if (count($catData) > 0) {
            foreach ($catData as $keys => $vs) {
                $listurl=URL::to('/category/' . $vs->slug);
                $result[] = ['value' => $vs->name,'url'=>$listurl, 'search_type' => 'category'];
            }
        }
        $data = \DB::table("user_kyc")
            ->join('products', 'products.user_id', '=', 'user_kyc.user_id')
            ->whereRaw("find_in_set($sessionPincode, user_kyc.delivery_pincode)")
            ->where('products.name', 'like', '%'. $search . '%')
            ->where('products.is_admin_approved',1)
            ->distinct('products.id')->get();
         if (count($data) > 0) {
            foreach ($data as $key => $v) {
                $url=URL::to('/product/' . $v->slug);
                $result[] = ['value' => $v->name,'url'=>$url, 'search_type' => 'product'];
            }
        }
        echo json_encode($result);
    }

    





}