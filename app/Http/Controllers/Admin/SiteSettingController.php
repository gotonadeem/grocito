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
use App\BankDetail;
use App\MerchantWallet;
use App\Agreement;
use App\ContactUs;
use App\SellerJoinusCms;
use App\Cashback;
use App\ReferralSetting;
use DB;
use URL;
use File;
use App\UserComplaint;
use App\CallRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class SiteSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth.admin:admin');
    }

    public function create()
    {
           $setting = GeneralSetting::first();
            return view('admin.site_setting.general')->with(['setting' => $setting]);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'saleplus_commission' => 'required',
                'wallet_deduction' => 'required',
            ], [
                'saleplus_commission.required' => 'This field is required',
                'wallet_deduction.required' => 'This field is required',
            ]);

        if ($validator->fails())
        {
            return redirect('/admin/site-setting/general-setting')->withInput()->withErrors($validator);
        }
        else
        {
            $data=array();
            $obj=new GeneralSetting();
            $obj->saleplus_commission=$request->input('saleplus_commission');
            $obj->wallet_commission=$request->input('wallet_commission');
              $setting = GeneralSetting::first();
              if($setting) {
                  $data =$request->all();
                  $update_data = GeneralSetting::find($setting->id)->fill($data);
                  $update_data->update();
              }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated ');
            return redirect('/admin/site-setting/general-setting');
        }
    }
    public function seller_joinus_cms()
    {
        $cms = SellerJoinusCms::first();
        return view('admin.site_setting.seller_joinus_cms')->with(['setting' => $cms]);

    }

    public function seller_joinus_cms_store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'description' => 'required',
            ], [
                'description.required' => 'This field is required',
            ]);

        if ($validator->fails())
        {
            return redirect('/admin/site-setting/seller-joinus-cms')->withInput()->withErrors($validator);
        }
        else
        {
            $data=array();
            $obj=new SellerJoinusCms();
            $obj->description=$request->input('description');
            $setting = SellerJoinusCms::first();
            if($setting) {
                $data =$request->all();
                $update_data = SellerJoinusCms::find($setting->id)->fill($data);
                $update_data->update();
            }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated ');
            return redirect('/admin/site-setting/seller-joinus-cms')->with('setting',$setting);
        }
    }
    public function payment_create()
    {
        $setting = PaymentGatwaySetting::first();
        return view("admin.site_setting.payment_gatway")->with(['setting' => $setting]);
    }

    public function payumoney_setting_store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'merchant_key' => 'required',
                'salt' => 'required',
            ], [
                'merchant_key' => 'Merchant Key is required',
                'salt' => 'Merchant Id is required',
            ]);

        if ($validator->fails())
        {
            return redirect('/admin/site-setting/payumoney-setting')->withInput()->withErrors($validator);
        }
        else
        {
            $data=array();
            $obj=new PaymentGatwaySetting();
            $obj->merchant_key=$request->input('merchant_key');
            $obj->merchant_id=$request->input('salt');
            $setting = PaymentGatwaySetting::first();
            if($setting) {
                $data =$request->all();
                $update_data = PaymentGatwaySetting::find($setting->id)->fill($data);
                $update_data->update();
            }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated sub-admin!');
            return redirect('/admin/site-setting/payumoney-setting');
        }
    }


    public function bank_detail_create()
    {
        $setting = BankDetail::first();
        return view("admin.site_setting.bank_detail")->with(['setting' => $setting]);
    }

    public function bank_detail_store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'bank_name' => 'required',
                'ifsc' => 'required',
                'account_holder_name' => 'required',
                'account_no' => 'required',
                'branch_name' => 'required',
            ], [
                'bank_name' => 'Bank Name is required',
                'ifsc' => 'Ifsc is required',
                'account_no' => 'Account no is required',
                'account_holder_name' => 'Account holder is required',
                'branch_name' => 'Branch name is required',
            ]);

        if ($validator->fails())
        {
            return redirect('/admin/site-setting/bank-detail')->withInput()->withErrors($validator);
        }
        else
        {
            $data=array();
            $obj=new BankDetail();
            $obj->bank_name=$request->input('bank_name');
            $obj->ifsc=$request->input('ifsc');
            $obj->account_holder_name=$request->input('account_holder_name');
            $obj->account_no=$request->input('account_no');
            $obj->branch_name=$request->input('branch_name');
            $setting = BankDetail::first();
            if($setting) {
                $data =$request->all();
                $update_data = BankDetail::find($setting->id)->fill($data);
                $update_data->update();
            }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated sub-admin!');
            return redirect('/admin/site-setting/bank-detail');
        }
    }
	
	public function add_agreement()
	{
		$agreements = Agreement::first();
        return view("admin.site_setting.agreement")->with(['agreements' => $agreements]);
	}
	
	public function store_agreement(Request $request)
	{
		    $obj=new Agreement();
            $obj->description=$request->input('description');
            $setting = Agreement::first();
			if($setting) {
                $data['description']= $request->input('description');
                $update_data = Agreement::where('id',$setting->id)->first()->fill($data);
                $update_data->update();
            }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated Agreement!');
            return redirect('/admin/site-setting/add-agreement');
	}
	/*........................App setting.............................*/
	public function app_update()
	{
		$setting = GeneralSetting::first();
        return view("admin.site_setting.app_update")->with(['setting' => $setting]);
	}
	
	/*........................App setting.............................*/
	public function app_video()
	{
		$setting = GeneralSetting::first();
        return view("admin.site_setting.app_video")->with(['setting' => $setting]);
	}
	/*........................App Popualar Image.............................*/
	public function popular_thumbnail()
	{
		$setting = GeneralSetting::first();
        return view("admin.site_setting.popular_image")->with(['setting' => $setting]);
	}
	
	public function store_app_update(Request $request)
	{
		    $obj=new GeneralSetting();
            $obj->app_version=$request->input('app_version');
            $setting = GeneralSetting::first();
			if($setting) {
                $data['app_version']= $request->input('app_version');
                $update_data = GeneralSetting::where('id',$setting->id)->first()->fill($data);
                $update_data->update();
            }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated app version!');
            return redirect('/admin/site-setting/app-update');
	}
	public function store_app_video(Request $request)
	{
		    $obj=new GeneralSetting();
            $obj->app_video=$request->input('app_hindi_video');
            $obj->app_video=$request->input('app_english_video');
            $obj->app_video=$request->input('app_video');
            $obj->video_english_title=$request->input('video_english_title');
            $obj->video_hindi_title=$request->input('video_hindi_title');
            $setting = GeneralSetting::first();
			if($setting) {
                $data['app_hindi_video']= $request->input('app_hindi_video');
                $data['app_english_video']= $request->input('app_english_video');
                $data['video_english_title']= $request->input('video_english_title');
                $data['video_hindi_title']= $request->input('video_hindi_title');
                $update_data = GeneralSetting::where('id',$setting->id)->first()->fill($data);
                $update_data->update();
            }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated app video!');
            return redirect('/admin/site-setting/app-video');
	}
	public function popular_thumbnail_store(Request $request)
	{
		     $obj=new GeneralSetting();
			 $setting = GeneralSetting::first();
			if ($request->hasFile('popular_image'))
                  {
                      $path_original = public_path() . '/admin/uploads/category/';
                      $file = $request->popular_image;
                          $photo_name = time() . '-' . $file->getClientOriginalName();
                          $file->move($path_original, $photo_name);
                          $data['popular_image'] = $photo_name;
                          if ($request->old_img != '') {
                              try {
                                  unlink($path_original . $request->old_img);

                              } catch (\Exception $e) {
                              }
                          }
						  $obj->popular_image=$data['popular_image'];
						  $data['popular_image']= $data['popular_image'];
                  }	
				  
				  if ($request->hasFile('popular_image2'))
                  {
                      $path_original = public_path() . '/admin/uploads/general_setting/';
                      $file = $request->popular_image2;
                          $photo_name = time() . '-' . $file->getClientOriginalName();
                          $file->move($path_original, $photo_name);
                          $data['popular_image2'] = $photo_name;
                          if ($request->old_img != '') {
                              try {
                                  unlink($path_original . $request->old_img);

                              } catch (\Exception $e) {
                              }
                          }
						  $obj->popular_image2=$data['popular_image2'];
						  $data['popular_image2']= $data['popular_image2'];
                  }	if ($request->hasFile('special_image'))
                  {
                      $path_original = public_path() . '/admin/uploads/general_setting/';
                      $file = $request->special_image;
                          $photo_name = time() . '-' . $file->getClientOriginalName();
                          $file->move($path_original, $photo_name);
                          $data['special_image'] = $photo_name;
                          if ($request->old_img != '') {
                              try {
                                  unlink($path_original . $request->old_img);

                              } catch (\Exception $e) {
                              }
                          }
						  $obj->special_image=$data['special_image'];
						  $data['special_image']= $data['special_image'];
                  }	if ($request->hasFile('deal_of_the_day_image'))
                  {
                      $path_original = public_path() . '/admin/uploads/general_setting/';
                      $file = $request->deal_of_the_day_image;
                          $photo_name = time() . '-' . $file->getClientOriginalName();
                          $file->move($path_original, $photo_name);
                          $data['deal_of_the_day_image'] = $photo_name;
                          if ($request->old_img != '') {
                              try {
                                  unlink($path_original . $request->old_img);

                              } catch (\Exception $e) {
                              }
                          }
						  $obj->deal_of_the_day_image=$data['deal_of_the_day_image'];
						  $data['deal_of_the_day_image']= $data['deal_of_the_day_image'];
                  }	if ($request->hasFile('more_image'))
                  {
                      $path_original = public_path() . '/admin/uploads/general_setting/';
                      $file = $request->more_image;
                          $photo_name = time() . '-' . $file->getClientOriginalName();
                          $file->move($path_original, $photo_name);
                          $data['more_image'] = $photo_name;
                          if ($request->old_img != '') {
                              try {
                                  unlink($path_original . $request->old_img);

                              } catch (\Exception $e) {
                              }
                          }
						  $obj->more_image=$data['more_image'];
						  $data['more_image']= $data['more_image'];
                  }
            
           
			if($setting) {
               
                $update_data = GeneralSetting::where('id',$setting->id)->first()->fill($data);
                $update_data->update();
            }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated Popular Image');
            return redirect('/admin/site-setting/popular-thumbnail');
	}
	
	 public function contact_us(Request $request)
     {
	    $contact_us = ContactUs::get();
		return view('admin.contact_us.contact_us',compact('contact_us'));
	 }
    public function getQueryFormData(Request $request)
    {
        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'contact_uses.id',
            1 => 'contact_uses.name',
            2 => 'contact_uses.mobile',

        );
        $totalUsers = ContactUs::get()->count();
        $totalFiltered = $totalUsers;
        //$users = ContactUs::
        //print_r($users);die;
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);

        if (!empty($requestData['search']['value'])) {

            $user=ContactUs::where(function($query) use ($searchString) {
                return $query->where('name','LIKE','%'.$searchString.'%')
                    ->orWhere('mobile','LIKE','%'.$searchString.'%')
                    ->orWhere('email','LIKE','%'.$searchString.'%');
            });
            $totalFiltered=ContactUs::where(function($query) use ($searchString) {
                return $query->where('name','LIKE','%'.$searchString.'%')
                    ->orWhere('mobile','LIKE','%'.$searchString.'%')
                    ->orWhere('email','LIKE','%'.$searchString.'%');
            })->get()->count();
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = ContactUs::offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;

            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->name;
            $nestedData[] = $item->mobile;
            $nestedData[] = $item->email;
            $nestedData[] = $item->message;
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);

            //$viewLink = '<a href="' . URL::to('/') . '/admin/payment/view-payment/' . $id . ' " title="View Payment"><i class="fa fa-google-wallet"></i></a>';
            //$nestedData[] = $viewLink." | ".$transactionLink." | ".$accountInfo;


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
	 public function store_contact_us(Request $request)
	 {
        $validator = Validator::make($request->all(),
            [
                'mobile' => 'required',
                'email' => 'required',
                'time' => 'required',
            ], [
                'mobile' => 'Mobile no is required',
                'email' => 'email is required',
                'time' => 'Time is required',
            ]);
			
        if ($validator->fails())
        {
            return redirect('/admin/site-setting/contact-us')->withInput()->withErrors($validator);
        }
        else
        {
            $data=array();
            $obj=new ContactUs();
            $obj->mobile=$request->input('mobile');
            $obj->email=$request->input('email');
            $obj->time=$request->input('time');
            $setting = ContactUs::first();
            if($setting) {
                $data =$request->all();
                $update_data = ContactUs::find($setting->id)->fill($data);
                $update_data->update();
            }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated contact us!');
            return redirect('/admin/site-setting/contact-us');
        }
    }
	
	public function merchant_commission(Request $request)
     {
		$data= MerchantWallet::get(); 
		return view('admin.site_setting.merchant_commission',compact('data'));
	 }
	
	  public function merchant_commission_store(Request $request)
	 {
          if(count($request->input('level'))>0)
		  {
              //dd($request->input('level'));
			  
            $data=array();
            $obj=new MerchantWallet();
            MerchantWallet::truncate();
			foreach($request->input('level') as $ks=>$vs)
			   {
				if(!empty($vs))
				{
				  $data[] =[
							'level' => $ks+1,
	 						'value' => $vs,
	 						'commission' =>$request->input('commission'),
	 					   ];                 
   
	 		   }}
	 		  MerchantWallet::insert($data);
	 		  
            Session::flash('success_message', 'Successfully updated!');
            return redirect('/admin/site-setting/merchant-commission');
        }
	 	else
	 	{
	 		 Session::flash('error_message', 'Please fill all fields!');
             return redirect('/admin/site-setting/merchant-commission');
	 	}
    }
   
   public function cashback(){
     //die('dsjkd');
         $data= Cashback::first(); 
          return view('admin.site_setting.cashback')->with(['cashback'=>$data]); 
      }
   
   public function update_cashback(Request $request){
         $validator = Validator::make($request->all(),
            [
                'min_order_value' => 'required',
                'cashback_per' => 'required',
                'upto_cashback'=>'required',
                'welcome_min_order_value'=>'required',
                'welcome_cashback_per'=>'required',
            ], [
                'min_order_value.required' => 'This field is required',
                'cashback_per.required' => 'This field is required',
                'upto_cashback.required'=>'This field is required', 
                'welcome_min_order_value'=>'This field is required',
                'welcome_cashback_per'=>'This field is required',
            ]);
   
        if ($validator->fails())
        {
            return redirect('/site-setting/cashback')->withInput()->withErrors($validator);
        }
        else
        {
            $data=array();
            $obj= new Cashback();
            $obj->min_order_value=$request->input('min_order_value');
            $obj->cashback_per = $request->input('cashback_per');
            $obj->upto_cashback = $request->input('upto_cashback');
            $obj->welcome_min_order_value=$request->input('welcome_min_order_value');
            $obj->welcome_cashback_per = $request->input('welcome_cashback_per');
              $setting = Cashback::first();

              //die($setting);
              if($setting) {
                   $data =$request->all();
                   DB::table('cashbacks')->where('id',$setting->id)->update(['min_order_value'=>$request->input('min_order_value'), 'cashback_per'=>$request->input('cashback_per'), 'upto_cashback'=>$request->input('upto_cashback'),'welcome_min_order_value'=>$request->input('welcome_min_order_value'),'welcome_cashback_per'=>$request->input('welcome_cashback_per'),]);
                   //die($request->input('upto_cashback'));
                   //$update_data = Cashback::find($setting->id)->fill($data);
                   //$update_data->update();
              }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated ');
            return redirect('/admin/site-setting/cashback');
        }


   }

    public function refer_earn(){
     //die('dsjkd');
         $data= ReferralSetting::first(); 
          return view('admin.site_setting.referral')->with(['refferearn'=>$data]); 
      }

    public function update_referral(Request $request){
         $validator = Validator::make($request->all(),
            [
                'referrer_amount' => 'required',
                'referral_amount' => 'required',
               
            ], [
                'referrer_amount' => 'This field is required',
                'referral_amount.required' => 'This field is required',
                 
            ]);
   
        if ($validator->fails())
        {
            return redirect('/site-setting/refernearn')->withInput()->withErrors($validator);
        }
        else
        {
            $data=array();
            $obj= new ReferralSetting();
            $obj->referrer_amount=$request->input('referrer_amount');
            $obj->referral_amount = $request->input('referral_amount');
            $obj->referral_description = $request->input('referral_description');
            
              $setting = ReferralSetting::first();

              //die($setting);
              if($setting) {
                   $data =$request->all();
                   DB::table('referral_settings')->where('id',$setting->id)->update(['referrer_amount'=>$request->input('referrer_amount'), 'referral_amount'=>$request->input('referral_amount'), 'referral_description'=>$request->input('referral_description')]);
                   //die($request->input('upto_cashback'));
                   //$update_data = Cashback::find($setting->id)->fill($data);
                   //$update_data->update();
              }
            else
            {
                $obj->save();
            }
            Session::flash('success_message', 'Successfully updated ');
            return redirect('/admin/site-setting/refernearn');
        }


   } 

   public function user_complaints(Request $request)
     {
      $complaints = UserComplaint::get();
      return view('admin.user_complaints.user-complaints',compact('complaints'));
    }
   
    public function getUserComplaintData(Request $request)
    {

        $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'user_complaints.id',
            1 => 'user_complaints.subject',
            2 => 'user_complaints.complaint_id',

        );
        $totalUsers = UserComplaint::get()->count();
        $totalFiltered = $totalUsers;
        //$users = ContactUs::
        //print_r($users);die;
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);

        if (!empty($requestData['search']['value'])) {

            $user=UserComplaint::where(function($query) use ($searchString) {
                return $query->where('subject','LIKE','%'.$searchString.'%')
                    ->orWhere('complaint_id','LIKE','%'.$searchString.'%')
                    ->orWhere('user_id','LIKE','%'.$searchString.'%');
            });
            $totalFiltered=UserComplaint::where(function($query) use ($searchString) {
                return $query->where('subject','LIKE','%'.$searchString.'%')
                    ->orWhere('complaint_id','LIKE','%'.$searchString.'%')
                    ->orWhere('user_id','LIKE','%'.$searchString.'%');
            })->get()->count();
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = UserComplaint::offset($offset)->limit($limit)->orderBy($orderColumn, $orderColumnDir)->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;

            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $item->user_id;
            $nestedData[] = $item->complaint_id;
            $nestedData[] = $item->subject; 
             $nestedData[] = $item->reply;            
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            if($item->status==1){
                $class="on"; $title="active";
            } else {
                $class="off"; $title="inactive";
            }
            $activateLink = "<select onchange='change_status(this.value)'>	<option ".(($item->status=='pending')?'selected':'')." value='0,".$item->id."'>Pending</option>
						 <option ".(($item->status=='solved')?'selected':'')." value='1,".$item->id."'>Solved</option>							 </select>";
            $viewLink = '<a  href="'.URL::to('admin/user-complaints/view/'.$item->id).'">View / Reply</a>';
            //$viewLink = '<a href="' . URL::to('/') . '/admin/payment/view-payment/' . $id . ' " title="View Payment"><i class="fa fa-google-wallet"></i></a>';
            $nestedData[] = $viewLink." | ".$activateLink;


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

     public function view_complaint($id)
    {
        $data = UserComplaint::findOrFail($id);
        return view('admin.user_complaints.view',compact('data'));
    }

     public function delete()
    {
        $faq = UserComplaint::findOrFail($_POST['id']);
        if(!empty($faq->delete()))
        {
            Session::flash('success_message', 'Data has been deleted successfully!');
        }
        else {
            Session::flash('error_message', 'Unable to delete the Data');
        }
    }

     public function store_user_complaints(Request $request)
   {  
            //echo $comId = $request->input('id');die;
            $obj = UserComplaint::findOrFail($_POST['id']);
            $obj->reply=$request->input('reply');
            $setting = UserComplaint::first();
      if($setting) {
                $data['reply']= $request->input('reply');
                $update_data = UserComplaint::where('id',$setting->id)->first()->fill($data);
                $update_data->update();
            }
            else
            {
                $update_data->save();
            }
            Session::flash('success_message', 'Successfully updated Reply!');
            return redirect('/admin/site-setting/user-complaints');   
    }

    public function callRequest(){
     //$data = CallRequest::get();
      return view('admin.call_request.index');
    }

    public function getCallRequestData(Request $request)
    {

       $requestData = $_REQUEST;
        $columns = array(
            // column index  => database column name
            0 => 'call_requests.id',
            1 => 'call_requests.user_id',
            2 => 'call_requests.status',

        );
        $totalUsers = CallRequest::with('user')->get()->count();
        $totalFiltered = $totalUsers;
        //$users = ContactUs::
        //print_r($users);die;
        $searchString = str_replace("%", "zzempty", $requestData['search']['value']);
        $searchString = str_replace("'", "\'", $searchString);

        if (!empty($requestData['search']['value'])) {

            $user=CallRequest::with('user')->where(function($query) use ($searchString) {
                return $query->where('username','LIKE','%'.$searchString.'%');
            });

            $totalFiltered=CallRequest::with('user')->where(function($query) use ($searchString) {
                return $query->where('username','LIKE','%'.$searchString.'%');
            })->get()->count();
        }
        $orderColumn = $columns[$requestData['order'][0]['column']];
        $orderColumnDir = $requestData['order'][0]['dir'];
        $limit = $requestData['length'];
        $offset = $requestData['start'];
        $users = CallRequest::with('user')->offset($offset)->limit($limit)->orderBy('id','desc')->get();
        $data = array();
        $i = $offset;
        foreach ($users as $item) {
            $i++;

            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = !is_null($item->user)?$item->user->username:"";
            $nestedData[] = !is_null($item->user)?$item->user->mobile:"";
            $nestedData[] = !is_null($item->user)?$item->user->email:"";         
            $date = strtotime($item->created_at);
            $nestedData[] = date('d-m-Y', $date);
            /*if($item->status==1){
                $class="on"; $title="active";
            } else {
                $class="off"; $title="inactive";
            }
            $activateLink = "<select onchange='change_status(this.value)'>  <option ".(($item->status=='pending')?'selected':'')." value='0,".$item->id."'>Pending</option>
             <option ".(($item->status=='solved')?'selected':'')." value='1,".$item->id."'>Solved</option>               </select>";
            $viewLink = '<a  href="'.URL::to('admin/call-request/view/'.$item->id).'">View / Reply</a>';
            //$viewLink = '<a href="' . URL::to('/') . '/admin/payment/view-payment/' . $id . ' " title="View Payment"><i class="fa fa-google-wallet"></i></a>';
            /*$nestedData[] = $viewLink." | ".$activateLink;*/
            
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
    
    public function view_callrequest($id)
    {
        $data = CallRequest::findOrFail($id);
        return view('admin.call_request.view',compact('data'));
    }

}

?>