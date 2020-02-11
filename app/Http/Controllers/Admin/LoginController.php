<?php

namespace App\Http\Controllers\Admin; //admin add
use App\Http\Requests;
use App\Http\Controllers\Controller; // using controller class
use Auth;
use Session;
use Illuminate\Http\Request;
use App\User;
use App\Admin;

class LoginController extends Controller
{

	public function __construct()
	{

	}
	
	public function index()
	{

		if(Auth::guard('admin')->user())
		{
			return redirect('admin/dashboard');
		}
		else
		{
			return view('admin.login.index');
		}
	}

	public function authenticate(Request $request)
	{
		if (Auth::guard('admin')->attempt(['email' => $request->username, 'password' => $request->password,'active' => 1]))
		{
			 $data=Admin::where('email',$request->username)->first();
		     Session::set('user_sdata',  $data);
			 return redirect('admin/dashboard');
		}
		else
		{
			$request->flash();
			Session::flash('message', 'Invalid Email or Password');
			return redirect('/gadmin');
		}
	}
	/**
	 * logout()
	 * logout admin
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */


   

	public function logout()
	{
		Auth::guard('admin')->logout();
		return redirect('/gadmin');
	}

}