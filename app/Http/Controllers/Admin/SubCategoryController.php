<?php

namespace App\Http\Controllers\Admin; //admin add
use App\Http\Controllers\Controller;   // using controller class
use App\Category;
use App\SubCategory;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use DB;
use URL;
use Excel;
use Helper;
use App\Product;
use File;
use Image;
use Mail;
use Illuminate\Support\Facades\Validator;
class SubCategoryController extends Controller
{

    public function __construct()
    {
    }
    public function index()
    {

        return view('admin.sub_categories.index');
    }
    public function create()
    {
        $cateList=category::get();
        return view('admin.sub_categories.create')->with('category_list',$cateList);
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
	
    public function store(Request $request)
    {
        $category= Category::select('slug')->where('id',$request->input('category_id'))->first();
        $categoryData = array(
            'name'     => $request->input( 'name'),
            'slug'     => str_slug($request->input( 'name'),'-'),
            'category_slug'=>$category->slug,
            'category_id'    => $request->input( 'category_id'),
        );
        $rules = array();
        $data = $request->all();
        $validator = Validator::make($categoryData, $rules);
        if ($validator->fails()) {
            return redirect('admin/category/create-category')->withInput()->withErrors($validator);
        }
        $category = new SubCategory($categoryData);
		$image = $request->file('image');
        if($image) {
			$file = $request->image;
            $photo_name = time() . '-' . $file->getClientOriginalName();
            $path_original = public_path() . '/admin/uploads/category/'.$photo_name;
            $fileTemp= $_FILES['image'];
            //$file->move($path_original, $photo_name);
			$ratio=16/9;
			$this->compressImage($fileTemp['tmp_name'],$path_original,50);
			$img = Image::make(realpath($path_original));
			//$img->fit($img->width(), intval($img->width() / $ratio));
			//$img->resize(512, 288);
            $img->resize(null,512,function ($constraint) {
                $constraint->aspectRatio();
            });
			$img->save($path_original);
            $category->image = $photo_name;
        }
        $category->save();
        Session::flash('success_message', 'Category has been created successfully!');
        return redirect('admin/subcategory/subcategory-list');
    }
    public function show($id)
    {
        $category = SubCategory::with('main_category')->findOrFail($id);
        return view('admin.sub_categories.show',compact('category'));
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
        $totalAmenities = Product::with('user_name','main_category','sub_category')->where('sub_category_id',$id)->where('is_admin_approved',1)->get()->count();
        $totalFiltered  = $totalAmenities;
        $amenities = Product::with('user_name','main_category','sub_category')->select('products.*')->where('sub_category_id',$id)->where('is_admin_approved',1)->orderBy('products.id', 'desc');
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
        $category = SubCategory::findOrFail($id);
		$category_list= Category::where('status',1)->get();
        return view('admin.sub_categories.edit',compact('category'))->with('category_list',$category_list);
    }
    public function update(Request $request, $id)
    {
        $data =$request->all();
        $category = SubCategory::findOrFail($id);
        $rules=array();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('admin/subcategory/edit/'.$id)->withInput()->withErrors($validator);
        }
		$image = $request->file('image');
        if($image) {
			$file = $request->image;
            $photo_name = time() . '-' . $file->getClientOriginalName();
            $path_original = public_path() . '/admin/uploads/category/'.$photo_name;
            $fileTemp= $_FILES['image'];
            //$file->move($path_original, $photo_name);
			$ratio=16/9;
			$this->compressImage($fileTemp['tmp_name'],$path_original,50);
			$img = Image::make(realpath($path_original));
			//$img->fit($img->width(), intval($img->width() / $ratio));

            $img->resize(null,512,function ($constraint) {
                $constraint->aspectRatio();
            });
			$img->save($path_original);
            $data['image'] = $photo_name;
        }
        $category->fill($data)->save();
        // redirect
        Session::flash('success_message', 'Sub Category has been updated successfully!');
        return redirect('admin/subcategory/subcategory-list');
    }

    public function delete($id)
    {
        if($art = SubCategory::find($id)){
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
    public function getSubCategoryData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'sub_categories.id',
            1 => '',
            2 => 'sub_categories.name',
            3 => 'sub_categories.created_at',
            4 => '',

        );
        $totalCategories = SubCategory::where('status',1)->get()->count();
        $totalFiltered  = $totalCategories;
        $category = SubCategory::with('main_category')->select('sub_categories.*')->orderBy('sub_categories.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
           $category->where('name','LIKE','%'.$searchString.'%')->orWhereHas('main_category', function ($query) use ($searchString)
            {
                 $query->whereRaw("categories.name LIKE '%" . $searchString . "%'");
            });
		   
            $totalFiltered = SubCategory::with('main_category')->where('name','LIKE','%'.$searchString.'%')->orWhereHas('main_category', function ($query) use ($searchString)
            {
                 $query->whereRaw("categories.name LIKE '%" . $searchString . "%'");
            }) ->get()->count();
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $categories = $category->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;

        foreach ($categories as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = (!is_null($item->main_category)?$item->main_category->name:'');
            $nestedData[] = $item->name."(".Helper::get_product_count_by_category('sub_category_id',$item->id).")";
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if($item->status==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/subcategory/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/subcategory/show/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $activateLink = '<a onclick="return confirm(\'Are you sure to change the status of category?\')" href="' . URL::to('/') . '/admin/subcategory/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $nestedData[] = $activateLink ." | ". $editLink." | ". $viewLink ." | ". $deleteLink;
            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalCategories),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        echo json_encode($json_data);

    }
	
	

    function update_status($id=null)
    {
        $response=DB::statement("UPDATE sub_categories SET status =(CASE WHEN (status = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'Status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status!');
        }
        return redirect('/admin/subcategory/subcategory-list');
    }
}
