<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Slider;
use App\PaymentGatwaySetting;
use App\Faq;
use App\Dip;
use App\GiftCard;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class GiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view('admin.gifts.index');
    }
 //get list of record of subadmin...........................................................
    public function getGiftData(Request $request)
    {
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'gift_cards.id',
            1 => 'gift_cards.type',
            2 => 'gift_cards.title',
            3 => 'gift_cards.card_value',
            4 => 'gift_cards.description',
            5 => 'gift_cards.status',
        );

        $totalItems = GiftCard::get()->count();
        $totalFiltered = $totalItems;
        $items = GiftCard::where('id','!=',0);

        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $items->whereRaw("title LIKE '%" . $searchString . "%'")->orWhereHas('main_category', function ($query) use ($searchString)
            {
                $query->whereRaw("categories.name  LIKE '%" . $searchString . "%'");
				
            });
            $totalFiltered = Slider::whereRaw("title LIKE '%" . $searchString . "%'")->orWhereHas('main_category', function ($query) use ($searchString)
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
            $nestedData[] = $item->type;
            $nestedData[] = $item->card_value;
            $nestedData[] = $item->description;
            $nestedData[] = $item->created_at->format('F d, Y');
                if($item->status==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $editLink = '<a href="' . URL::to('/') . '/admin/gift/gift-edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
              $nestedData[] = $editLink." | ".$deleteLink;
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

    public function add()
    {
      return view('admin.gifts.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $sliderData = array(
            'image1'    =>$request->input('image1'),
            'image2'    =>$request->input('image2'),
            'image3'    =>$request->input('image3'),
        );
        $rules = array(
            'link'=>'required',
            'images1'=>'image|mimes:jpeg,png,jpg',
            'images2'=>'image|mimes:jpeg,png,jpg',
            'images3'=>'image|mimes:jpeg,png,jpg',
        );
        $validator = Validator::make($sliderData,$rules);
        if ($validator->fails()) {
            return redirect('admin/gift/add-gift')->withInput()->withErrors($validator);
        }else{
            $slider = new GiftCard($request->all());

            //Upload Image
            $image1 = $request->file('image1');
            $image2 = $request->file('image2');
            $image3 = $request->file('image3');
            $path_original=public_path() . '/admin/uploads/gift_image';
                $file1 = $request->image1;
                $file2 = $request->image2;
                $file3 = $request->image3;

                $photo_name1 = time() . '-' . $file1->getClientOriginalName();
                $file->move($path_original, $photo_name1);
                $slider->image1 = $photo_name1;
                
                $photo_name2 = time() . '-' . $file2->getClientOriginalName();
                $file->move($path_original, $photo_name2);
                $slider->image2 = $photo_name2;
                
                $photo_name3 = time() . '-' . $file3->getClientOriginalName();
                $file->move($path_original, $photo_name3);
                $slider->image1 = $photo_name3;
                
 
            }
            $slider->save();


        // redirect
        Session::flash('success_message', 'Your image has been added successfully');
        return redirect('/admin/dip/dip-list');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {

        // get the testimonial
        $plans = $this->model->findOrFail($id);


        // show the view and pass the nerd to it
        return view('admin.plans.show')
            ->with('plans', $plans);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit_slider($id)
    {
        //$Package = Package::first();
        $slider = Slider::find($id);
        $category_list=Category::get(); 
        return view('admin.slider.edit')->with(['slider'=>$slider,'category_list'=>$category_list]);
    }

    /**
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function updateSlider($id, Request $request)
    {
        // validate
        $slider = Slider::find($id);
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',


            ], [
                'title.required' => 'This field is required.',


            ]);

        if ($validator->fails())
        {
            return redirect('admin/slider/slider-edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $obj=new Slider();
            $obj->title=$request->input('title');
            $setting = Slider::first();

            if($slider) {
                $data =$request->all();
                if ($request->hasFile('images'))
                {
                    $path_original=public_path() . '/admin/uploads/slider_image';
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
                $update_data = Slider::find($slider->id)->fill($data);
                if($request->images){$update_data->images=$photo_name;}
                $update_data->update();
            }
            else
            {
                if ($file = $request->hasFile('images')) {
                    $file = $request->file('images');
                    $fileName = $file->getClientOriginalName();
                    $destinationPath = public_path() . '/admin/uploads/slider_image';
                    $file->move($destinationPath, $fileName);
                    $obj->images = $fileName;
                }
                $obj->save();
            }


            // redirect
            Session::flash('success_message', 'Slider Successfully updated');
            return redirect('admin/slider/slider-list');
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
        $slider = Slider::findOrFail($_POST['id']);
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
        return redirect('/admin/slider/slider-list');
    }

    /**
     * @return mixed
     */

}
