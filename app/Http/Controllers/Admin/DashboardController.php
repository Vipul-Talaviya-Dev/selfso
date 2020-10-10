<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function users(Request $request)
    {
    	$users = User::latest();
    	if($request->get('name')) {
    		$users = $users->where(function ($query) use($request) {
                $query->where('first_name', 'Like', '%'.$request->get('name').'%')->orWhere('last_name', 'Like', '%'.$request->get('name').'%')->orWhere('email', 'Like', '%'.$request->get('name').'%');
            });
    	}

    	$users = $users->paginate(15);
        return view('admin.user.index', ['users' => $users]);
    }

    public function userStatusChange(Request $request)
    {
    	if(!$user = User::find($request->get('id'))) {
            return response()->json([
                'status' => false,
            ]);
        }

        $user->status = ($user->status == 1) ? 0 : 1;
        $user->save();

        return response()->json([
            'status' => true
        ]);
    }

    public function userDelete(Request $request)
    {
    	if(!$user = User::find($request->get('id'))) {
            return response()->json([
                'status' => false,
            ]);
        }

        $user->delete();

        return response()->json([
            'status' => true
        ]);
    }
}
