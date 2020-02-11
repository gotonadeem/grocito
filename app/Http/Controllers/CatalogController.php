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
use App\SellerDuplicateProduct;
use App\Brand;
use App\Attribute;
use App\UserKyc;
use App\AdminNotification;
use Image;
use Helper;
use App\ProductNote;
use App\Size;
use Validator;
use DateTime;
use DateInterval;
use DatePeriod;
use DB;
use Session;
use URL;
class CatalogController extends Controller
{
    public function __construct()
    {
		parent::__construct();
    }
	
    public function index()
    {
		$date = date('Y-m-d', strtotime('+1 day')); //tomorrow date
		$weekOfdays = array();
		$begin = new DateTime($date);
		$end = new DateTime($date);
		$end = $end->add(new DateInterval('P7D'));
		$interval = new DateInterval('P1D');
		$daterange = new DatePeriod($begin, $interval ,$end);
		foreach($daterange as $dt){
			$weekOfdays[] = $dt->format('l : Y-m-d');
		}
		if(Auth::user())
		{
		  $sponsor_plan= SponsorPlan::get()->toArray();
		  $catatlog_list=Product::with('product_image','main_category','sub_category','super_sub_category','product_note','product_item')->where('status',1)->where('user_id',Auth::user()->id)->orderBy('id','desc');	
	       if(isset($_GET['search']))
			{
				$keyword= $_GET['keyword'];
				$catatlog_list=$catatlog_list->where('name',$keyword);
			}
			$catatlog_list=$catatlog_list->paginate(15);
		  return view('seller.catalog.index',compact('catatlog_list','weekOfdays','sponsor_plan'));
		}
		else
		{
			return redirect('/');
		}
    }
	
	public function create()
	{
		 $brand=Brand::where('status',1)->get();
		 $category_list=Category::where('status',1)->get();
		$colorList=Attribute::where('type','product')->where('name','color')->get();
		return view('seller.catalog.create',compact('category_list','brand','colorList'));
	}
	public function store(Request $request)
	{
		$rules = array();
		$name= $request->input('name');
		$description= $request->input('description');
		$weight= $request->input('weight');
		$price= json_decode($request->input('price'));
		$qty= json_decode($request->input('qty'));
		$offer= json_decode($request->input('offer'));
		$brand_id= $request->input('brand_id');
		$category= $request->input('category');
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
		$sellerData = UserKyc::where('user_id',Auth::user()->id)->first();
		$product_info=array(
			'user_id'=>Auth::user()->id,
			'city_id'=>$sellerData->city_id,
			'name'=>$name,
			'brand_id'=>$brand_id,
			'description'=>$description,
			'category_id'=>$category,
			'sub_category_id'=>$sub_category,
			'sub_category_slug'=>$sub_category_slug,
			'p_gst'=>$p_gst,
			'color'=>$p_color,
		);
		$product = new Product($product_info);
		$product->save();
		//update info....
		$category= Category::select('slug')->where('id',$category)->first();
		$pInfoUpdate['slug']= str_slug($request->name." ".$product->id,"-");
		$pInfoUpdate['sku']= "GRCTO-".$product->id;
		$pInfoUpdate['category_slug']=$category['slug'];
		DB::table('products')->where('id',$product->id)->update($pInfoUpdate);
		$rules = array(
			//'name'  =>'required|unique:products,name',
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			//return redirect('seller/catalog-add')->withInput()->withErrors($validator);
			echo json_encode(array("status"=>false,"message"=>'Duplicate product name'));
		} else {
			if(count($_FILES)>20)
			{
				echo json_encode(array("status"=>false,"message"=>'maximum 20 images can be uploaded at once'));
			}
			else
			{
				$i=0;
				foreach ($_FILES as $file) {
					// Valid extension
					$valid_ext = array('png','jpeg','jpg');

					$propertyArray=array();
					$photo_name = time() . '-' . $file['name'];
					$path_original = public_path() . '/admin/uploads/product/'.$photo_name;
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
					//$this->addTextWatermark($path_original, $watermark, $path_original);
					$propertyArray = [
						'product_id' => $product->id,
						'image' =>$photo_name,
					];
					DB::table('product_images')->insert($propertyArray);
					$i++;
				}

				foreach(json_decode($weight) as $ks=>$vs)
				{
					$offerPrice = $offer[$ks];
					$salePrice = $price[$ks] - $offerPrice;
					$item=array();
					$item['product_id']= $product->id;
					$item['seller_id']= Auth::user()->id;
					$item['weight']= $vs;
					$item['price']= $price[$ks];
					$item['offer'] = $offer[$ks];
					$item['sprice']= $salePrice;
					$item['qty']= $qty[$ks];
					$obj= new ProductItem($item);
					$obj->save();

				}
			}
			//add notification for admin ..........
			$adminnotifyObj = new AdminNotification;
			$adminnotifyObj->int_val = $product->id;//product  id
			$adminnotifyObj->type = 'product_upload';
			$adminnotifyObj->message = 'New Product Upload';
			$adminnotifyObj->save();
			echo json_encode(array("status"=>true,"message"=>'Catalog has been uploaded successfully'));
		}


	}
	function edit($id=null,Request $request)
	{
		if(Auth::user())
		{
			$brand=Brand::where('status',1)->get();
			$category_list=Category::where('status',1)->get();
			$product_details=Product::with('main_category','sub_category','super_sub_category','product_item')->where('id',$id)->first();
			$colorList=Attribute::where('type','product')->where('name','color')->get();
			$type = $request->input('type');
			if($type==1){
				$duplicateProduct = $type; // if duplicate product, type equal is one
			}else{
				$duplicateProduct = 0;
			}
			return view('seller.catalog.edit',['colorList'=>$colorList,'product_details'=>$product_details,'category_list'=>$category_list,'brand'=>$brand,'duplicateProduct'=>$duplicateProduct]);
		}
		else
		{
			return redirect('/');
		}
	}
	public function update(Request $request)	
	{
		 $rules = array();
		 $duplicae_product= $request->input('duplicae_product');
		//if seller add similar product ......
		//print_r($duplicae_product);
		 if($duplicae_product ==1){

			 $id= $request->input('id');
			 $product_data = Product::where('id',$id)->first();
			 $dupliacte['product_id'] = $product_data->id;
			 $dupliacte['seller_id']  = Auth::user()->id;

			 $instance = SellerDuplicateProduct::firstOrNew(array('seller_id' => Auth::user()->id,'product_id'=>$product_data->id));

			 $instance->fill($dupliacte)->save();

			 $weight = json_decode($request->input('weight'));
			 $price= json_decode($request->input('price'));
			 $qty= json_decode($request->input('qty'));
			 $offer= json_decode($request->input('sprice'));//it's offer in amount.
			 $item_id= json_decode($request->input('item_id'));

			 foreach($weight as $ks=>$vs)
			 {
				 $offerPrice = $offer[$ks];
				 $salePrice = $price[$ks] - $offerPrice;
				 $item=array();
				 $item['product_id']= $product_data->id;
				 $item['seller_id']= Auth::user()->id;
				 $item['weight']= $vs;
				 $item['price']= $price[$ks];
				 $item['offer'] = $offer[$ks];
				 $item['sprice']= $salePrice;
				 $item['qty']= $qty[$ks];
				  $itemCount = count($item_id);

				 if(!empty($item_id) && $itemCount > $ks ){
					 $itemId = $item_id[$ks];
					 if($itemId){
						 $update_data = ProductItem::find($itemId)->fill($item);
						 if($update_data){
							 $update_data->update();
						 }

					 }
				 }else {
					 $obj = new ProductItem($item);
					 $obj->save();
				 }

			 }

			 echo json_encode(array('type'=>1,"status"=>true,"message"=>"Catalog has been updated successfully"));
			 exit();
		 }
		//end similar product code.......
		 $name= $request->input('name');
		 $id= $request->input('id');
		 $description= $request->input('description');
		 $p_gst= $request->input('p_gst');
		 $weight= $request->input('weight');
		 $price= json_decode($request->input('price'));
		 $qty= json_decode($request->input('qty'));
		 $offer= json_decode($request->input('sprice'));//offer in %.
		 $item_id= json_decode($request->input('item_id'));
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
		    'user_id'=>Auth::user()->id,
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
						//DB::table('product_items')->where('product_id',$id)->delete();
						foreach(json_decode($weight) as $ks=>$vs)
						{
							$offerPrice = $offer[$ks];
							$salePrice = $price[$ks] - $offerPrice;
							$item=array();
							$item['product_id']= $id;
							$item['seller_id']= Auth::user()->id;
							$item['weight']= $vs;  
							$item['price']= $price[$ks];
							$item['offer'] = $offer[$ks];
							$item['sprice']= $salePrice;
							$item['qty']= $qty[$ks];
							$itemCount = count($item_id);
							if(!empty($item_id) && $itemCount > $ks ){
								$itemId = $item_id[$ks];
								if($itemId){
									$update_data = ProductItem::find($itemId)->fill($item);
									if($update_data){
										$update_data->update();
									}

								}
							}else {
								$obj = new ProductItem($item);
								$obj->save();
							}
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




	
	function details(Request $request)
	{
		$catatlog_details=Product::with('product_image','product_category')->where('id',$request->input('id'))->first();
		return view('seller.catalog.catalog_details_ajax',compact('catatlog_details'));
	}
	
	function get_subcat(Request $request)
	{
	      $category_list=SubCategory::where("category_id",$request->input('id'))->get();
		  return view('seller.catalog.sub_category_ajax',compact('category_list'));
	}
	function getProductList(Request $request){
		$search = $request->input('term');
		$result =[];
		$data= Product::where('is_admin_approved',1)->where('user_id', '<>', Auth::user()->id)->where('name', 'like','%'. $search . '%')->get();

		if (count($data) > 0) {
			foreach ($data as $key => $v) {
				$url=URL::to('/seller/catalog-edit/' . $v->id.'?type=1');
				$result[] = ['value' => $v->name,'url'=>$url,'id'=>$v->id, 'seller_id' => $v->user_id];
			}
		}
		echo json_encode($result);
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
	
	function catalog_delete($id=null)
	{
		if(Auth::user())
		{
	  $product=Product::where('id', '=',$id)->first();
	  $product->delete();
	  Session::flash('success_message', 'Catalog has been deleted successfully');
      return redirect('seller/catalog');
		}
       else
       {
	     return redirect('/');
       }	
	}
	function duplicateCatlogDelete($id=null)
	{
		if(Auth::user())
		{
			$product=SellerDuplicateProduct::where('id', '=',$id)->first();
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

			Session::flash('success_message', 'Catalog has been deleted successfully');
			return redirect('seller/duplicate-product');
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
	public function addBrand(Request $request){
		$brandData['name'] =$request->input('name');
		$brandData['status'] =1;
		$brand = new Brand($brandData);
		$brand->save();
		echo json_encode(array('status'=>true,'message'=>'add Successfully'));
	}

    function display_seller_suggestion(Request $request)
	{
		$search = $request->input('term');
		$data = \DB::table("user_kyc")
            ->join('products', 'products.user_id', '=', 'user_kyc.user_id')
            ->where('products.name', 'like', '%'. $search . '%')
            ->where('products.user_id', Auth::user()->id)
            ->distinct('products.id')->get();
         if (count($data) > 0) {
            foreach ($data as $key => $v) {
                $url=URL::to('/product/' . $v->slug);
                $result[] = ['value' => $v->name,'url'=>$url, 'search_type' => 'product'];
            }
        }
        echo json_encode($result);
	}
	
	//end
}


