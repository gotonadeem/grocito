<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Banner;
use App\PaymentGatwaySetting;
use App\Faq;
use App\Category;
use App\Product;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view('admin.banner.index');
    }
    public function addBanner()
    {
        $category_list=Category::get();
        return view('admin.banner.create',compact('category_list'));
    }
    public function store(Request $request)
    {

        $sliderData = array(
            'title'     =>$request->input( 'title'),
            'link'     =>$request->input( 'link'),
            'images'    =>$request->input( 'images'),
                
        );
        $rules = array(
            'title'=>'required',
            'link'=>'required',
            'images'=>'image|mimes:jpeg,png,jpg',

        )   ;
        $validator = Validator::make($sliderData,$rules);
        if ($validator->fails()) {
            return redirect('admin/banner/add-banner')->withInput()->withErrors($validator);
        }else{
            $banner = new Banner($request->all());

            //Upload Image
            $image = $request->file('image');
            $path_original=public_path() . '/admin/uploads/banner_image';
                $file = $request->images;

                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $banner->images = $photo_name;
                $banner->type = $request->input('type');
                $banner->save();
                Session::flash('success_message', 'Your Banner has been added successfully');
                return redirect('/admin/banner/banner-list');
            }

    }


    //get list of record of subadmin...........................................................
    public function getBannerData(Request $request)
    {
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'banners.id',
            1 => 'banners.images',
            2 => 'banners.title',
        );

        $totalItems = Banner::where('type','bottom')->orWhere('type','middle')->orWhere('type','offer_banner_first')->get()->count();
        $totalFiltered = $totalItems;
        $items = Banner::where('type','bottom')->orWhere('type','middle')->orWhere('type','offer_banner_first')->with('main_category')->where('id','!=',0);

        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $items->whereRaw("title LIKE '%" . $searchString . "%'")->orWhereHas('main_category', function ($query) use ($searchString)
            {
                $query->whereRaw("categories.name  LIKE '%" . $searchString . "%'");
				
            });
            $totalFiltered = Banner::whereRaw("title LIKE '%" . $searchString . "%'")->orWhereHas('main_category', function ($query) use ($searchString)
            {
                $query->whereRaw("categories.name  LIKE '%" . $searchString . "%'");
				
            })->get()->count();
        }

        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];

        $items=$items->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($items as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = strip_tags($item->title);
            $nestedData[] = ucfirst(str_replace('_',' ',$item->type)) ;
            $nestedData[] = (!is_null($item->main_category)?$item->main_category->name:'');
             $img=empty($item->images) ? '':$item->images;
            $nestedData[] = '<img src="' . URL::to('/') . '/public/admin/uploads/banner_image/'.$img.'" height="100" width="150">';

            $nestedData[] = $item->created_at->format('F d, Y');
                if($item->status==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $editLink = '<a href="' . URL::to('/') . '/admin/banner/banner-edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $activateLink = '<a href="' . URL::to('/') . '/admin/banner/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $nestedData[] = $activateLink ." | ". $editLink." | ".$deleteLink;
              $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalItems),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $plans = $this->model->findOrFail($id);
        return view('admin.plans.show')
            ->with('plans', $plans);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit_banner($id)
    {
        //$Package = Package::first();
        $banner = Banner::find($id);
        $category_list=Category::get();
        $product_list=Product::where('category_id',$banner->link)->get();
        return view('admin.banner.edit')->with(['banner'=>$banner,'category_list'=>$category_list,'product_list'=>$product_list]);
    }

    /**
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateBanner($id, Request $request)
    {
        // validate
        $banner = Banner::find($id);
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',


            ], [
                'title.required' => 'This field is required.',


            ]);

        if ($validator->fails())
        {
            return redirect('admin/banner/banner-edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $obj=new Banner();
            $obj->title=$request->input('title');
            $setting = Banner::first();

            if($banner) {
                $data =$request->all();
                if ($request->hasFile('images'))
                {
                    $path_original=public_path() . '/admin/uploads/banner_image';
                    $file = $request->images;

                    $photo_name = time() . '-' . $file->getClientOriginalName();
                    $file->move($path_original, $photo_name);
                    $data['old_images'] = $photo_name;
                    if ($request->old_img != '') {
                        try {
                            unlink($path_original . $request->old_img);

                        } catch (\Exception $e) {
                        }
                    }
                }
                $update_data = Banner::find($banner->id)->fill($data);
                if($request->images){$update_data->images=$photo_name;}
                $update_data->update();
            }
            else
            {
                if ($file = $request->hasFile('images')) {
                    $file = $request->file('images');
                    $fileName = $file->getClientOriginalName();
                    $destinationPath = public_path() . '/admin/uploads/banner_image';
                    $file->move($destinationPath, $fileName);
                    $obj->images = $fileName;
                }
                $obj->save();
            }


            // redirect
            Session::flash('success_message', 'Banner Successfully updated');
            return redirect('admin/banner/banner-list');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if($plans = $this->model->find($id)){
            $plans->delete();
            $data =  response('deleted',200);
        }else{
            $data = response('some_thing_is_wrong',500);
        }
        return $data;

    }
    public function delete()
    {
        $slider = Banner::findOrFail($_POST['id']);
        if(!empty($slider->delete()))
        {
            Session::flash('success_message', 'Slider has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the Slider');
        }
    }
    function update_status($id=null)
    {
        $response=DB::statement("UPDATE banners SET status =(CASE WHEN (status = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/banner/banner-list');
    }

    /**
     * @return mixed
     */

}
