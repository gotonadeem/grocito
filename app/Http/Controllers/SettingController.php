<?php
namespace App\Http\Controllers;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;
use Auth;
use App\Helpers\Helper;
use Session;
use DB;
use Validator;
use App\User;
use App\Role;
use Hash;
use Mail;
use App\Enquiry;
use App\UserRequestPlan;
use App\PropertyVisitCount;
use Carbon\Carbon;
session_start();
class SettingController extends Controller
{

    public function __construct()
    {
      parent::__construct();
	  
    }



    public function setting(){
        $role = User::all()->pluck('is_available','id');
        $user = User::find(Auth::user()->id);
        //print_r($user->address);die;
        return view("seller.setting",compact('role','user'));
    }

    public function updateProfile(Request $request)
    {

        if (Auth::check())

        {

            $input = $request->all();

            $reqmobile=$input['mobile'];

            $reqemail=$input['email'];

            $user = User::find(Auth::user()->id);

            $usermob=$user->mobile;

            $useremail=$user->email;

            if($reqmobile!=$usermob){

                $input['is_email_verified']=0;

            }

            if($reqmobile!=$usermob){

                $input['is_mobile_verified']=0;

            }

            $id=$user->id;



            $validator = Validator::make($request->all(),

                [

                    'fname' => 'required|max:20|regex:/^[a-zA-Z .\']+$/',

                    'lname' => 'required|max:20|regex:/^[a-zA-Z .\']+$/',

                    'email' => 'required|max:100|email|unique:users,email'.($id != '' ? ','.$id:'').'',

                    'mobile' => 'required|numeric|digits_between:8,10|unique:users,mobile'.($id != '' ? ','.$id:'').'',

                    'address'=> 'required',

                    'gender'=>'required',

                    'dob'=>  'required',

                ]);

            if ($validator->fails())

            {

                return redirect('seller/edit-profile')->withInput()->withErrors($validator);

            }

            else

            {

              //print_r($input);die;

                $user->fill($input)->save();

                Session::flash('success_message', 'Successfully updated profile!');

                return redirect('seller/profile');

            }

        }else{

            Session::flash('error_message', 'Sorry You have to login first');

            return redirect('login');



        }

    }



    public function view_certified(){

        if (Auth::check()) {

            $user = User::find(Auth::user()->id);

            $plans = Plan::select()->where('category','0')->get();

            $city= City::select('name','id')->get();

            //echo "<pre>";print_r($plans);die;

            return view("user.seller.become-certified", compact('user','plans','city'));

        }else{

            Session::flash('error_message', 'Sorry You have to login first');

            return redirect('/');

        }

    }

    public function view_certified_form(Request $request){

        if(empty($request['plan_id'])){

            Session::flash('error_message', 'Please login to your account!');

            return back(); return redirect()->back();

        }

        else {

            $_SESSION['plan_id'] = $request['plan_id'];

            $_SESSION['checkout_total'] = $request['checkout_total'];

            $_SESSION['checkout_subtotal'] = $request['checkout_subtotal'];

            $_SESSION['checkout_plan'] = $request['checkout_plan'];

            $_SESSION['checkout_discount'] = $request['checkout_discount'];



            echo json_encode(array(

                'success' => true,

            ));

            exit;

        }

    }

    public function certified_form(){

        return view('user.seller.view-certified-form');

    }

    public function become_certified_form(request $request){



        if (Auth::check()) {

            $user = User::find(Auth::user()->id);

            $input = $request->all();

            $validator = Validator::make($request->all(),

                [

                    'ruc' => 'required|max:13',

                    'licence' => 'required|max:30',

                    'licence_date' => 'required',

                    'experience' => 'required|numeric',

                    'association' => 'required|max:200',

                    'document' => 'required|mimes:jpeg,png,jpg,gif,pdf,doc'



                ]);

            if ($validator->fails())

            {

                return redirect('seller/become-certified')->withInput()->withErrors($validator);

            }

            else

            {

                //image upload

                $picture = '';

                $file =  $input['document'];

                if ($request->hasFile('document')) {

                    //print_r($_FILES);die;

                    //$files = $request->file('document');

                    //foreach($files as $file) {

                    if ($request->hasFile('document')) {

                        $name = time() . '-' . $file->getClientOriginalName();



                        $input['document'] = $name;

                        //$path = config('image.path.profile_image.local');

                        $path_original = config('image.path.certified');

                        $destinationPath = public_path('uploads/become_certified/original');

                       // print_r($destinationPath);die;

                        $file->move($destinationPath, $name);

                    }

                    // }

                }

//

//                if (!empty($input['document'])) {

//                    $input['document'] = $name;

//                }

//                else {

//                    unset($input['document']);

//                }

                //image upload

                $ruc=$input['ruc'];

                $licence=$input['licence'];

                $licence_date=$input['licence_date'];

                $experience=$input['experience'];

                $association=$input['ruc'];

                $desc= array(

                    'ruc'        => $ruc,

                    'licence'      => $licence,

                    'date'   => $licence_date,

                    'experience'      => $experience,

                    'association'=>$association,

                    'email'=>$user->email

                );

                $subject='Request for certified seller';

                $subjectuser='Request Sent';



                $emailData = array(

                    'to'        => env('MAIL_USERNAME'),

                    'from'      => $user->email,

                    'subject'   => $subject,

                    'view'      => 'user.seller.mailview',

                    'content'=>$desc

                );

                $useremailData = array(

                    'to'        => $user->email,

                    'from'      => env('MAIL_USERNAME'),

                    'subject'   => $subjectuser,

                    'view'      => 'user.seller.user-mailview',

                );

                try{

                    Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {

                        $message

                            ->to($emailData['to'])

                            ->from($emailData['from'])

                            ->subject($emailData['subject']);

                    });

                    Mail::send($useremailData['view'], $useremailData, function ($message) use ($useremailData) {

                        $message

                            ->to($useremailData['to'])

                            ->from($useremailData['from'])

                            ->subject($useremailData['subject']);

                    });



                }catch(Exception $e) {

                    echo 'Message: ' .$e->getMessage();

                }





                $requestPlan=new UserRequestPlan();

                $input['user_id']= $user->id;

                $input['plan_id']= $_SESSION['plan_id'];



                $input['price']= $_SESSION['checkout_total'];

                $requestPlan->fill($input)->save();

                if($requestPlan->fill($input)->save()){

                   User::where('id',$user->id)->update(['is_certified'=>1]);

                    Session::flash('success_message','Request sent successfully !');

                    return redirect('seller/profile');

                }

            }



        }else{

            Session::flash('error_message', 'Sorry You have to login first');

            return redirect('login');

        }

    }



    public function become_certified(request $request){

        // die;

        if (Auth::check()) {

            $user_id=Auth::user()->id;

            $user = User::find(Auth::user()->id);

            $id = $request['id'];



            $validator = Validator::make($request->all(),

                [

                    'id' => 'required'



                ]);

            if ($validator->fails())

            {

                return redirect('seller/become-certified')->withInput()->withErrors($validator);

            }

            else

            {

                $input= array(

                    'user_id'        => $user_id,

                    'plan_id'      => $id

                );



                User_plans::create($input);

                Session::flash('success_message', 'Request sent successfully !');

                return redirect('seller/become-certified');

            }



        }else{

            Session::flash('error_message', 'Sorry You have to login first');

            return redirect('login');

        }

    }

    public function city_planList(request $request){

       $date = date('Y-m-d');

        $cityPlan=Plan::select()->where('city_id',$request['cityId'])->whereRaw("CAST(`startdate` AS DATE) <='".$date."'")->whereRaw("CAST(`enddate` AS DATE) >='".$date."'")->get();



        if($cityPlan){

            echo json_encode(array(

                'success' => true,

                'cityPlan' => $cityPlan

            ));



        }else{

            echo json_encode(array(

                'fail' => true,

                'message' => 'Sorry'

            ));

        }

    }



    public function download_app(){

        return view('user.seller.download-app');

    }





    public function verify_email(request $request){

        if (Auth::check()) {

            $user = User::find(Auth::user()->id);

            $id=$user->email;

            $userfname=$user->fname;

            $userlname=$user->lname;

            $random_number = rand(1,999999);

            $verify_email_token =  Hash::make($random_number);

            $link = url('seller/verify-check?email='.encrypt($id).'&hash='.$verify_email_token);

            $emailData = array(

                'to'        => $id,

                'from'      => env('MAIL_USERNAME'),

                'subject'   => 'Email Verify',

                'view'      => 'user.seller.email_verify_mail',

                'content'=>"Hi ".Auth::user()->fname.", <br><br> Please <a href='".$link."'>click</a> on below URL or paste into your browser to verify your email ".$link

            );



            Mail::send($emailData['view'],$emailData, function ($message) use ($emailData) {

                $message

                    ->to($emailData['to'])

                    ->from($emailData['from'])

                    ->subject($emailData['subject']);



            });

            Session::flash('success_message', 'Mail has been sent successfully to verify email id!');

            return redirect('seller/profile');



        }else{

            Session::flash('error_message', 'Sorry You have to login first');

            return redirect('login');

        }



    }

    public function verify_check(Request $request)

    {

        $token = $request->hash;

        $email=decrypt($request->email);

        $user = User::where('email',$email)->where('status','1')->first();

        $user->is_email_verified = 1;

        $user->save();



        if (Auth::check()) {

            Session::flash('success_message', 'Email verified successfully !');

            return redirect('seller/profile');

        }else{

            Session::flash('success_message', 'Email verified successfully ! You can now login to see the status');

            return redirect("/login");

        }



    }



    public function verify_mobile(request $request)

    {

        if (Auth::check()) {

            $user = User::find(Auth::user()->id);

            $mobile = $user->mobile;



            $dataResponce = Helper::SENDOTPforCash(['mobile' => $mobile]);

          

//dd($data['otp']);

            if ($dataResponce['success']) {

                $_SESSION['otp'] =$dataResponce['otp'];

               // print_r($_SESSION['otp']);die;

                $user->otp_code = $dataResponce['otp'];

                $user->update();

                Session::flash('success_message', 'OTP send to your Registerd number!');

                return redirect()->back();



            } else {

                Session::flash('success_message', 'Something went wrong!');

                return redirect()->back();

            }



        }

    }

    public function verify_mobile_check(Request $request)

    {

        //echo "hi";die;

             $user = User::find(Auth::user()->id);





            $otp_code = $request->otp_code;



            $hasUser = User::where('id',$user->id)->where('otp_code','=',$otp_code)->first();





            if(count($hasUser)){

                $user->is_mobile_verified = 1;

                $user->save();

                return Response::json(array(

                    'otp_error' => true,

                    'msg' =>"OTP does not match!!"

                ),200

                );



            }

            else{

                return Response::json(array(

                    'otp_error' => true,

                    'msg' =>"OTP does not match!!"

                ),400

                );



            }

        }



    public function changePassword(){
        User::find(Auth::user()->id);

        return view("user.seller.change-password");

    }



    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(),

            [

                'password' => 'required|min:6|max:20|confirmed',

                'password_confirmation' => 'required|max:20|min:6',

                'current_password' => 'required|max:20|min:6',

            ]);

        if ($validator->fails())
        {

            return redirect('seller/change-password')->withInput()->withErrors($validator);

        }
        else

        {
            $user = User::find(Auth::user()->id);

            if (Hash::check($request->current_password, $user->password))

            {

                $user->password = Hash::make($request->password);

                $user->save();

                Session::flash('success_message', 'Successfully updated password!');

            }

            else

            {

                Session::flash('error_message', 'Current password is incorrect');

            }

            return redirect('seller/change-password');

        }

    }

    /**

     * enquires()

     * looged in user can get enquires

     * @return $enquires

     * Developer : jyotsna

     */

    public function enquires()
    {
        $id = Auth::user()->id;

        $enquires = Enquiry::where('sent_to',$id)->with('sender')->with('property','property.propertyType')->whereIn('property_id',Property::pluck('id'))->get();

        

     $view_phone=  DB::table('properties')

        ->join('view_phones', 'properties.id', '=', 'view_phones.property_id')

        ->join('users', 'users.id', '=', 'properties.id')

        ->where('properties.user_id',$id)

        ->select('properties.bed_room','properties.user_id','properties.property_type_id','view_phones.*','users.image as user_image')

         ->whereIn('property_id',Property::pluck('id'))

        ->get();

       

        return view("user.seller.enquires")

               ->with('enquires',$enquires)

               ->with('view_phones',$view_phone);
    }


 /**

     * acceptSiteVisitRequest()

     * remove from favorite list

     * @param $id

     */

    public function acceptSiteVisitRequest($id)
    {
            $enquery=Enquiry::where('id',$id)->with('sender')->with('property','property.propertyType')->first();

        /* Mail Code Start */

        $msg="Hi ".($enquery->sender->fname or '').",Welcome to Zonavivienda<br> Your site visit request is accepted by the seller";

        $msg.="Your property is ".$enquery->property->name."<br>";

        $msg.="Your location & date is ".$enquery->property->locality.', '.$enquery->visit_request_date."<br>";



        $emailData = array(

            'to'        => $enquery->sender->email,

            'from'      => 'endivesofttest@gmail.com',

            'subject'   => 'Zonavivienda',

            'view'      => 'user.site-visit-request-accept-email',

            'content'=>$msg,

            'name' => $enquery->sender->fname

        );



        Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {

            $message

                ->to($emailData['to'])

                ->from($emailData['from'])

                ->subject($emailData['subject']);



        });
        /* Mail Code End */
                $visitrequest = Enquiry::findOrFail($id);

                $visitrequest->status  =  1;

                $visitrequest->save();
                Session::flash('success_message', 'Visit for site request accepted successfully');

     }
    /**

     * declineSiteVisitRequest()

     * remove from favorite list

     * @param $id

     */
    public function declineSiteVisitRequest($id)
    {

        $myfavorite = Enquiry::find($id);

        $myfavorite->status  =  2;

        $myfavorite->save();

        Session::flash('error_message', 'Successfully Decline');

    }
}
