<?php



namespace App\Http\Controllers\Admin; //admin add

use App\Http\Controllers\Controller;   // using controller class

use App\Category;

use App\SubCategory;

use App\SuperSubCategory;

use Illuminate\Support\Facades\Session;

use Illuminate\Http\Request;

use DB;

use URL;

use Excel;

use File;

use Mail;

use Illuminate\Support\Facades\Validator;

class SuperSubCategoryController extends Controller

{



    public function __construct()

    {

    }

    public function index()

    {



        return view('admin.super_sub_categories.index');

    }

    public function create()

    {

        $cateList=category::get();

        $subcateList=SubCategory::get();

        return view('admin.super_sub_categories.create')->with('category_list',$cateList)->with('subcategory_list',$subcateList);

    }

    public function store(Request $request)

    {



        $categoryData = array(

            'name'     => $request->input( 'name'),
           'slug'     => str_slug($request->input( 'name'),'-'),
            'category_id'    => $request->input( 'category_id'),

            'sub_category_id'    => $request->input( 'sub_category_id'),

      
        );

        $rules = array();

        $data = $request->all();

        $validator = Validator::make($categoryData, $rules);

        if ($validator->fails()) {

            return redirect('admin/category/create-super-subcategory')->withInput()->withErrors($validator);

        }

        $category = new SuperSubCategory($request->all());

        

        $category->save();

        Session::flash('success_message', 'Category has been created successfully!');

        return redirect('admin/supersubcategory/super-subcategory-list');

    }

    public function show($id)

    {

        $category = SuperSubCategory::with('main_category','sub_category')->findOrFail($id);

        return view('admin.super_sub_categories.show',compact('category'));

    }



    public function edit($id)

    {

        $category = SuperSubCategory::findOrFail($id);
        $subcategory_list =SubCategory::get();
		$category_list= Category::where('status',1)->get();
        return view('admin.super_sub_categories.edit',compact('category','category_list','subcategory_list'));

    }

    public function update(Request $request, $id)

    {

        $data =$request->all();
        $data['slug']     =str_slug($request->input( 'name'),'-');
        $category = SuperSubCategory::findOrFail($id);

        $rules=array();

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            return redirect('admin/supersubcategory/edit/'.$id)->withInput()->withErrors($validator);

        }

        $category->fill($data)->save();

        // redirect

        Session::flash('success_message', 'Sub Category has been updated successfully!');

        return redirect('admin/supersubcategory/super-subcategory-list');

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

    public function getSuperSubCategoryData(Request $request)
    {

        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'super_sub_categories.id',
            1 => 'super_sub_categories.category_id',
            2 => 'super_sub_categories.sub_category_id',
            3 => 'super_sub_categories.name',
            4 => 'super_sub_categories.created_at',
            5 => '',
        );

        $totalAmenities = SuperSubCategory::where('status',1)->get()->count();

        $totalFiltered  = $totalAmenities;

        $category = SuperSubCategory::with('sub_category','main_category')->select('super_sub_categories.*')->orderBy('super_sub_categories.id', 'desc');

        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);

        $searchString = str_replace("'", "\'", $searchString);

        if (!empty($requestData['search']['value']))

        {

           $category->where('name','LIKE','%'.$searchString.'%')->orWhereHas('main_category', function ($query) use ($searchString)

            {

                 $query->whereRaw("categories.name LIKE '%" . $searchString . "%'");

            });

		   

            $totalFiltered = SuperSubCategory::where('name','LIKE','%'.$searchString.'%')->orWhereHas('main_category', function ($query) use ($searchString)

            {

                 $query->whereRaw("categories.name LIKE '%" . $searchString . "%'");

            })

			->get()->count();

        }



        $orderColumn = $columns[$requestData['order'][0]['column']];

        $orderColumnDir = $requestData['order'][0]['dir'];

        $limit = $requestData['length'];

        $offset = $requestData['start'];

        $users = $category->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();

        $data = array();

        $i = $offset;



        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->main_category->name;
            $nestedData[] = $item->sub_category->name;
            $nestedData[] = $item->name;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if($item->status==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/supersubcategory/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/supersubcategory/show/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $activateLink = '<a onclick="return confirm(\'Are you sure to change the status of category?\')" href="' . URL::to('/') . '/admin/subcategory/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
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

        return redirect('/admin/subcategory/category-list');

    }

	

	function get_sub_category($id)

	{


	  $subcategory_list=SubCategory::where('category_id',$id)->get();

	  return view('admin.super_sub_categories.sub_cat_ajax',compact('subcategory_list'));

	}

}

