<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\City;
use App\Pincode;
use App\State;
use DB;
use Helper;
use PDF;
use URL;
use DNS1D;
use DNS2D;
use Image;
use Redirect;
use Excel;
use File;
use Mail;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class CityController extends Controller
{
	var $sdata;
	 public function __construct()
    {
		$this->sdata=Session::get('user_sdata');
        $this->middleware('auth.admin:admin');
    }
	
	function index()
	{
	   return view('admin.city.index');
	   
	}
	
	function getCityData(Request $request)
	{
		$requestData = $_REQUEST;
		$columns = array(
            0 => 'cities.id',
            1 => 'cities.name',
        );
        $totalAmenities = City::with('state')->where('status',1)->get()->count();
        $totalFiltered  = $totalAmenities;
        $amenities = City::with('state')->where('status',1)->orderBy('id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $amenities->where('name','LIKE','%'.$searchString.'%');
            $totalFiltered = City::where('name','LIKE','%'.$searchString.'%')
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
            $nestedData[] = $item->name;
            $nestedData[] = $item->state->name;
            $nestedData[] ="<a href='".URL::to('admin/icon-create/'.$item->id)."'>Add Icon</a>";
            $nestedData[] ="<img src='".URL::asset('public/admin/uploads/city_icon/'.$item->icon)."' height='40' width='40'>";
            //$nestedData[] = $item->status;
            $date = strtotime($item->created_at);
           /* $nestedData[] = date('d-m-Y', $date);*/
            if($item->is_admin_approved==1){ $class="on"; $title="active";
             
			} else { $class="off"; $title="inactive";
                   $catalogLink = '<a href="' . URL::to('/') . '/admin/product/upload-catalog/'. $item->id .' " title="Upload Catalog"><i class="fa fa-upload"></i></a>';
			}
			$deleteLink="";
			$ViewLink="";
			if(Helper::check_action($this->sdata->id,'city','delete'))
			{
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            }
			
			$editLink = '<a href="' . URL::to('/') . '/admin/city/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
            if(Helper::check_action($this->sdata->id,'city','view'))
			{
			  $ViewLink = '<a href="' . URL::to('/') . '/admin/city/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            }
			$activateLink = '<a href="' . URL::to('/') . '/admin/city/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $nestedData[] = (($item->image)?$activateLink ." | ":'').$ViewLink." | ".$deleteLink;
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
	
	function create()
	{
        $stateList = State::where('country_id',101)->get();
		return view('admin.city.add',compact('stateList'));
	}
	function icon_create($id=null)
	{
		$cityData= City::findOrFail($id);
		return view('admin.city.add_icon',compact('cityData'));
	}

    function import()
    {
        $stateList = State::where('country_id',101)->get();
        return view('admin.city.import',compact('stateList'));
    }

	function csvToArray($filename = '', $delimiter = ',')
			{
				if (!file_exists($filename) || !is_readable($filename))
					return false;

				$header = null;
				$data = array();
				if (($handle = fopen($filename, 'r')) !== false)
				{
					while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
					{
						if (!$header)
							$header = $row;
						else
							$data[] = array_combine($header, $row);
					}
					fclose($handle);
				}

				return $data;
			}
    function store_import(Request $request)
      {   
                  /*dd('adsadd');*/             
        try {
            $importData = array(
                'state_id' => $request->input('state_id'),
                'city_id' => $request->input('city_id'),
                'csv' => $request->file('csv'),
            );
            $rules = array(
                'state_id' => 'required',
                'city_id' => 'required',
                'csv' => 'required',

            );
            $validator = Validator::make($importData, $rules);
            if ($validator->fails()) {
                return redirect('admin/city/import')->withInput()->withErrors($validator);
            } else {

             if ($request->hasFile('csv')) {

                $path_original = public_path() . '/uploads/city_csv';
                $file = $request->csv;
                 $fileExtension= $file->getClientOriginalExtension();
                 if($fileExtension == 'csv') {

                     $photo_name = time() . '-' . $file->getClientOriginalName();

                     if ($file->move($path_original, $photo_name)) {
                         $file = public_path("/uploads/city_csv/" . $photo_name);
                         $customerArr = $this->csvToArray($file);
                         for ($i = 0; $i < count((array)$customerArr); $i++) {
                             if ($customerArr[$i]['Pincode']) {
                                 $data = array(
                                     'state_id' => $request->input('state_id'),
                                     'city_id' => $request->input('city_id'),
                                     'pincode' => $customerArr[$i]['Pincode'],
                                 );
                                 $checkAlready = Pincode::where('city_id', $request->input('city_id'))->where('pincode', $customerArr[$i]['Pincode'])->first();
                                 if (empty($checkAlready)) {
                                     Pincode::insert($data);
                                     $status = 1;
                                     $id = $request->input('city_id');
                                     DB::update('update cities set status = ? where id = ?', [$status, $id]);
                                 }

                             }
                         }
                         Session::flash('success_message', 'CSV has been imported successfully');
                         return redirect('admin/city/index');


                     }
                 }else{
                     Session::flash('error_message', 'Invalid csv format.');
                     return redirect('admin/city/import');
                 }
                }
                else{
                    die('CSV file Not found');
                    //session::flash('error message','CSV not available');
                    return redirect('admin/city/import')->withInput();
                   
                }
              } 
           }
           catch(Exception $e) {
            Session::flash('success_message', $e->getMessage());
            return Redirect::back();
             }
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
     function icon_store(Request $request,$id)
	 {
		 if($request->hasFile('icon')) {
            $file = $request->icon;
            $photo_name = time(). '-' .$file->getClientOriginalName();
            $path_original = public_path() . '/admin/uploads/city_icon/'.$photo_name;
            $fileTemp= $_FILES['icon'];
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
            DB::table('cities')->where('id',$id)->update(['icon'=>$photo_name]);
			Session::flash('success_message',"Updated Successfully");
            return Redirect::back();
        }
		else
		{
			Session::flash('error_message',"Upload icon first");
			return Redirect::back();
		}
		   
	 }
	
	 public function store(Request $request)
	{
		$pincodeData['state_id'] = $request->input('state_id');
		$pincodeData['city_id'] = $request->input('city_id');
        $pincode = $request->input('pincode');
		$rules=['city_id'=>"required",'state_id'=>"required"];
		$validator = Validator::make($pincodeData, $rules);
        if ($validator->fails()) {
            return redirect('admin/city/create')->withInput()->withErrors($validator);
        }
		else {
            if (!empty($pincode)) {
            $pinArray = explode(',', $pincode);
            $alreadyPin = [];
            $insertPin = [];

            foreach ($pinArray as $pin) {
                $data = array('state_id' => $pincodeData['state_id'],'city_id' => $pincodeData['city_id'], 'pincode' => $pin);
                $checkAlready = Pincode::where('city_id', $pincodeData['city_id'])->where('pincode', $pin)->first();
                if ($checkAlready) {
                    $alreadyPin[] = $pin;
                } else {
                    $insertPin[] = $pin;
                    Pincode::insert($data);
                    $status = 1;
                    $id = $pincodeData['city_id'];
                    DB::update('update cities set status = ? where id = ?',[$status,$id]);
                }

            }
            if (count($alreadyPin) > 0) {
                $alPin = implode(',', $alreadyPin);
                Session::flash('error_message', "$alPin Already exists.");

            }
            if (count($insertPin) > 0) {
                $inPin = implode(',', $insertPin);
                Session::flash('success_message', "$inPin Pincode has been added successfully.");

            }
            return redirect('/admin/city/create');

        }else{
                Session::flash('error_message', "Please enter valid pincode.");
                return redirect('/admin/city/create');
        }
	}
		
		}

	/*function show($id=null)
    {
        $pincode = Pincode::where('id', $id)->first();
        return view('admin.city.show')->with('pincode', $pincode);
    }*/
     public	function view($id)
       {
    	
    	$pincode= City::findOrFail($id);
        //$pincode = Pincode::where('city_id', $id)->get();
       
        return view('admin.city.view')->with('city_id',$id);
      }

     public function getPincodeData(Request $request)
       {


        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'pincodes.id',
            1 => 'pincodes.city_id',
            2 => 'pincodes.pincode',
            2 => 'pincodes.address',
            3 => 'pincodes.status',
        );
        $totalUsers = Pincode::with('city')->get()->where('city_id', $request->input('city_id'))->count();
        $totalFiltered = $totalUsers;
        $users = Pincode::select('pincodes.*')->where('city_id' , $request->input('city_id'))->orderBy('pincodes.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $users=$users->where('pincode','LIKE','%'.$searchString.'%');
            $totalFiltered = Pincode::where('pincode','LIKE','%'.$searchString.'%')->get()->count();
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
            $nestedData[] = $item->city->name;
            $nestedData[] = $item->pincode;
            //$nestedData[] = env('BREEDER_NUMBER').$item->unique_code;
           // $date = strtotime($item->created_at);
            //$nestedData[] = date('d-m-Y', $date); 
              $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
             /* $ViewLink = '<a href="' . URL::to('/') . '/admin/breeder/view/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';*/
			  //$editLink = '<a href="' . URL::to('/') . '/admin/user/edit/'. $item->id .' " title="Edit"><i class="glyphicon glyphicon-pencil"></i></a>';
			/*  $formLink = '<a href="' . URL::to('/') . '/admin/breeder/breeder-form/'. $item->associate_id .'/'.$item->id.' " title="Form" target="_blank"><i class="glyphicon glyphicon-file"></i></a>';*/

            // $activateLink = '<a href="' . URL::to('/') . '/admin/user/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
              $nestedData[] = $deleteLink;
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

     public function deletepincode($id)
       {
    	$pincode = Pincode::where('id',$id)->delete();
           Session::flash('success_message', 'User has been deleted successfully!');
      }
    public function deleteCity($id)
    {
        $status = 0;
        DB::update('update cities set status = ? where id = ?',[$status,$id]);
        Session::flash('success_message', 'City has been deleted successfully!');
    }



	}

	


?>