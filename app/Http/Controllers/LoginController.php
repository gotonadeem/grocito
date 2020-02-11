<?php
namespace App\Http\Controllers; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller; // using controller class
use Illuminate\Http\Request;
use Validator;
use App\Admin;
use Session;
use Hash;
use Illuminate\Support\Facades\Mail;
class LoginController extends Controller
{
	public function __construct()
	{
		parent::__construct();
	}

    public function index()
    {
      /* if (Auth::guard('admin')->user()) {
            return redirect('admin/dashboard');
        } else {
            return view('admin.login.index');
        }*/
    }
    
      public function forgotPassword() {
        return view('admin.passwords.email');
    }
    
     public function sendVerifyLink(Request $request) {
        
        $validator = Validator::make($request->all(), [
                    'email' => 'required|max:255|email',
        ]);

        //check user validations
        if ($validator->fails()) {
            
            return redirect('admin/forgot-password')
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $user = Admin::where('email', $request->email)->first();
            
            if (!empty($user)) {
                
                $id = $user->id;
                $link = url('admin/forgot_changepassword/' . encrypt($id));

                /* Mail Code Start */
                $emailData = array(
                    'to' => $request->email,
                    'from' => strtolower(env('APP_NAME')).'@gmail.com',
                    'subject' => 'Reset Password',
                    'view' => 'user.forgot',
                    'content' => "Please click on below URL or paste into your browser to reset your Password \n\n " . $link . "\n" . "\n\nThanks\nAdmin Team"
                );

                Mail::send($emailData['view'], $emailData, function ($message) use ($emailData) {
                    $message
                            ->to($emailData['to'])
                            ->from($emailData['from'])
                            ->subject($emailData['subject']);
                });
                /* Mail Code End */

                Session::flash('success_message', 'An email with the reset password link has been sent to you. Please check your inbox or spam folder');
                return redirect('admin/forgot-password');
            } else {
                Session::flash('error_message', 'This email id is not registered');
                return redirect('admin/forgot-password');
            }
        }
    }


public function forgot_changepassword($token) {
   
        $id = decrypt($token);
        $user = Admin::find($id);
  
        if (!empty($user)) {
            Session::set('reset-password', $user->id);
            return view('admin.passwords.reset');
        } else {
            Session::flash('error_message', 'Your link is not verify. Please correct your link..!!');
            return redirect('/');
        }
    }


 public function resetPassword(Request $request) {
        $data = $request->all();
        $rules = array(
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        );

        // Create a new validator instance.
        $validator = Validator::make($data, $rules, [
                    'password.confirmed' => 'Passwords does not matched.',
        ]);

        if ($validator->passes()) {

            $id = Session::get('reset-password');

            $user = Admin::find($id);
            $user->password = Hash::make($data['password']);
            $user->save();
            // print_r($user_found);exit();
            Session::set('reset-password', '');
            Session::flash('success_message', "Password successfully reset");
            return redirect('admin');
        }
        //Session::flash('error_message', "Please Try Again");
        return redirect('admin/reset-password')->withErrors($validator);
    }



	
	public function changePassword()
    {
            return view("login.changePassword");
        
    }
    
   public function updatePassword(Request $request)
    {
		
        $validator = Validator::make($request->all(),
            [
                'password' => 'required|min:6|max:20|confirmed',
                'password_confirmation' => 'required|max:20|min:6',
                'old_password' => 'required|max:20|min:6',
            ]);
               //dd($validator);
        if ($validator->fails())
        {
            return redirect('/login/changePassword')->withInput()->withErrors($validator);
        }
        else
        {
			if (Auth::check()) 
			{
            $user = Auth::user(); 
			//pr($user); die('GEWF');
			if ($user->is_frontend == 1) 
			{
				
             if ($user->status == 1) 
			 {
				 if ($user->is_deactivated == 0) 
				 {
					//echo $request->old_password;die; 
                    
					   if (Hash::check($request->old_password, $user->password))
					  {
						
						$user->password = Hash::make($request->password);
						$user->save();
						Session::flash('success_message', 'Successfully updated password!');
                       
					  }
						else
						{
                           
							Session::flash('error_message', 'Current password is incorrect');
						}

                        if ($user->role == 2) 
                         {
                            return redirect('/customer/my-account');
                        } 
						else
                         {
                            return redirect('/my-account');
                        }
						
                } 
				else 
				{
                   
                    Session::flash('error_message', 'Sorry Your Account is Disabled By Admin ! Please Contact to Admin.');
					Auth::logout();
				}
				
			 }
			
			}
    			
			} 
			else 
			{
				Session::flash('error_message', 'Sorry You have to login first');
				 return redirect('login');
			
			}
		}
    }
    
}