<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\Category;
use App\SubCategory;
use App\SuperSubCategory;
use App\ProductImage;
use App\ProductSponsor;
use App\SponsorPlan;
use App\Product;
use App\Order;
use App\Payment;
use App\ProductItem;
use App\Brand;
use Image;
use Helper;
use App\ProductNote;
use App\Size;
use App\SchemeProduct;
use Validator;
use DateTime;
use DateInterval;
use DatePeriod;
use DB;
use Session;
class SchemeProductController extends Controller
{
    public function __construct()
    {
		parent::__construct();
    }
	
    public function index()
    {

		if(Auth::user())
		{
		  $catatlog_list=SchemeProduct::with('get_product','get_product_item')->where('status',1)->where('user_id',Auth::user()->id)->orderBy('id','desc')->paginate();
			//dd($catatlog_list);
	      return view('seller.scheme_product.index',compact('catatlog_list'));
		}
		else
		{
			return redirect('/');
		}
    }
	
	public function create()	
	{
		 $category_list=Category::where('status',1)->get();
		return view('seller.scheme_product.create',compact('category_list','brand'));
	}
	public function store(Request $request)
	{
		$rules = array();
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return redirect('seller/scheme-product-add')->withInput()->withErrors($validator);
		} else {

			$file = $_FILES['files0'];
			$user_id= Auth::user()->id;
			$cat_id= $request->input('cat_id');
			$sub_cat_id= $request->input('sub_cat_id');
			$product_id= $request->input('product_id');
			$product_item_id= $request->input('product_item_id');
			$offer_name= $request->input('offer_name');
			$valid_ext = array('png','jpeg','jpg');

			$photo_name = time() . '-' . $file['name'];
			$path_original = public_path() . '/admin/uploads/scheme_product/'.$photo_name;
			// file extension
			$file_extension = pathinfo($path_original, PATHINFO_EXTENSION);
			$file_extension = strtolower($file_extension);
			$this->compressImage($file['tmp_name'],$path_original,50);
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
			$product_info=array(
				'user_id'=>Auth::user()->id,
				'cat_id'=>$cat_id,
				'sub_cat_id'=>$sub_cat_id,
				'product_id'=>$product_id,
				'product_item_id'=>$product_item_id,
				'offer_name'=>$offer_name,
				'image'=>$photo_name,

			);
			$product = new SchemeProduct($product_info);
			$product->save();

			echo json_encode(array("status"=>true,"message"=>'Scheme product has been uploaded successfully'));
		}


	}
	public function update(Request $request)	
	{
		 $rules = array();
		 $name= $request->input('name');
		 $id= $request->input('id');
		 $description= $request->input('description');
		 $weight= $request->input('weight');
		 $price= json_decode($request->input('price'));
		 $qty= json_decode($request->input('qty'));
		 $sprice= json_decode($request->input('sprice'));


		 $brand_id= $request->input('brand_id');
		 $category= $request->input('category_id');
		 $sub_category= $request->input('sub_category_id');
		 $super_sub_category= $request->input('super_sub_category_id');
		  if($super_sub_category)
		  {
		    $slug=SuperSubCategory::select('slug')->where('id',$super_sub_category)->first();
		    $super_sub_category_slug= $slug->slug;
		   }
		   else
		   {
			  $super_sub_category_slug=""; 
		   }
		   
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
		    'user_id'=>Auth::user()->id,
		    'name'=>$name,
		    'brand_id'=>$brand_id,
		    'description'=>$description,
		    'category_id'=>$category,
		    'sub_category_id'=>$sub_category,
		    'sub_category_slug'=>$sub_category_slug,
		   'super_sub_category_id'=>$super_sub_category,
		   'super_sub_category_slug'=>$super_sub_category_slug,
		 );  
		 
			$category= Category::select('slug')->where('id',$request->category_id)->first();
			$product_info['slug']= str_slug($request->name." ".$id,"-");
			$product_info['category_slug']=$category->slug;
		     $update_data = Product::find($id)->fill($product_info);
             $update_data->update();
			 $validator = Validator::make($request->all(), $rules);
			 if ($validator->fails()) {
				return redirect('admin/team/create-property')->withInput()->withErrors($validator);
			 } else {
				
					if(count($_FILES)>20)
					{
						echo json_encode(array("status"=>false,"message"=>'maximum 20 images can be uploaded at once'));
					}
					else
					{
						 $i=0;
						foreach ($_FILES as $file) {
							$propertyArray=array();
							$photo_name = time() . '-' . $file['name'];
							$path_original = public_path() . '/admin/uploads/product'.$photo_name;
							//move_uploaded_file($file['tmp_name'],$path_original);
							$watermark = "C-".rand(10,99).rand(111,999);
							$this->compressImage($file['tmp_name'],$path_original,50);
							$img = Image::make(realpath($path_original));
							if($img->height()>512)
								{
								 //$img->resize(intval($img->width() / $ratio),512);
								   $img->resize(null,512,function ($constraint) {
									$constraint->aspectRatio();
								  
									});
								 $img->save($path_original);
								}
								
							//$this->addTextWatermark($path_original, $watermark, $path_original);
							$propertyArray = [
								'product_id' => $id,
								'image' =>$photo_name,
							   ];
							 DB::table('product_images')->insert($propertyArray);
						$i++;
						}
						DB::table('product_items')->where('product_id',$id)->delete();
						foreach(json_decode($weight) as $ks=>$vs)
						{
							$item=array();
							$item['product_id']= $id;  
							$item['weight']= $vs;  
							$item['price']= $price[$ks];  
							$item['sprice']= $sprice[$ks];  
							$item['qty']= $qty[$ks];  
							$obj= new ProductItem($item);
							$obj->save();
						}
						
				   }
					echo json_encode(array("status"=>true,"message"=>'Catalog has been updated successfully'));
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


	
	function edit($id=null)
	{
		if(Auth::user())
		{
	     $brand=Brand::where('status',1)->get();
	     $category_list=Category::where('status',1)->get();
		 $product_details=Product::with('main_category','sub_category','super_sub_category','product_item')->where('id',$id)->first();
		return view('seller.catalog.edit',['product_details'=>$product_details,'category_list'=>$category_list,'brand'=>$brand]);
		}
		else
		{
			return redirect('/');
		}
	}
	
	function details(Request $request)
	{
		$scheme_details=SchemeProduct::with('get_product','get_product_item')->where('id',$request->input('id'))->first();
		return view('seller.scheme_product.catalog_details_ajax',compact('scheme_details'));
	}
	
	function get_subcat(Request $request)
	{
	      $category_list=SubCategory::where("category_id",$request->input('id'))->get();
		  return view('seller.scheme_product.sub_category_ajax',compact('category_list'));
	}

	function getProduct(Request $request)
	{
		$productList=Product::where('user_id',Auth::user()->id)->where('is_admin_approved',1)->where("sub_category_id",$request->input('id'))->get();
		return view('seller.scheme_product.product_ajax',compact('productList'));
	}
	function getProductItem(Request $request)
	{
		$productItem=ProductItem::where("product_id",$request->input('id'))->get();
		return view('seller.scheme_product.product_item_ajax',compact('productItem'));
	}
	function get_supsubcat(Request $request)
	{
	      $category_list=SuperSubCategory::where("sub_category_id",$request->input('id'))->get();  
		  return view('seller.catalog.sub_category_ajax',compact('category_list'));
	}
	
	function update_price(Request $request)
	{
			$product_id= $request->input('product_id');
			$price= $request->input('price');
			$sell_price= $request->input('sell_price');
			$productData['starting_price']=$price;
			$productData['sell_price']=$sell_price;
			$product=Product::where('id', '=',$product_id)->first();
			if($product->update($productData))
			{
				 echo json_encode(array('status'=>true,'message'=>'Updated Successfully'));
			}
			else
			{
				 echo json_encode(array('status'=>false,'message'=>'Please Try Successfully'));
			}
	}
	
	function schemeProductDelete($id=null)
	{
		if(Auth::user())
		{
	  $schemeProduct=SchemeProduct::where('id', '=',$id)->first();
			$schemeProduct->delete();
	  Session::flash('success_message', 'Scheme Product has been deleted successfully');
      return redirect('seller/scheme-product');
		}
       else
       {
	     return redirect('/');
       }	
	}
	
	function delete_catalog_image(Request $request)
	{
		    $id= $request->input('id');
			$product=ProductImage::where('id', '=',$id)->delete();
			if($product)
			{
				 echo json_encode(array('status'=>true,'message'=>'Deleted Successfully'));
			}
			else
			{
				 echo json_encode(array('status'=>false,'message'=>'Please Try Successfully'));
			}
	}
	
	
	
	function import()
	{
			$category_list=Category::where('status',1)->get();
		    return view('seller.catalog.import',compact('category_list'));	
	}
	
	function csvToArray($filename = '', $delimiter = ',')
{
    if (!file_exists($filename) || !is_readable($filename))
        return false;

    $header = null;
    $data = array();
    if (($handle = fopen($filename, 'r')) !== false)
    {
        while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
        {
            if (!$header)
                $header = $row;
            else
                $data[] = array_combine($header, $row);
        }
        fclose($handle);
    }

    return $data;
   }

    
	
	function store_import(Request $request)
	{	
	    try{
			   if($request->hasFile('product_csv'))
				{
					$path_original=public_path() . '/uploads/csv';
					$file = $request->product_csv;
					$photo_name = time() . '-' . $file->getClientOriginalName();
					$file->move($path_original, $photo_name);
				}
				$file = public_path("/uploads/csv/demo.csv");
				$customerArr = $this->csvToArray($file);
				for ($i = 0; $i < count($customerArr); $i ++)
				{
					$data=array('category_id'=>$request->input('category'),'sub_category_id'=>$request->input('sub_category_id'),'super_sub_category_id'=>$request->input('super_sub_category_id'));
					
					
					$allData=array_merge($customerArr[$i],$data);
					unset($allData['image1']);
					unset($allData['image2']);
					unset($allData['image3']);
					unset($allData['image4']);
					unset($allData['image5']);
					//$product=Product::firstOrCreate($allData);
					$image=array("image1"=>$customerArr[$i]['image1'],"image2"=>$customerArr[$i]['image2'],"image3"=>$customerArr[$i]['image3'],"image4"=>$customerArr[$i]['image4'],"image5"=>$customerArr[$i]['image5']);
					foreach($image as $vs)
					{
						if(!empty(trim($vs)))
						{
						  $productImage['product_id']=$product->id;
						  $productImage['image']=$vs;
						 // DB::table('product_images')->insert($productImage);
						}
					}
				}
				Session::flash('success_message', 'Catalog has been imported successfully');
				return Redirect::back();
		}catch (Exception $e) {
                Session::flash('success_message', $e->getMessage());
				return Redirect::back();
        }
	}
	
	public function get_next_payment()
	{
		
	   $seller_order_date= Order::with('order_meta')->where('seller_id',Auth::user()->id)->where('shipped_date','!=','0000-00-00 00:00:00')->first();
	   if(count($seller_order_date)>0)
	   {
	   $date=date("Y-m-d",strtotime($seller_order_date->shipped_date));
	   $id= Auth::user()->id;	   
       $response=DB::select("SELECT 
			1 + DATEDIFF(orders.shipped_date, '".$date."') DIV 7  AS weekNumber
		  ,'".$date."' + INTERVAL (DATEDIFF(orders.shipped_date,'".$date."') DIV 7) WEEK
			  AS week_start_date
		  , MIN(orders.shipped_date) AS actual_first_date
		  , MAX(orders.shipped_date) AS actual_last_date
		  , SUM(order_metas.price * order_metas.qty) AS total
		FROM 
			orders inner join order_metas on orders.id= order_metas.order_id
		WHERE 
			orders.shipped_date >= '".$date."' and orders.seller_id='$id' and (order_metas.status='shipped' or order_metas.status='exchange')
		GROUP BY
			DATEDIFF(orders.shipped_date, '".$date."') DIV 7");		
	    return $response;
	   }
	   else
	   {
		   return false;
	   }
	}
	
	
	function activate_sponsor(Request $request)
	{
		     if(Auth::user())
			 {
					$data['product_id']= $request->input('product_id');
					$data['sponsor_plan_id']= $request->input('sponsor_plan_id');
					$data['user_id']= Auth::user()->id;
					$data['created_at']= date("Y-m-d h:i:s");
					$data['date']= $request->input('date');
					$sponsor_plan= SponsorPlan::get()->toArray();
					if($data['sponsor_plan_id']==1){ $data['price']= $sponsor_plan[0]['price']; } elseif($data['sponsor_plan_id']==2) { $data['price']= $sponsor_plan[1]['price']; } elseif($data['sponsor_plan_id']==3){ $data['price']= $sponsor_plan[2]['price']; }
					//check amount....
					$next_payment=null;
					$last_payment = Payment::where('user_id',Auth::user()->id)->where('type','deposit')->orderBy('id','desc')->limit(1)->first();
		           
					if($this->get_next_payment()!=false)
						{
						$response=$this->get_next_payment();
						$next_payment=end($response);
						}
						if((count($next_payment)>0 and $next_payment!="") or $last_payment['amount'])
		                  {
							 $total_outstanding=!is_null($last_payment)?$last_payment['amount']:0; 
						     $total= @$total_outstanding+((count($next_payment)>0)?!is_null($next_payment->total)?$next_payment->total:0:"");
							 if($total>=$data['price'])
							 {
								   $obj= new ProductSponsor($data);
									if($obj->save())
									{
										 echo json_encode(array('status'=>1,'message'=>'Requested Successfully'));
										  Session::flash('success_message', "Requested Successfully");
									}
									else
									{
										 echo json_encode(array('status'=>2,'message'=>'Please Try again'));
										
									}
							 }
							 else
							 {
								  echo json_encode(array('status'=>3,'message'=>'You do not have sufficient balance in your wallet'));
										
							 }
						  }
						  else
						  {
							     echo json_encode(array('status'=>3,'message'=>'You do not have sufficient balance into your wallet'));
						  }	  
					
			 }
			 else
			 {
				 echo json_encode(array('not_login'=>true)); 
			 }
	}
	
	function get_sponsored(Request $request)
    {
	      if(Auth::user())
			 {
				    $data=ProductSponsor::with('plan')->where('id',$request->input('id'))->first();
					 return view('seller.catalog.sponsored_plan_ajax',compact('data'));
			 }
    }
	
	function stock_image(Request $request)
	{
		    $id=$request->input('id');
			$productData['in_stock']=$request->input('status');
			$product=ProductImage::where('id', '=',$id)->first();
			if($product->update($productData))
			{
				 echo json_encode(array('status'=>true,'message'=>'Updated Successfully'));
			}
			else
			{
				 echo json_encode(array('status'=>false,'message'=>'Please Try Again'));
			}
    }
	function catalog_error($id=null)
	{
	  if(Auth::user())
		 {
		  $product= Product::where('id',$id)->first();
		  $data= ProductNote::where('product_id',$id)->get();
		  return view('seller.catalog.errors',compact('data','product'));
		 }
		else
		{
			return redirect('/');
		}
	}
}


