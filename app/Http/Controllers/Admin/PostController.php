<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Post;
use DB;
use URL;
use Excel;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function index()
    {
        return view("admin.posts.index");
    }

    //get list of record of subadmin...........................................................
    public function getPostData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'posts.id',
            1 => 'posts.title',
            2 => 'posts.description',
            4 => 'posts.created_at',

        );
        $totalUsers = Post::where('status', 'yes')->get()->count();
        $totalFiltered = $totalUsers;
        $users = Post::where('status', 1)->orderBy('id', 'desc');
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
            $nestedData[] = $item->title;
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
        return view("admin.posts.create");
    }

    public function store(Request $request)
    {

        $testimonialsData = array(
            'title'     => $request->input( 'name'),
            'description'    => $request->input( 'description'),
        );
        $rules = array();
        $data = $request->all();
        $validator = Validator::make($testimonialsData, $rules);
        if ($validator->fails()) {
            return redirect('admin/posts/create-post')->withInput()->withErrors($validator);
        }
        $post = new Post($request->all());
        //Upload Image
        $image = $request->file('image');
        if($image) {
            $path_original = public_path() . '/admin/uploads/post';
            $file = $request->image;
            $photo_name = time() . '-' . $file->getClientOriginalName();
            $file->move($path_original, $photo_name);
            $post->image = $photo_name;
        }
        $post->save();
        Session::flash('success_message', 'Post has been created successfully!');
        return redirect('admin/post/post-list');
    }
}