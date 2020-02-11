<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\GeneralSetting;
use App\PaymentGatwaySetting;
use App\Manifest;
use App\Section;
use Redirect;
use DB;
use URL;
use Helper;
use DNS1D;
use DNS2D;
use PDF;
use File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ManifestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }
	
	public function index()
	{
		return view('admin.manifest.index');
   
	}
	
	public function getManifestData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'manifests.seller_id',
            1 => 'manifests.service',
        );
        $totalAmenities = Manifest::with('seller')->get()->count();
        $totalFiltered  = $totalAmenities;
        $amenities = Manifest::with('seller')->select('*')->orderBy('manifests.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $amenities->orWhereHas('seller', function ($query) use ($searchString)
            {
                $query->whereRaw("users.email  LIKE '%" . $searchString . "%'");
				
            });
            $totalFiltered = Manifest::with('seller')->orWhereHas('seller', function ($query) use ($searchString)
            {
                $query->whereRaw("users.email  LIKE '%" . $searchString . "%'");
				
            })
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
            $nestedData[] = $item->seller->email;
            $nestedData[] = $item->seller->mobile;
            $nestedData[] = $item->service;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            $nestedData[] = "<a href='".URL::to('/admin/download-manifest/'.$item->id)."'><i class='fa fa-download'></i></a>";
                      
		   if($item->admin_status==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            //$ViewLink = '<a href="' . URL::to('/') . '/admin/product/show/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            //$activateLink = '<a href="' . URL::to('/') . '/admin/productsponsor/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $nestedData[] = $deleteLink;
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
	
	function generate_manifest($id=null)
	{
	 	    $manifest= Manifest::where('id',$id)->first();
		    $data['order_ids']=json_decode($manifest['order_id']);
		    $pdf = PDF::loadView('admin.manifest.pdf.manifest',$data);
			$label= date("d-m-Y")."_".time();
		    return $pdf->download($label.'.pdf');
	}
	
	 public function delete()
    {
        if($art = Manifest::find($_POST['id'])){
            $art->delete();
            $data =  response('deleted',200);
            Session::flash('success_message', 'Category has been deleted successfully!');
        }else{
            $data = response('some_thing_is_wrong',500);
            Session::flash('success_message', 'Please Try Again!');
        }
        return $data;
    }
}