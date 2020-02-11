<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\User;
use App\Product;
use App\UserKyc;
use App\City;
use App\Category;
use App\SubCategory;
use App\Brand;
use App\ProductItem;
use App\Attribute;
use App\SellerNotification;

use App\ProductImage;
use App\SuperSubCategory;
use DB;
use Helper;
use DateTime;
use App\SponsorPlan;
use App\GeneralSetting;
use App\ProductSponsor;
use App\Payment;
use DateInterval;
use DatePeriod;
use Image;
use URL;
use Excel;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    public function __construct()
    {
    }
    public function index()
    {
        $category_list=Product:: with('main_category')->groupBy('category_id')->get();
		return view('admin.products.index',compact('category_list'));
    }
	//color....
	public function add_color()
	{
		$data= Attribute::where('type','product')->where('name','color')->get();
		return view('admin.products.color',compact('data'));
	}
	public function store_color(Request $request)
	{
		$data=$request->input('color');
		$colorData = array(
			'value'    => strtolower($data),
			'name'    =>'color',
			'type'    =>'product',
			'code'    =>$request->input('code'),
		);
		$rules = array('name'=>"required|unique:attributes,value");
		$validator = Validator::make($colorData, $rules);
		if ($validator->fails()) {
			return redirect('admin/product/color-list/')->withInput()->withErrors($validator);
		}
		$obj= new Attribute($colorData);
		$obj->save($colorData);
		Session::flash('success_message', 'Color has been added successfully!');
		return redirect('admin/product/color-list');
	}
	public function delete_color()
	{
		$property = Attribute::findOrFail($_POST['id']);
		if(!empty($property->delete()))
		{
			Session::flash('success_message', 'Color has been deleted successfully!');
		}
		else {
			Session::flash('error_message', 'Unable to delete the color');
		}
	}
	public function unverified_product_list()
    {
        return view('admin.products.unverified_product_list');
    }

    public function create()
    {
		$cityList = City::where('status',1)->where('state_id',33)->get();
		$brandList = Brand::where('status',1)->get();
        $sellerList=User::where('verify_status','verified')->get();
        $cateList=Category::get();
        $subcateList=SubCategory::get();
        $productList=Product::where('is_admin_approved',1)->get();
        $supsubcateList=SuperSubCategory::get();
		$color=Attribute::where('type','product')->where('name','color')->get();
        return view('admin.products.create')
            ->with('category_list',$cateList)
            ->with('sub_category_list',$subcateList)
            ->with('super_sub_category_list',$supsubcateList)
            ->with('sellerList',$sellerList)
            ->with('productList',$productList)
            ->with('cityList',$cityList)
			->with('colorList',$color)
            ->with('brandList',$brandList);
    }
	public function store(Request $request)
	{
		 
		$rules = array();

			$validator = Validator::make($request->all(), $rules);
			if ($validator->fails()) {
				return redirect('admin/product/create-product')->withInput()->withErrors($validator);
			} else {
				$pInfo=$request->all();
				$pInfo['color']= $request->color;
				$pInfo['p_gst']= $request->p_gst;
				$sub_category= $request->sub_category_id;

				$weight= explode(",",$request->weight);
				$price= explode(",",$request->price);
				$qty= explode(",",$request->qty);
				$offer= explode(",",$request->offer);

				if($sub_category)
				{
					$slug=SubCategory::select('slug')->where('id',$sub_category)->first();
					$sub_category_slug= $slug->slug;
				}
				else
				{
					$sub_category_slug="";
				}
				$pInfo['sub_category_slug']= $sub_category_slug;
				$product = new Product($pInfo);
				$product->save();
				//Add product item like weight, price, qty etc.

					foreach ($weight as $ks => $vs) {
						$item = array();
						$offerPrice = 0;
						$salePrice = 0;
						if(!empty($vs)) {
							$offerPrice = $offer[$ks];
							$salePrice = $price[$ks] - $offerPrice;
							$item['product_id'] = $product->id;
							$item['seller_id'] = $request->input('user_id');
							$item['weight'] = $vs;
							$item['price'] = $price[$ks];
							$item['offer'] = $offer[$ks];
							$item['sprice'] = $salePrice;
							$item['qty'] = $qty[$ks];
							$obj = new ProductItem($item);
							$obj->save();
						}
					}

				$category= Category::select('slug')->where('id',$request->category_id)->first();
				$pInfoUpdate['slug']= str_slug($request->name." ".$product->id,"-");
				$pInfoUpdate['sku']= "GRCTO-".$product->id;
				$pInfoUpdate['category_slug']=$category->slug;
				DB::table('products')->where('id',$product->id)->update($pInfoUpdate);
				$i=0;
				foreach ($_FILES as $file) {
					$product1=array();
					$photo_name = time() . '-' . $file['name'];
					$path_original = public_path() . '/admin/uploads/product/'.$photo_name;
					$watermark = "C-".rand(10,99).rand(111,999);
					$this->compressImage($file['tmp_name'],$path_original,50);
					$ratio=16/9;
					$img = Image::make(realpath($path_original));
					if($img->height()>512)
					{
						$img->resize(null,512,function ($constraint) {
							$constraint->aspectRatio();
						});
						$img->save($path_original);
					}
					$product1 = [
						'product_id' => $product->id,
						'image' =>$photo_name,
					];
					DB::table('product_images')->insert($product1);
					$i++;
				}
				echo json_encode(array("status"=>true,"message"=>'Catalog has been uploaded successfully'));
			}

	}


    public function edit($id)
    {
		$cityList = City::where('status',1)->where('state_id',33)->get();
		$brandList = Brand::where('status',1)->get();
        $cateList=Category::get();
        $subcateList=SubCategory::get();
        $supsubcateList=SuperSubCategory::get();
		$productList=Product::where('is_admin_approved',1)->get();
		$product_details=Product::with('main_category','sub_category','product_item')->where('id',$id)->first();
		$sellerList=UserKyc::with('user')->whereHas('user', function ($query) {
			$query->where('role_id', 2);
		})->where('user_kyc.city_id',$product_details->city_id)->get();
		$color=Attribute::where('type','product')->where('name','color')->get();
        return view('admin.products.edit')->with('category_list',$cateList)
            ->with('sub_category_list',$subcateList)
            ->with('super_sub_category_list',$supsubcateList)
            ->with('product_details',$product_details)
            ->with('sellerList',$sellerList)
            ->with('cityList',$cityList)
			->with('colorList',$color)
            ->with('brandList',$brandList)
		->with('productList',$productList);
    }

	public function update(Request $request)	
	{
		 $rules = array();
		 $name= $request->input('name');
		 $city_id= $request->input('city_id');
		 $brand_id= $request->input('brand_id');
		 $id= $request->input('product_id');
		 $weight= explode(",",$request->weight);
		 $price= explode(",",$request->price);
		 $qty= explode(",",$request->qty);
		 $offer= explode(",",$request->offer);
		 $item_id= explode(",",$request->item_id);
		 $description= $request->input('description');
		 $category= $request->input('category_id');
		 $sub_category= $request->input('sub_category_id');
		 $user_id= $request->input('user_id');
		$color= $request->input('color');
		$p_gst= $request->input('p_gst');
		$related_product= $request->input('related_product');
		 $sub_category= $request->sub_category_id;
			 $validator = Validator::make($request->all(), $rules);
			 if ($validator->fails()) {
				return redirect('admin/team/create-property')->withInput()->withErrors($validator);
			 } else {

				 if($sub_category)
				 {
					 $slug=SubCategory::select('slug')->where('id',$sub_category)->first();
					 $sub_category_slug= $slug->slug;
				 } else  {
					 $sub_category_slug="";
				 }
				 $product_info=array(
					 'name'=>$name,
					 'city_id'=>$city_id,
					 'brand_id'=>$brand_id,
					 'description'=>$description,
					 'user_id'=>$user_id,
					 'category_id'=>$category,
					 'color'=>$color,
					 'p_gst'=>$p_gst,
					 'sub_category_id'=>$sub_category,
					 'sub_category_slug'=>$sub_category_slug,
					 'is_return'=>$request->input('is_return'),
					 'is_exchange'=>$request->input('is_exchange'),
					 'is_cod'=>$request->input('is_cod'),
					 'related_product'=>$related_product,
				 );

				 $product_info['slug']= str_slug($request->name." ".$id,"-");
				 $update_data = Product::find($id)->fill($product_info);
				 $update_data->update();
				 //DB::table('product_items')->where('product_id',$id)->delete();
				 //Add product item like weight, price, qty etc.
				 foreach ($weight as $ks => $vs) {
					 $item = array();
					 $offerPrice = 0;
					 $salePrice = 0;
					 if(!empty($vs)) {
						 $offerPrice =$offer[$ks];
						 $salePrice = $price[$ks] - $offerPrice;
						 $item['product_id'] = $id;
						 $item['seller_id'] = $user_id;
						 $item['weight'] = $vs;
						 $item['price'] = $price[$ks];
						 $item['offer'] = $offer[$ks];
						 $item['sprice'] = $salePrice;
						 $item['qty'] = $qty[$ks];
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

				 $i=0;
					foreach ($_FILES as $file) {
						$propertyArray=array();
						$photo_name = time() . '-' . $file['name'];
						$path_original = public_path() . '/admin/uploads/product/'.$photo_name;
						    $this->compressImage($file['tmp_name'],$path_original,50);
							$ratio=16/9;
							$img = Image::make(realpath($path_original));
							
							 if($img->height()>512)
								{
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
					echo json_encode(array("status"=>true,"message"=>'Product has been uploaded successfully'));
		   }

	}
    //get list of record of product...........................................................
    public function getProductData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'products.id',
            1 => 'users.username',
            2 => 'products.name',
            3 => 'products.starting_price',
            4 => 'products.description',
        );
        $totalAmenities = Product::with('user_name','main_category','sub_category','product_note')->where('is_admin_approved',1)->get()->count();
        $totalFiltered  = $totalAmenities;
        $amenities = Product::with('user_name','main_category','sub_category','product_note','city')->select('products.*')->where('is_admin_approved',1)->orderBy('products.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
			$amenities=$amenities->where(function($query) use ($searchString) {
			   return $query->where('name','LIKE','%'.$searchString.'%')
					 ->orWhere('id',$searchString)
				   ->orWhereHas('main_category', function ($query) use ($searchString)
				   {
					   $query->whereRaw("name  LIKE '%" . $searchString . "%'");
				   })
				   ->orWhereHas('city', function ($query) use ($searchString)
				   {
					   $query->whereRaw("name  LIKE '%" . $searchString . "%'");
				   })
				   ->orWhereHas('user_name', function ($query) use ($searchString)
				   {
					   $query->whereRaw("username  LIKE '%" . $searchString . "%'");
				   });
			});
			
			$totalFiltered=Product::where('is_admin_approved',1)->where(function($query) use ($searchString) {
                return $query->where('name','LIKE','%'.$searchString.'%')
					  ->orWhere('id',$searchString)
					->orWhereHas('main_category', function ($query) use ($searchString)
					{
						$query->whereRaw("name  LIKE '%" . $searchString . "%'");
					})
					->orWhereHas('user_name', function ($query) use ($searchString)
					{
						$query->whereRaw("username  LIKE '%" . $searchString . "%'");
					});
            })->get()->count();
        }
		

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $amenities->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = !is_null($item->main_category)?$item->main_category->name:'';
            $nestedData[] = $item->id;
            $nestedData[] = ((strlen($item->name)>50)?wordwrap(substr($item->name,0,50),20,"<br>\n"):wordwrap($item->name,20,"<br>\n"));
			$nestedData[] = $item->city['name'];
            $nestedData[] = (!is_null($item->user_name)?$item->user_name->username:"");

			if($item->is_sunday_bazar == 1){ $classs="on text-green"; $titles="active"; } else { $classs="off text-danger"; $titles="inactive"; }
			$isSundayBazarLink = '<a onclick="return confirm(\'Are you sure?\')" href="' . URL::to('/') . '/admin/product/is-sunday-bazar-offer/'.$item->id.'" title="'.$titles.'"><i class="fa fa-toggle-'.$classs.'" aria-hidden="true" ></i></a>';
			$nestedData[] = $isSundayBazarLink;

			if($item->is_grocito_exclusive == 1){ $classs="on text-green"; $titles="active"; } else { $classs="off text-danger"; $titles="inactive"; }
			$isGrocitoExclusiveLink = '<a onclick="return confirm(\'Are you sure?\')" href="' . URL::to('/') . '/admin/product/is-grocito-exclusive-offer/'.$item->id.'" title="'.$titles.'"><i class="fa fa-toggle-'.$classs.'" aria-hidden="true" ></i></a>';
			$nestedData[] = $isGrocitoExclusiveLink;



            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if($item->is_admin_approved==1){ $class="on"; $title="active";
             
			} else { $class="off"; $title="inactive";
                   $catalogLink = '<a href="' . URL::to('/') . '/admin/product/upload-catalog/'. $item->id .' " title="Upload Catalog"><i class="fa fa-upload"></i></a>';
			}
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/product/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $ViewLink = '<a href="' . URL::to('/') . '/admin/product/show/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $activateLink = '<a href="' . URL::to('/') . '/admin/product/update-inactive-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $noteLink = '<a href="' . URL::to('/') . '/admin/product-note/'.$item->id.'" title="Create Note"><i class="fa fa-sticky-note">&nbsp;<span class="error">'.((count($item->product_note)>0)?count($item->product_note):'').'</span></i></a>';
			//$nestedData[] = $activateLink .' | '. $editLink . ' | '. $ViewLink." | ".$deleteLink.(($title=='inactive')? " | ".$catalogLink:'')." | ".$noteLink;
			$nestedData[] = $activateLink .' | '. $editLink . ' | '. $ViewLink." | ".$noteLink;

			$data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalAmenities),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);

    }
	
   //get list of record of subadmin...........................................................
    public function getUnverifiedProductData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'products.id',
            1 => 'users.username',
            2 => 'products.name',
            3 => 'products.starting_price',
            4 => 'products.description',
        );
        $totalAmenities = Product::with('user_name','main_category','sub_category','product_note')->where('is_admin_approved',0)->get()->count();
        $totalFiltered  = $totalAmenities;
        $amenities = Product::with('user_name','main_category','sub_category','product_note')->select('products.*')->where('is_admin_approved',0)->orderBy('products.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
			$amenities=$amenities->where(function($query) use ($searchString) {
			   return $query->where('name','LIKE','%'.$searchString.'%')
					 ->orWhere('id',$searchString)
				   ->orWhereHas('main_category', function ($query) use ($searchString)
				   {
					   $query->whereRaw("name  LIKE '%" . $searchString . "%'");
				   })
				   ->orWhereHas('user_name', function ($query) use ($searchString)
				   {
					   $query->whereRaw("username  LIKE '%" . $searchString . "%'");
				   });
			});
			
			$totalFiltered=Product::where('is_admin_approved',0)->where(function($query) use ($searchString) {
                return $query->where('name','LIKE','%'.$searchString.'%')
					  ->orWhere('id',$searchString)
					->orWhereHas('main_category', function ($query) use ($searchString)
					{
						$query->whereRaw("name  LIKE '%" . $searchString . "%'");
					})
					->orWhereHas('user_name', function ($query) use ($searchString)
					{
						$query->whereRaw("username  LIKE '%" . $searchString . "%'");
					});
            })->get()->count();
			
        }
		

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $amenities->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = !is_null($item->main_category)?$item->main_category->name:'';
            $nestedData[] = $item->id;
            $nestedData[] = ((strlen($item->name)>50)?wordwrap(substr($item->name,0,50),20,"<br>\n"):wordwrap($item->name,20,"<br>\n"));
            $nestedData[] = $item->user_name['username'];
            //$nestedData[] = $item->sell_price;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if($item->is_admin_approved==1){ $class="on"; $title="active";
             
			} else { $class="off"; $title="inactive";
                   $catalogLink = '<a href="' . URL::to('/') . '/admin/product/upload-catalog/'. $item->id .' " title="Upload Catalog"><i class="fa fa-upload"></i></a>';
			}
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/product/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $ViewLink = '<a href="' . URL::to('/') . '/admin/product/show/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
			$activateLink = '<a href="javascript:void(0)" onclick="variefy_now('.$item->id.','.$item->user_name->id.')" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
			//$activateLink = '<a href="' . URL::to('/') . '/admin/product/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $noteLink = '<a href="' . URL::to('/') . '/admin/product-note/'.$item->id.'" title="Create Note"><i class="fa fa-sticky-note">&nbsp;<span class="error">'.((count($item->product_note)>0)?count($item->product_note):'').'</span></i></a>';
            $nestedData[] = $activateLink ." | ".$ViewLink." | ". $editLink." | ".$deleteLink." | ".$noteLink;
            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalAmenities),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);

    }

    public function delete()
    {
        $property = Product::findOrFail($_POST['id']);

        if(!empty($property->delete()))
        {

            Session::flash('success_message', 'Product has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the product');
        }
    }

    public function delete_image()
    {
        $property = ProductImage::findOrFail($_POST['id']);

        if(!empty($property->delete()))
        {

            echo json_encode(array('status'=>true,"message"=>"Deleted successfully"));
        }
        else {
            Session::flash('error_message', 'Unable to delete the product');
        }
    }
           
			 
    public function property_details($id)
    {
        $product = Product::with('user_name','main_category','product_image','sub_category','city','product_item')->findOrFail($id);
        return view('admin.products.show',compact('product'));
    }
	function isSundayBazarOffer(Request $request)
	{
		$id= $request->id;
		$response=DB::statement("UPDATE products SET is_sunday_bazar =(CASE WHEN (is_sunday_bazar = 1) THEN '0' ELSE '1' END) where id = $id");
		if($response) {
			Session::flash('success_message', 'Product sunday bazar successfully!');
		}
		else {
			Session::flash('error_message', 'Unable to update status');
		}
		return redirect('/admin/product/product-list');
	}
	function isGrocitoExclusiveOffer(Request $request)
	{
		$id= $request->id;
		$response=DB::statement("UPDATE products SET is_grocito_exclusive =(CASE WHEN (is_grocito_exclusive = 1) THEN '0' ELSE '1' END) where id = $id");
		if($response) {
			Session::flash('success_message', 'Product exclusive successfully!');
		}
		else {
			Session::flash('error_message', 'Unable to update status');
		}
		return redirect('/admin/product/product-list');
	}
	function update_status(Request $request)
    {
		$id= $request->id;
		$seller_id= $request->seller_id;
		$w_commission= $request->w_commission;
		$is_return= $request->is_return;
		$is_exchange= $request->is_exchange;
		$response=DB::statement("UPDATE products SET is_admin_approved =(CASE WHEN (is_admin_approved = 1) THEN '0' ELSE '1' END),commission='$w_commission',is_return='$is_return',is_exchange='$is_exchange' where id = $id");
		//send sms to seller
		$sellerInfo = User::where('id', $seller_id)->first();
		$mmsg = "Congratulations! " . $sellerInfo['username'] . ", \n your product has been approved . \n";
		//$mmsg .= "Order id: $order_number. \n";
		$mmsg .= "\n\n Thanks Grocito";
		Helper::send_msg($sellerInfo['mobile'], $mmsg);

		//insert notification message for seller...
		$notifyObj = new SellerNotification;
		$notifyObj->seller_id = $seller_id;
		$notifyObj->int_val = $id;//product id
		$notifyObj->type = 'product_verify';
		$notifyObj->message = 'Product approved by grocito admin';
		$notifyObj->save();
		if($response) {
			Session::flash('success_message', 'status has been updated successfully!');
		}
		else {
			Session::flash('error_message', 'Unable to update status');
		}
		echo json_encode(array('status'=>1,'message'=>"veryfied successfully"));

    }
	function update_inactive_status(Request $request)
	{
		$id= $request->id;
		$response=DB::statement("UPDATE products SET is_admin_approved =(CASE WHEN (is_admin_approved = 1) THEN '0' ELSE '1' END) where id = $id");
		if($response) {
			Session::flash('success_message', 'status has been updated successfully!');
		}
		else {
			Session::flash('error_message', 'Unable to update status');
		}
		return redirect('/admin/product/product-list');
	}
    ///change subadmin status...................
    
	public function get_sub_category(Request $request)
	{
		$id= $request->input('id');
		$data=SubCategory::where('category_id',$id)->get();
		return view('admin.ajax.sub_category',compact('data'));
	}
	public function get_seller(Request $request)
	{
		$id= $request->input('id');

		$data = UserKyc::where('city_id',$id)->with('user')->get();
		return view('admin.ajax.get_seller',compact('data'));
	}
	public function get_super_category(Request $request)
	{
		$id= $request->input('id');
		$data=SuperSubCategory::where('sub_category_id',$id)->get();
		return view('admin.ajax.sub_category',compact('data'));
	}

	
	public function upload_catalog($id)
	{
		$data= Product::findOrFail($id);
		return view('admin.products.upload_catalog',compact('data'));
	}
	function store_catalog_image(Request $request)
	{
		$id= $request->input('product_id');
		 $productData = array(
            'image'    => $request->input( 'image'),
        );
        $rules = array();
        $data = $request->all();
        $validator = Validator::make($productData, $rules);
        if ($validator->fails()) {
            return redirect('admin/product/upload-catalog/'.$id)->withInput()->withErrors($validator);
        }
		
        //Upload Image
        $image = $request->file('image');
        if($image) {
			$file= $_FILES['image'];
            $photo_name = time() . '-' . $file['name'];
            $path_original = public_path() . '/uploads/seller/catalog/'.$photo_name;
			$this->compressImage($file['tmp_name'],$path_original,50);
			$ratio=16/9;
			$img = Image::make(realpath($path_original));
				if($img->height()>512)
				{
				  $img->resize(null,512,function ($constraint) {
		            $constraint->aspectRatio();
				    });
				 $img->save($path_original);
				}				
			$watermark = "C-".rand(10,99).rand(111,999);
			$this->addTextWatermark($path_original, $watermark, $path_original);
            $productInfo['image']= $photo_name;
            $productInfo['watermark']= $watermark;
			$update_data = Product::find($id)->fill($productInfo);
            $update_data->update();
        }
        Session::flash('success_message', 'Catalog image has been uploaded successfully!');
        return redirect('admin/product/unverified-product-list');
	}

	public function add_size()
	{
		$data= Size::get();
		return view('admin.products.add_size',compact('data'));
	} 
	
	public function delete_size()
	{
		 $property = Size::findOrFail($_POST['id']);
        if(!empty($property->delete()))
        {
            Session::flash('success_message', 'Size has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the size');
        }
	} 
	
	public function store_size(Request $request)
	{
		$data=str_replace('"',"&quot;",$request->input('name'));
		 $sizeData = array(
            'name'    => strtolower($data),
        );
        $rules = array('name'=>"required|unique:sizes,name");
        $validator = Validator::make($sizeData, $rules);
        if ($validator->fails()) {
            return redirect('admin/product/size-list/')->withInput()->withErrors($validator);
        }
         $obj= new Size($sizeData);
		 $obj->save($sizeData);
		  Session::flash('success_message', 'Size has been added successfully!');
		 return redirect('admin/product/size-list');
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
		$gray = imagecolorallocate($image_color, 0x55, 0x55, 0x55);
		$image = imagecreatefromjpeg($src);
		imagecopyresampled($image_color, $image, 0, 0, 0, 0, $width, $height, $width, $height);
		$txtcolor = imagecolorallocate($image_color, 255, 255, 255);
		$font = realpath("public/front/image/MONOFONT.ttf");
		$font_size = 20;
		$bbox = imagettfbbox($font_size, 0, $font, $watermark);
		$x = $bbox[0] + (imagesx($image)) - ($bbox[4] / 2) - 130;
		$y = $bbox[1] + (imagesy($image)) - ($bbox[5] / 2) - 30;
		//imagettftextblur($image_color,$font_size,0,$x + 3,$y + 3,$gray,$font,$watermark,1);
		//imagettftext($image_color, $font_size, 0, $x, $y, $txtcolor, $font, $watermark);
		$font_color = imagecolorallocate($image_color, 255, 255, 255);
		$stroke_color = imagecolorallocate($image_color, 0, 0, 0);
		$this->imagettfstroketext($image_color, 20, 10, $x, $y, $font_color, $stroke_color, $font, $watermark, 2);
		// 1 can be higher to increase blurriness of the shadow

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

//END ----------------------------------------
}
