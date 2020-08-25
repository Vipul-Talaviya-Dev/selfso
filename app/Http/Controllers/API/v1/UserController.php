<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use App\Library\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Api\V1\Controller;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->user;
        return response()->json([
            'status' => true,
            'user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'mobile' => $user->mobile,
                'image' => '',
            ],
        ], Helper::SUCCESS_CODE);
    }

    public function profileUpdate(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'first_name'=> 'required',
            'last_name'=> 'required',
            'email'=> ['required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'mobile' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => false,
                'message' => $error[0],
            ], Helper::ERROR_CODE);
        }

        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->mobile = $request->get('mobile');
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Successfully Profile Updated.',
        ], Helper::SUCCESS_CODE);
    }

    public function changePassword(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'oldPassword' => 'required',
            'newPassword' => 'required|min:6|same:confirmPassword',
            'confirmPassword' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => false,
                'message' => $error[0],
            ], Helper::ERROR_CODE);
        }

        if (\Hash::check($request->get('oldPassword'), $user->password)) {
            $user->password = bcrypt($request->get('newPassword'));
            $user->save();
            
            $response = [
                'status' => true,
                'success' => 'Password has been changed successfully.',
            ];
        } else {
            $response = [
                'status' => false,
                'success' => 'Invalid Old Password.',
            ];
        }
        
        return response()->json($response, Helper::SUCCESS_CODE);
    }

    public function logout()
    {
        \Auth::logout();
        return $response = [
            'status' => true,
            'success' => 'Logout Successfully.',
        ];
    }
}
