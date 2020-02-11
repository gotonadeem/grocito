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
use App\Category;
use App\UserNotification;
use DB;
use URL;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserNotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
    public function index()
    {
        return view('admin.user_notification.index');
    }
   
    //get list of record of subadmin...........................................................
    public function getUserNotificationData(Request $request)
    {
        $requestData = $request->toArray();
        $columns = array(
            // column index  => database column name
            0 => 'user_notifications.id',
            1 => 'user_notifications.image',
            2 => 'user_notifications.title',
        );

        $totalItems = UserNotification::get()->count();
        $totalFiltered = $totalItems;
        $items = UserNotification::where('status',0)->orWhere('status',1);

        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $items->whereRaw("title LIKE '%" . $searchString . "%'")->orWhereHas('main_category', function ($query) use ($searchString)
            {
                $query->whereRaw("user_notifications.name  LIKE '%" . $searchString . "%'");
                
            });
            $totalFiltered = UserNotification::whereRaw("title LIKE '%" . $searchString . "%'")->orWhereHas('main_category', function ($query) use ($searchString)
            {
                $query->whereRaw("user_notifications.name  LIKE '%" . $searchString . "%'");
                
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
            //dd($item);
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = strip_tags($item->title);
            $nestedData[] = (!is_null($item->description)?$item->description:'Description Not found');
             $img=empty($item->image) ? 'Not Attached':$item->image;
            $nestedData[] = '<img src="' . URL::to('/') . '/public/admin/uploads/user_notification/'.$img.'" height="100" width="150">';
            $nestedData[] = $item->created_at->format('F d, Y');
                 
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
              $toPublish = '<a href="javascript:void(0);" onclick="publishItem('.$item->id .',this)" title="Publish"><i class="btn btn-success" aria-hidden="true" >Publish</i></a>';
              $published = '<a href="javascript:void(0);" title="published"><i class="btn btn-success" aria-hidden="true" >published</i></a>';
              $activate =  (($item->status==1)? $published :  $toPublish);
              $editLink = '<a href="' . URL::to('/') . '/admin/site-setting/edit-notification/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
             
              $nestedData[] =    $activate." | ".$editLink." | ".$deleteLink;
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



    public function add_notification()
    {
       $notification=UserNotification::get(); 
      return view('admin.user_notification.create',compact('notification'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $sliderData = array(
           'title'  =>$request->input('title'),
           'description'  =>$request->input('description'), 
           //'image'  =>$request->file( 'image'),
                
        );
        $rules = array(
            //'image' => 'required|mimes:jpeg,jpg,png,gif',           
            'title' => 'required',
            'description' => 'required',
        );
        $data = $request->all();
        $validator = Validator::make($sliderData,$rules);
        if ($validator->fails()) {
            return redirect('admin/site-setting/add-user-notification')->withInput()->withErrors($validator);
        }else{
            $banner = new UserNotification($request->all());
            
            //Upload Image
            $image = $request->file('image');
            $path_original=public_path() . '/admin/uploads/user_notification';
                $file = $request->image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $banner->image = $photo_name;
                $banner->save();
                Session::flash('success_message', 'Your Notification has been added successfully');
                return redirect('/admin/site-setting/user-notification');
            }
    }
   

    public function edit($id)
    {
        $notification = UserNotification::find($id);
        return view('admin.user_notification.edit' , compact('notification'));
    }


    public function update($id, Request $request)
    {
        // validate
        $slider = UserNotification::find($id);
        $validator = Validator::make($request->all(),
            [
                'title' => 'required',
                'description' =>'required',
                //'image' => 'required|mimes:jpeg,jpg,png,gif',
            ], [
                'title.required' => 'This field is required.',
                'description.required' => 'This field is required',
            ]);

        if ($validator->fails())
        {
            return redirect('admin/site-setting/edit-notification/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $obj=new UserNotification();
            $obj->title=$request->input('title');
            $setting = UserNotification::first();

            if($slider) {
                $data =$request->all();
                if ($request->hasFile('image'))
                {
                    $path_original=public_path() . '/admin/uploads/user_notification';
                    $file = $request->image;

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
                $update_data = UserNotification::find($slider->id)->fill($data);
                if($request->image){$update_data->image=$photo_name;}
                $update_data->update();
            }
            else
            {
                if ($file = $request->hasFile('image')) {
                    $file = $request->file('image');
                    $fileName = $file->getClientOriginalName();
                    $destinationPath = public_path() . '/admin/uploads/user_notification';
                    $file->move($destinationPath, $fileName);
                    $obj->images = $fileName;
                }
                $obj->save();
            }


            // redirect
            Session::flash('success_message', 'Notification Successfully updated');
            return redirect('admin/site-setting/user-notification');
        }

    }

   

    public function delete()
    {
        $notification = UserNotification::findOrFail($_POST['id']);
        if(!empty($notification->delete()))
        {
            Session::flash('success_message', 'Notification has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the Notification');
        }
    }

    public function publish(Request $request){
          $id = $request->id;
          if($id){
            DB::table('user_notifications')->where('id', $id)->update(['status' => 1]);

          }
    }

   





}
