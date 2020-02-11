<?php

namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\User;
use App\Category;
use App\Product;
use DB;
use URL;
use Image;
use Helper;
use Excel;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class CategoryController extends Controller
{

    public function __construct()
    {
		
    }
    public function index()
    {

        return view('admin.categories.index');
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
	
    public function create()
    {
        return view('admin.categories.create');
    }
    public function store(Request $request)
    {
        $categoryData = array(
            'name'     => $request->input('name'),
            'slug'     => str_slug($request->input( 'name'),'-'),
            'image'    => $request->file('image'),
            'app_icon'    => $request->file('app_icon')
        );
        $rules = array('name'=>'required|unique:categories,name', 'image' => 'mimes:jpeg,jpg,png,gif|required', 'app_icon' => 'mimes:jpeg,jpg,png,gif|required');
        /*$rules = array(
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000' // max 10000kb
        );*/
        $data = $request->all();
        $validator = Validator::make($categoryData, $rules);
        if ($validator->fails()) {
            return redirect('admin/category/create-category')->withInput()->withErrors($validator);
        }
		
		
        $category = new Category($categoryData);
        //Upload Image
         //Upload Image
        $image = $request->file('image');
        if($image) {
			$file = $request->image;
            $photo_name = time() . '-' . $file->getClientOriginalName();
            $path_original = public_path() . '/admin/uploads/category/'.$photo_name;
            $fileTemp= $_FILES['image'];
            //$file->move($path_original, $photo_name);
			$ratio=16/9;
			$this->compressImage($fileTemp['tmp_name'],$path_original,100);			
			$img = Image::make(realpath($path_original));
			$img->fit($img->width(), intval($img->width() / $ratio));
			$img->resize(512, 288);
			$img->save($path_original);
            $category->image = $photo_name;
        }
        $appicon = $request->file('app_icon');
        if($appicon) {
            $file = $request->app_icon;
            $photo_name = time() . '-' . $file->getClientOriginalName();
            $path_original = public_path() . '/admin/uploads/category_icon/'.$photo_name;
            $fileTemp= $_FILES['app_icon'];
            //$file->move($path_original, $photo_name);
            $ratio=16/9;
            $this->compressImage($fileTemp['tmp_name'],$path_original,100);
            $img = Image::make(realpath($path_original));
            $img->fit($img->width(), intval($img->width() / $ratio));
            $img->resize(512, 288);
            $img->save($path_original);
            $category->app_icon = $photo_name;
        }
        $category->save();
        Session::flash('success_message', 'Category has been created successfully!');
        return redirect('admin/category/category-list');
    }
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.show',compact('category'));
    }
	
	function product_ajax_list(Request $request)
	{
		$requestData = $_REQUEST;
		$id= $request->input('category_id');
        $columns = array(
            0 => 'products.id',
            1 => 'users.username',
            2 => 'products.name',
            3 => 'products.starting_price',
            4 => 'products.description',
        );
        $totalAmenities = Product::with('user_name','main_category','sub_category')->where('category_id',$id)->where('is_admin_approved',1)->get()->count();
        $totalFiltered  = $totalAmenities;
        $amenities = Product::with('user_name','main_category','sub_category')->select('products.*')->where('category_id',$id)->where('is_admin_approved',1)->orderBy('products.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $amenities->where('name','LIKE','%'.$searchString.'%')->orWhere('watermark','LIKE','%'.$searchString.'%');
            $totalFiltered = Product::where('name','LIKE','%'.$searchString.'%')->orWhere('watermark','LIKE','%'.$searchString.'%')
                ->get()->count();
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
            $nestedData[] = $item->user_name->username;
            $nestedData[] = $item->user_name->mobile;
            $nestedData[] = !is_null($item->main_category)?$item->main_category->name:'';
            $nestedData[] = ((strlen($item->name)>50)?wordwrap(substr($item->name,0,50),20,"<br>\n"):wordwrap($item->name,20,"<br>\n"));
            $nestedData[] = $item->starting_price;
            $nestedData[] = $item->sell_price;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if($item->is_admin_approved==1){ $class="on"; $title="active";
             
			} else { $class="off"; $title="inactive";
                   $catalogLink = '<a href="' . URL::to('/') . '/admin/product/upload-catalog/'. $item->id .' " title="Upload Catalog"><i class="fa fa-upload"></i></a>';
			}
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/product/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $ViewLink = '<a href="' . URL::to('/') . '/admin/product/show/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $activateLink = '<a href="' . URL::to('/') . '/admin/product/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $nestedData[] = (($item->image)?$activateLink ." | ":'').$ViewLink." | ". $editLink." | ".$deleteLink.(($title=='inactive')? " | ".$catalogLink:'');
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

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit',compact('category'));
    }
    public function update(Request $request, $id)
    {
         $data =$request->all();
         $category = Category::findOrFail($id);
         $rules=array();
         $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('admin/category/edit/'.$id)->withInput()->withErrors($validator);
        }
         if($request->hasFile('image')) {
			$file = $request->image;
            $photo_name = time(). '-' .$file->getClientOriginalName();
            $path_original = public_path() . '/admin/uploads/category/'.$photo_name;
            $fileTemp= $_FILES['image'];
            //$file->move($path_original,$photo_name);
			$ratio=16/9;
			$this->compressImage($fileTemp['tmp_name'],$path_original,100);			
			$img = Image::make(realpath($path_original));
			$img->fit($img->width(), intval($img->width() / $ratio));
			$img->resize(512, 288);
			$img->save($path_original);
            if($request->old_img!='') {
                try {
                    unlink($path_original . $request->old_img);
                } catch (\Exception $e) {
                }
            }
            $data['image'] = $photo_name;
        }
        if($request->hasFile('app_icon')) {
            $file = $request->app_icon;
            $photo_name = time(). '-' .$file->getClientOriginalName();
            $path_original = public_path() . '/admin/uploads/category_icon/'.$photo_name;
            $fileTemp= $_FILES['app_icon'];
            //$file->move($path_original,$photo_name);
            $ratio=16/9;
            $this->compressImage($fileTemp['tmp_name'],$path_original,100);
            $img = Image::make(realpath($path_original));
            $img->fit($img->width(), intval($img->width() / $ratio));
            $img->resize(512, 288);
            $img->save($path_original);
            if($request->old_icon!='') {
                try {
                    unlink($path_original . $request->old_icon);
                } catch (\Exception $e) {
                }
            }
            $data['app_icon'] = $photo_name;
        }
        $category->fill($data)->save();
        // redirect
        Session::flash('success_message', 'Category has been updated successfully!');
        return redirect('admin/category/category-list');
    }

    public function delete($id)
    {
        if($art = Category::find($id)){
            $art->delete();
            $data =  response('deleted',200);
            Session::flash('success_message', 'Category has been deleted successfully!');
        }else{
            $data = response('some_thing_is_wrong',500);
            Session::flash('success_message', 'Please Try Again!');
        }
        return $data;
    }

    //get list of record of subadmin...........................................................
    public function getCategoryData(Request $request)
    {
       $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'categories.id',
            1 => 'categories.name',
            2 => 'categories.created_at',
            4 => '',

        );
        $totalAmenities = Category::where('status',1)->get()->count();
        $totalFiltered  = $totalAmenities;
        $amenities = Category::select('categories.*')->orderBy('categories.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
           $amenities->where("name","LIKE",'%'.$searchString.'%');
		   $totalFiltered = Category::where("name","LIKE",'%'.$searchString.'%')
                ->get()->count();
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $amenities->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
			     if($item->is_special)
				 {
					 $checked="checked";
				 }
				 else
				 {
					 $checked="";
				 }
            $i++;
            $nestedData = array();
			$product_count=Helper::get_product_count_by_category('category_id',$item->id);
            $nestedData[] = $i;
		    $nestedData[] = $item->name."(".$product_count.")";
		    $nestedData[] = "<input type='text' name='position' value=".$item->position." onblur='set_position(".$item->id.",this.value)'>";
		    $nestedData[] = "<input type='radio' name='is_special' $checked value='1'  onclick='is_special(".$item->id.")'>";
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if($item->status==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/category/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/category/show/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            
			$viewProductLink = '<a href="' . URL::to('/') . '/admin/category/product-list/'. $item->id .' " title="View Products">View Products</a>';
			
            $activateLink = '<a onclick="return confirm(\'Are you sure to change the status of category?\')" href="' . URL::to('/') . '/admin/category/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $nestedData[] = $activateLink ." | ". $editLink." | ". $viewLink ." | ". $deleteLink;
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

    function update_status($id=null)
    {
        $response=DB::statement("UPDATE categories SET status =(CASE WHEN (status = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'Status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status!');
        }
        return redirect('/admin/category/category-list');
    }
	
	function position(Request $request)
	{
		 $value=$request->input('value');
		 $position=$request->input('position');
		 $response=DB::statement("UPDATE categories SET position ='$position' where id = $value");
	}
    
	function special(Request $request)
	{
		 $value=$request->input('value');
		 DB::statement("UPDATE categories SET is_special ='0'");
		 $response=DB::statement("UPDATE categories SET is_special ='1' where id = '$value'");
	}
    
	
}
