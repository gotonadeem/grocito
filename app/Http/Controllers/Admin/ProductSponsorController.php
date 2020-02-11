<?php
namespace App\Http\Controllers\Admin; //admin add
use App\Http\Controllers\Controller;   // using controller class
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Helper;
use App\User;
use App\Product;
use App\ProductSponsor;
use App\Category;
use App\SubCategory;
use App\ProductImage;
use App\SuperSubCategory;
use DB;
use URL;
use Excel;
use File;
use Mail;
use Illuminate\Support\Facades\Validator;
class ProductSponsorController extends Controller
{
	var $FIREBASE_API_KEY="AAAA_YtrgDM:APA91bHGwuMXAqYx9630IBtWm2LcGrEu9VOyZZd4-Pzd2fNmfcQENhFUPLyU5ZiKHkDVSFOYwboLhD-otKdTWqCB6GuwYirAM9fL6P5LRoT-jyRBxGsN7iVId_7_DFfsPb_SYiSup437";
	
    public function __construct()
    {
    }
    public function index()
    {  
        return view('admin.product_sponsors.index');
    }
    public function create()
    {
        $cateList=Category::get();
        $subcateList=SubCategory::get();
        $supsubcateList=SuperSubCategory::get();
        return view('admin.products.create')
            ->with('category_list',$cateList)
            ->with('sub_category_list',$subcateList)
            ->with('super_sub_category_list',$supsubcateList);
    }
    public function store(Request $request)
    {
        $rules = array();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('admin/team/create-property')->withInput()->withErrors($validator);
        } else {
            $product = new Product($request->all());
            $product->save();

                foreach ($_FILES as $file) {
                    echo $file['name'];

                    $product1=array();
                    $photo_name = time() . '-' . $file['name'];
                    $path_original = public_path() . '/admin/uploads/product/'.$photo_name;
                    move_uploaded_file($file['tmp_name'],$path_original);
                    $product1 = [
                        'product_id' => $product->id,
                        'image' =>$photo_name,
                    ];
                    DB::table('product_images')->insert($product1);
                }
              return response('success', 200);
        }
    }


    public function edit($id)
    {
        $this->model->setRules($id);
        $this->rules = $this->model->getRules();
        $amenities = $this->model->findOrFail($id);
        $validator = JsValidatorFacade::make($this->rules['store']);
        return view('admin.amenities.edit',compact('validator','amenities'));
    }

    public function update(Request $request, $id)
    {
        $data =$request->all();
        $this->model->setRules($id);
        $this->rules = $this->model->getRules();
        //print_r($data);die;
        $amenity = $this->model->findOrFail($id);
        if($request->ajax() and !isset($request->_jsvalidation)){
            try {
                $amenity->fill($data)->save();
                return Response::json(array(
                    'success' => false,
                    'message' => 'updated'
                ), 200);
            } catch (\Exception $e) {
                return Response::json(array(
                    'success' => true,
                    'message' => $e->getMessage()

                ), 400);
            }

        }
        $validator = Validator::make($request->all(), $this->rules['update']);
        if ($validator->fails()) {
            return redirect()->route('admin.amenities.edit')->withInput()->withErrors($validator);
        }

        if($request->hasFile('image')) {
            $path = config('image.path.amenities.local');
            $path_original = config('image.path.amenities.original');
            $file = $request->image;
            $photo_name = time(). '-' .$file->getClientOriginalName();
            $file->move($path_original,$photo_name);
            if($request->old_img!='') {
                try {
                    unlink($path . $request->old_img);
                } catch (\Exception $e) {
                }
            }
            $data['image'] = $photo_name;
        }

        $amenity->fill($data)->save();
        // redirect
        Session::flash('success_message', 'Successfully updated amenity!');
        return redirect()->route('admin.amenities.index');
    }

    public function destroy($id)
    {
        if($art = $this->model->find($id)){
            $art->delete();
            $data =  response('deleted',200);
        }else{
            $data = response('some_thing_is_wrong',500);
        }
        return $data;
    }

    //get list of record of subadmin...........................................................
    //get list of record of ...........................................................
    public function getProductSponsorData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'product_sponsors.id',
            1 => 'users.username',
            2 => 'product_sponsors.admin_status',
        );
        $totalAmenities = ProductSponsor::with('product_data','user','plan')->get()->count();
        $totalFiltered  = $totalAmenities;
        $amenities = ProductSponsor::with('product_data','user','plan')->select('*')->orderBy('product_sponsors.id', 'desc');
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);
        if (!empty($requestData['search']['value']))
        {
            $amenities->orWhereHas('user', function ($query) use ($searchString)
            {
                $query->whereRaw("users.username  LIKE '%" . $searchString . "%'");
				
            });
            $totalFiltered = ProductSponsor::with('product_data','user','plan')->orWhereHas('user', function ($query) use ($searchString)
            {
                $query->whereRaw("users.username  LIKE '%" . $searchString . "%'");
				
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
            $nestedData[] = (!is_null($item->product_data)?$item->product_data->name:"");
            $nestedData[] = (!is_null($item->user)?$item->user->username:"");
            $nestedData[] = (!is_null($item->user)?$item->user->mobile:"");
            $nestedData[] =  wordwrap($item->plan->plan_details,15,"<br>\n",true);
            $nestedData[] = $item->price;
            $nestedData[] = $item->date;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if($item->admin_status==1){ $class="on"; $title="active"; } else { $class="off"; $title="inactive"; }
            $deleteLink = '<a href="javascript:void(0);" onclick="deleteItem(' . $item->id . ',this)" title="Delete"><i class="glyphicon glyphicon-trash"></i></a>';
            //$ViewLink = '<a href="' . URL::to('/') . '/admin/product/show/'. $item->id .' " title="View"><i class="glyphicon glyphicon-eye-open"></i></a>';
            $activateLink = '<a href="' . URL::to('/') . '/admin/productsponsor/update-status/'.$item->id.'" title="'.$title.'"><i class="fa fa-toggle-'.$class.'" aria-hidden="true" ></i></a>';
            $nestedData[] = $activateLink ." | ".$deleteLink;
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

    public function delete()
    {
        $property = ProductSponsor::findOrFail($_POST['id']);

        if(!empty($property->delete()))
        {

            Session::flash('success_message', 'Sponsor Request has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the property');
        }
    }

    public function property_details($id)
    {
        $product = Product::with('user_name','product_category','product_image')->findOrFail($id);
        return view('admin.products.show',compact('product'));
    }
	
	  public function new_push_notification($data, array $device_tokens)
	  {
		    	// prep the bundle
				$msg = array
				(
					'title'  => 'Cartlay',
					'name' => $data['name'],
					'description' => $data['description'],
					'image' => "https://seller.cartlay.com/public/uploads/seller/catalog/".$data['image'],
					'vibrate' => 1,
					'sound'  => 1,
					'largeIcon' => 'larg_icon',
					'smallIcon' => 'small_icon',
					'catalog'=>$data,
					
				);
				$fields = array
				(
					'registration_ids'  => $device_tokens,
					'data'   => $msg
				);

				$headers = array
				(
					'Authorization: key=' . $this->FIREBASE_API_KEY,
					'Content-Type: application/json'
				);

				$ch = curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields)  );
				$result[] = curl_exec($ch );
				$info = curl_getinfo($ch);
				curl_close( $ch );
				return $fields;
	   }
	
	
	  public function handle1($p_id)
      {
				//echo $p_id; die;

            $colname = date("Y-m-d");
              $query = \DB::table('products')
						 ->join('product_sponsors', 'products.id', '=', 'product_sponsors.product_id')
						 ->join('product_images', 'products.id', '=', 'product_images.product_id')
						 ->select("products.*",'product_images.image as image')
						 ->where('products.id',$p_id)
						 ->where('product_sponsors.admin_status', 0);
				    $vs=$query->first();
					//dd($product_list);
					
	
  						 $json=array();
						 $json['id']=$vs->id;
						 $json['name']=$vs->name;
						 $json['description'] = $vs->description;
						 $json['price'] = $vs->starting_price;
						 $json['sell_price'] = $vs->sell_price;
						 $json['image'] = $vs->image;
						 $json['catalog_images'] = Helper::get_catalog_images($vs->id);
						 $jsonData= $json;
						 ///////
						 $deviceToken = \DB::table('category_notifications')
						 ->join('users', 'users.id', '=', 'category_notifications.customer_id')
						 ->select("users.device_token")
						 //->where("users.id",1184)
						 ->where('category_notifications.special_status', 1)->get();
						 $deviceArray=array();
							foreach($deviceToken as $vs1)
							{
								if($vs1->device_token!="")
								{
								$deviceArray[]= $vs1->device_token;
								}
							}
						
						$this->new_push_notification($jsonData,$deviceArray);
						
								
	  }
	
	function update_status($id=null)
	 {
	    $data= ProductSponsor::where('id',$id)->first();
		if($data->admin_status==0)
    		{
		       $this->handle1($data->product_id);
	    	}
		$response=DB::statement("UPDATE product_sponsors SET admin_status =(CASE WHEN (admin_status = 1) THEN '0' ELSE '1' END) where id = $id");
		if($response) {
			Session::flash('success_message', 'status has been updated successfully!');
		}
		else {
			Session::flash('error_message', 'Unable to update status');
		}
		return redirect('/admin/productsponsor/product-sponsor-list');
	}
    ///change subadmin status...................
}
