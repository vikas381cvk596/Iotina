<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Auth;
use Redirect;
use Session;
use Validator;
use Illuminate\Support\Facades\Input;
use App\User;

class AuthController extends Controller
{
    public function loginUser(Request $request)
    {
    	/*$this->validate($request, ['user_name' => 'required', 'password' => 'required']);

    	$user_name = $request->input('user_name');
    	$password = $request->input('password');
    	
    	if (Auth::attempt(['user_name' => $user_name, 'password' => $password, 'is_active' => 'Yes'])) 
    	{
    		Session::put('user', Auth::user());
    		return redirect ('/admin');
		}
		
		return redirect()->back()->withErrors(['authenticateMessage' => ['Authentication failed. Please check the user name, password fields & try again. ']]);*/
		return view('admin.index');
    }

    public function registerUser(Request $request) 
    {
        /*$user = new User ();
        $user->user_name = $request->get ( 'user_name' );
        $user->password = Hash::make ( $request->get ( 'password' ) );
        $user->is_active = 'Yes';
        $user->remember_token = $request->get ( '_token' );
        $user->save ();
        return redirect('/admin/login')->with('status', 'A new user has been created. Please login to access the system.');
        //return redirect ( '/' );*/
    }

    public function logoutUser(Request $request)
    {
    	Auth::logout();
    	Session::flush();
    	return redirect('/admin/login');
    }

    public function showHomePage(Request $request) 
    {
    	if (Auth::check()) {
	    	return redirect('/admin');
    	} else {
	    	return view('/admin/login');
    	}
    }

    public function showRegisterPage(Request $request) {
    	if (Auth::check()) {
	    	return redirect('/admin');
    	} else {
	    	return view('/admin/register');
    	}
    }

    public function returnPage($page = 'index')
    {
        if (Auth::check()) {
			if ($page != "index" && Auth::user()->user_name == "dashboard") {
				return redirect('/admin');
			} else {
				return view('admin.' . $page)->with(['page' => $page]);
			}
        } else {
        	return view('admin/login');
        }
    }
}
