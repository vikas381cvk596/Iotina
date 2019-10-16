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
use App\Services\OrganisationService;

class AuthController extends Controller
{
    public function loginUser(Request $request)
    {
        $this->validate($request, ['email' => 'required', 'password' => 'required']);

        $email = $request->input('email');
        $password = $request->input('password');

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            Session::put('user', Auth::user());
            return view('admin.index');
        } else {
            return redirect('/admin/login')->with('status', 'Invalid Email or Password Combination');
        }
        
        //return redirect()->back()->withErrors(['authenticateMessage' => ['Authentication failed. Please check the user name, password fields & try again. ']]);
        
    }

    public function registerUser(Request $request) 
    {
        $user = new User ();
        $user->name = $request->get ( 'user_name' );
        $user->email = $request->get ( 'user_email' );
        $user->password = Hash::make ( $request->get ( 'password' ) );
        $user->role = "super_admin";
        $org_name = $request->get ( 'user_organisation' );
        //$user->is_active = 'Yes';
        $user->remember_token = $request->get ( '_token' );
        

        $OrganisationService = new OrganisationService();
        $org_id = $OrganisationService->createOrganisation($org_name);

        if ($org_id == 0) { // Error: Organisation Already Exists
            return redirect('/admin/register')->with('status', 'Error: Organisation Already Exists');
        } else {
            $user->org_id = $org_id;
            $user->save();
            return redirect('/admin/login')->with('status', 'A new user has been created. Please login to access the system.');
        }

        //return redirect ( '/' );
    }

    public function logoutUser(Request $request)
    {
        Auth::logout();
        Session::flush();
        return view('/admin/login');
    }

    public function showHomePage(Request $request) 
    {
        if (Auth::check()) {
            return view('admin.index');
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

    public function anyPage($page='index') {
        if (Auth::check()) {
            return view('admin.' . $page)->with(['page' => $page]);
        } else {
            return view('/admin/login');
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
