<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Notice;
use DB;
use PDF;
use URL;
use DNS1D;
use DNS2D;
use Excel;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class NoticeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
        return view("admin.notice.index");
    }

    //get list of record of subadmin...........................................................
    public function getNoticeData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'notices.id',
            1 => 'notices.heading',
            2 => 'notices.description',
            4 => 'notices.created_at',

        );
        $totalUsers = Notice::get()->count();
        $totalFiltered = $totalUsers;
        $users = Notice::orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {
           
		    $users->where('heading', 'LIKE', '%' . $searchString . '%');
            $totalFiltered = Notice::where('heading', 'LIKE', '%' . $searchString . '%')
                ->get()->count();
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = $users->offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->heading;
            $nestedData[] = ((strlen($item->description)>30)?substr($item->description,0,30)."...":$item->description);
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if ($item->status == 1) {
                $class = "on";
                $title = "active";
            } else {
                $class = "off";
                $title = "inactive";
            }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            $editLink = '<a href="' . URL::to('/') . '/admin/notice/edit/' . $item->id . ' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/notice/view/' . $item->id . ' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $activateLink = '<a href="' . URL::to('/') . '/admin/notice/update-status/' . $item->id . '" title="' . $title . '"><i class="fa fa-toggle-' . $class . '" aria-hidden="true" ></i></a>';
            $nestedData[] = $activateLink . " | " . $editLink . " | " .$viewLink." | ". $deleteLink;


            $data[] = $nestedData;

        }
        $json_data = array(
            "draw" => intval($requestData['draw']),
            "recordsTotal" => intval($totalUsers),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);

    }

    function create()
    {
        return view("admin.notice.add");
    }
	
	function view($id)
	{
		$data=Notice::findOrFail($id);
	    return view("admin.notice.show",compact('data'));
	}

   function edit($id)
	{
		$data=Notice::findOrFail($id);
	    return view("admin.notice.edit",compact('data'));
	}
	
	
	 public function update($id, Request $request)
    {
        // validate
        $faq = Notice::find($id);
        $validator = Validator::make($request->all(),
            [
                'heading' => 'required',
                'description' => 'required'


            ], [
                'title.required' => 'This field is required.',

            ]);

        if ($validator->fails())
        {
            return redirect('admin/notice/edit/'.$id)->withInput()->withErrors($validator);
        }
        else
        {
            $data=Input::all();
            $faq->fill($data)->save();
            // redirect
            Session::flash('success_message', 'Notice Successfully updated');
            return redirect('admin/notice/notice-list');
        }

    }

    public function store(Request $request)
    {

        $noticeData = array(
            'heading'     => $request->input( 'heading'),
            'description'    => $request->input( 'description'),
            //'created_at'    => date("Y-m-d h:i:a"),
        );
        $rules = array(
		'heading'=>'required',
		'description'=>'required',
		);
        $data = $request->all();
        $validator = Validator::make($noticeData, $rules);
        if ($validator->fails()) {
            return redirect('admin/notice/create-notice')->withInput()->withErrors($validator);
        }
        $notice = new Notice($request->all());
        //Upload Image
        // $image = $request->file('image');
        // if($image) {
            // $path_original = public_path() . '/admin/uploads/post';
            // $file = $request->image;
            // $photo_name = time() . '-' . $file->getClientOriginalName();
            // $file->move($path_original, $photo_name);
            // $post->image = $photo_name;
        // }
        $notice->save();
        Session::flash('success_message', 'Notice has been created successfully!');
        return redirect('admin/notice/notice-list');
    }
	
	function update_status($id=null)
    {
        $response=DB::statement("UPDATE notices SET status =(CASE WHEN (status = 1) THEN '0' ELSE '1' END) where id = $id");
        if($response) {
            Session::flash('success_message', 'status has been updated successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to update status');
        }
        return redirect('/admin/notice/notice-list');
    }
	///delete user...................
    public function delete()
    {
        $notice = Notice::findOrFail($_POST['id']);
        if(!empty($notice->delete()))
        {
            Session::flash('success_message', 'Notice has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the notice');
        }
    }
	public function generate_pdf()
	{

	     	 // $data['dfdfdf']="sfsfsfd";
		   //Send data to the view using loadView function of PDF facade
			// $pdf = PDF::loadView('pdf.offer', $data);
			//If you want to store the generated pdf to the server then you can use the store function
			// $pdf->save(storage_path().'_filename.pdf');
			//Finally, you can download the file using download function
			// return $pdf->download('offer.pdf');
	}
	
}