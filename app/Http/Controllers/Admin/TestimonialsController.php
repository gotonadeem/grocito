<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Testimonial;
use DB;
use URL;
use Excel;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class TestimonialsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
        return view("admin.testimonials.index");
    }

    //get list of record of subadmin...........................................................
    public function getTestimonialsData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'testimonials.id',
            1 => 'testimonials.name',
            2 => 'testimonials.address',
            3 => 'testimonials.description',
            4 => 'testimonials.created_at',
        );
        $totalUsers = Testimonial::get()->count();
        $totalFiltered = $totalUsers;
        $users = Testimonial::where('testimonials.status', 1)->orderBy('testimonials.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value'])) {
            $users->where('username', 'LIKE', '%' . $searchString . '%')->orWhereHas('activation_wallet', function ($query) use ($searchString) {
                $query->whereRaw("activation_wallets.coins  LIKE '%" . $searchString . "%'");
            });
            $totalFiltered = User::with('user_profile,activation_wallet,user_kyc')->where('username', 'LIKE', '%' . $searchString . '%')->orWhereHas('activation_wallet', function ($query) use ($searchString) {
                $query->whereRaw("activation_wallets.coins LIKE '%" . $searchString . "%'");
            })
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
            $nestedData[] = $item->name;
            $nestedData[] = $item->address;
            $nestedData[] = $item->description;
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
            $editLink = '<a href="' . URL::to('/') . '/admin/user/edit/' . $item->id . ' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            $viewLink = '<a href="' . URL::to('/') . '/admin/user/view/' . $item->id . ' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $activateLink = '<a href="' . URL::to('/') . '/admin/user/update-status/' . $item->id . '" title="' . $title . '"><i class="fa fa-toggle-' . $class . '" aria-hidden="true" ></i></a>';
            $nestedData[] = $activateLink . " | " . $editLink . " | " .$viewLink. " | ". $deleteLink;

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

    public function create()
    {
        return view('admin.testimonials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $testimonialsData = array(
            'name'     => $request->input( 'name'),
            'address'    => $request->input( 'address'),
            'description'    => $request->input( 'description'),
        );
        $rules = array();
        $data = $request->all();
        $validator = Validator::make($testimonialsData, $rules);
        if ($validator->fails()) {
            return redirect()->route('admin.amenities.create')->withInput()->withErrors($validator);
        }
        $testimonial = new Testimonial($request->all());
        //Upload Image
            $image = $request->file('image');
            if($image) {
                $path_original = public_path() . '/admin/uploads/testimonial';
                $file = $request->image;
                $photo_name = time() . '-' . $file->getClientOriginalName();
                $file->move($path_original, $photo_name);
                $testimonial->image = $photo_name;
            }
        $testimonial->save();
        Session::flash('success_message', 'Testimonial has been created successfully!');
        return redirect('admin/testimonials/testimonials-list');
    }
}
