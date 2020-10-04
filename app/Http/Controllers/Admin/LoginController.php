<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function loginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
    	$this->validate($request, [
    		'email' => 'required|email|exists:admins,email',
    		'password' => 'required'
    	]);

    	if($admin = Admin::where('email', $request->get('email'))->first()) {
    		if(\Hash::check($request->get('password'), $admin->password)) {
                // $remember = ($request->get('remember') == 1 ? true : false);
                // Auth::attempt(['email' => $admin->email, 'password' => $admin->password], $remember);
    			Auth::guard('admin')->login($admin);
                return redirect(route('admin.dashboard'));
    		} else {
    			return redirect()->back()->with(['error' => 'Invalid Email Or Password.']);
    		}
    	} else {
    		return redirect()->back()->with(['error' => 'Invalid Email Or Password.']);
        }
    }

    public function logout()
    {
    	Auth::guard('admin')->logout();
        return redirect(route('admin.loginForm'));
    }
}
