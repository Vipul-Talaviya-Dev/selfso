<?php

namespace App\Http\Controllers\API\v1;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            $response = [
                'status' => false,
                'message' => $error[0],
            ];
        } else {
        	// Login Code
        	$response = [
                'status' => true,
                'message' => 'Login Done',
            ];
        }

        return $response;
    }

    public function register(Request $request)
    {
        dd($request);        
    }
}
