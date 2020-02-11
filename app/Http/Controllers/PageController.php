<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\User;
use App\UserKyc;
use App\Subscribe;
use App\ContactUs;
use App\Cms;
use Validator;
use Response;
use Session;
use App\Faq;
use Hash;
use URL;
class PageController extends Controller
{
    public function __construct()
    {
       parent::__construct();
    }

    function contact_us()
    {
       return view('front.pages.contact_us');
    }

    function about_us()
    {
       $content= Cms::find(7);
       return view('front.pages.about_us',compact('content'));
    }

    function term_condition()
    {
        $content= Cms::find(2);
          return view('front.pages.term_condition',compact('content'));
    } 

    function privacy_policy()
    {
        $content= Cms::find(6);
           return view('front.pages.privacy_policy',compact('content'));
    }
    
    function return_policy() 
    {      $content= Cms::find(11);
        return view('front.pages.return_policy',compact('content'));
    } 

    function customer_services()
    {
           return view('front.pages.customer_service');
    } 

     function work_with_us()
    {
          return view('front.pages.work_with_us');
    } 

	function secure_payment()
    {
          return view('front.pages.secure_payment');
    } 
	
	function shipping_delivery()
    {
        $content= Cms::find(5);
          return view('front.pages.shipping_delivery',compact('content'));
    } 

    function payment_policy(){
        $content = Cms::find(12);
        return view('front.pages.payment_policy', compact('content'));
    }
	
	function guarantee()
    {
          return view('front.pages.guarantee');
    } 

  function faq()
    {
		  $data= Faq::where('banned',0)->get();
          return view('front.pages.faq',compact('data'));
    } 

	function discount_information()
    {
        $content= Cms::find(10);
          return view('front.pages.discount_information',compact('content'));
    }

    function subscribe(Request $request)
    {
        $count=Subscribe::where('email',$request->email)->get()->count(); 		
		$data['email']=$request->email;
        if(!$count>0)
		{			
			 $obj= new Subscribe($data);
			 if($obj->save())
			 {
			  echo json_encode(array('status'=>true,'message'=>"Subscribed Successfully"));
			 }
			 else
			 {
			  echo json_encode(array('status'=>false,'message'=>"Please Try again"));	 
			 }
		}
		else
		{
			echo json_encode(array('status'=>false,'message'=>"Already Subscribed"));
		}
    }
	
	 public function contact_us_store(Request $request)
    {
        $userData = array(
            'email'     => $request->input('email'),
            'name'     => $request->input('fname')." ".$request->input('lname'),
            'mobile'    => $request->input('mobile'),
            'message'   => $request->input('message'),
        );
        $rules = array(  
        );
        $validator = Validator::make($userData,$rules);
        if($validator->fails())
            echo json_encode(array(
                'fail' => true,
                'errors' => $validator->getMessageBag()->toArray()
            ));
        else {
            if (ContactUs::create($userData)) {
                $desc="Your request has been submitted successfully";
                $subject='Contact us on Grocito';
                // $emailData = array(
                    // 'to'        => $userData['email'],
                    // 'from'      => env('MAIL_USERNAME'),
                    // 'subject'   => $subject,
                    // 'view'      => 'user.seller.subscribemailview',
                    // 'content'=>$desc
                // );

                // try{
                    // Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                        // $message
                            // ->to($emailData['to'])
                            // ->from($emailData['from'])
                            // ->subject($emailData['subject']);
                    // });

                // }catch(Exception $e) {
                    // echo 'Message: ' .$e->getMessage();
                // }

                echo json_encode(array(
                    'success' => true,
                ));
            }
        }

    }
	
    function sitemap(){
        return view('front.pages.sitemap');
    }

}
?>