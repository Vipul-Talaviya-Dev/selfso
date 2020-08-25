<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use App\Models\User;
use App\Library\Helper;
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
            return response()->json([
                'status' => false,
                'message' => $error[0],
            ], Helper::ERROR_CODE);
        } else {
            if ($user = User::where('email', $request->get('email'))->first()) {
                if (\Hash::check($request->get('password'), $user->password)) {
                    if($request->get('fcm_token')) {
                        $user->fcm_token = $request->get('fcm_token');
                        $user->save();
                    }

                    return response()->json([
                        'status' => true,
                        'message' => 'Successfully Registered.',
                        'token' => $user->generateApiToken($user),
                        'user' => [
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'email' => $user->email,
                            'mobile' => $user->mobile,
                            'image' => '',
                        ],
                    ], Helper::SUCCESS_CODE);        
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Invalid Password.',
                    ], Helper::ERROR_CODE);    
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Password.',
                ], Helper::ERROR_CODE);
            }
        }
    }

    /**
     * { New User Register }
     *
     * @param  \Illuminate\Http\Request  $request  The request
     *
     * @return <type> (New User Register)
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'=> 'required',
            'last_name'=> 'required',
            'email'=> 'nullable|email|unique:users,email',
            'mobile' => 'nullable|numeric',
            'password' => 'required',
            'accountType' => 'required|numeric|in:1,2',
            'device' => 'required|numeric|in:1,2',
            'loginType' => 'required|numeric|in:1,2,3',
            'fcmToken' => 'nullable',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => false,
                'message' => $error[0],
            ], Helper::ERROR_CODE);
        } else {
            $user = User::create([
                'first_name' => $request->get('first_name'),
                'last_name'  => $request->get('last_name'),
                'email'      => !empty($request->get('email')) ? $request->get('email') : NULL,
                'mobile'     => !empty($request->get('mobile')) ? $request->get('mobile') : NULL ,
                'password'   => bcrypt($request->get('password')),
                'fcm_token'  => $request->get('fcmToken') ?: NULL,
                'device_id' => $request->get('device') ?: 1,
                'login_type' => $request->get('loginType') ?: 1,
                'account_type' => $request->get('accountType') ?: 1,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Successfully Registered.',
                'token' => $user->generateApiToken($user),
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'image' => '',
                ],
            ], Helper::SUCCESS_CODE);
        }
    }
}
