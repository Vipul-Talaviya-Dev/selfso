<?php

namespace App\Http\Controllers\Api\V1;

use DB;
use Mail;
use Cloudder;
use Validator;
use Exception;
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
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => []
            ], Helper::ERROR_CODE);
        } else {
            if ($user = User::where('email', $request->get('email'))->first()) {
                if (\Hash::check($request->get('password'), $user->password)) {
                    if($request->get('fcm_token')) {
                        $user->fcm_token = $request->get('fcm_token');
                        $user->save();
                    }

                    return response()->json([
                        'status' => Helper::SUCCESS_CODE,
                        'message' => 'Successfully Login.',
                        'data' => [
                            'token' => $user->generateApiToken($user),
                            'user' => [
                                'first_name' => $user->first_name,
                                'last_name' => $user->last_name,
                                'email' => $user->email,
                                'mobile' => $user->mobile,
                                'image' => ($user->avatar) ? Helper::getImage($user->avatar) : Helper::USERIMAGE,
                            ]
                        ],
                    ], Helper::SUCCESS_CODE);        
                } else {
                    return response()->json([
                        'status' => Helper::ERROR_CODE,
                        'message' => 'Invalid Password.',
                        'data' => []
                    ], Helper::ERROR_CODE);    
                }
            } else {
                return response()->json([
                    'status' => Helper::ERROR_CODE,
                    'message' => 'Invalid Password.',
                    'data' => []
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
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'first_name'=> 'required',
                'last_name'=> 'required',
                'email'=> 'nullable|email|unique:users,email',
                'mobile' => 'nullable|numeric',
                'image' => 'nullable|image',
                'password' => 'required',
                'accountType' => 'required|numeric|in:1,2',
                'device' => 'required|numeric|in:1,2',
                'loginType' => 'required|numeric|in:1,2,3',
                'fcmToken' => 'nullable',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all(':message');
                DB::rollback();
                return response()->json([
                    'status' => Helper::ERROR_CODE,
                    'message' => $error[0],
                    'data' => []
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

                if($request->file('image')) {
                    $profilePath = Helper::storeUserImagePath($user->id);
                    $imageResponse = Helper::imageUpload($profilePath, $request->file('image'));

                    if($imageResponse['status'] == false) {
                        DB::rollback();
                        return response()->json([
                            'status' => Helper::ERROR_CODE,
                            'message' => $imageResponse['message'],
                            'data' => [],
                        ], Helper::ERROR_CODE);
                    }
                    
                    $user->image = $imageResponse['publicKey'];
                    $user->save();
                }

                if($request->get('categories')) {
                    $user->categories()->sync($request->get('categories'));
                }

                DB::commit();
                return response()->json([
                    'status' => Helper::CREATE_CODE,
                    'message' => 'Successfully Registered.',
                    'data' => [
                        'token' => $user->generateApiToken($user),
                        'user' => [
                            'first_name' => $user->first_name,
                            'last_name' => $user->last_name,
                            'email' => $user->email,
                            'mobile' => $user->mobile,
                            'image' => ($user->avatar) ? Helper::getImage($user->avatar) : Helper::USERIMAGE,
                        ],
                    ],
                ], Helper::CREATE_CODE);
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => Helper::SERVERERROR,
                'message' => $e->getMessage(),
                'data' => [],
            ], Helper::SERVERERROR); 
        }
    }

    public function forgotPassword(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|exists:users,email',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all(':message');
                DB::rollback();
                return response()->json([
                    'status' => Helper::ERROR_CODE,
                    'message' => $error[0],
                    'data' => [],
                ], Helper::ERROR_CODE);
            } else {
                if ($user = User::active()->where('email', $request->get('email'))->first()) {

                    $token = rand(111111, 999999);
                    $user->password = bcrypt($token);
                    $user->save();

                    Mail::send('emails.forgot-password', [
                        'name' => $user->name,
                        'password' => $token
                    ], function ($message) use ($user) {
                        $message->from('selfso@gmail.com', 'Selfso')
                            ->subject('Reset account')
                            ->to($user->email, $user->name);
                    });
                    DB::commit();
                    return response()->json([
                        'status' => Helper::SUCCESS_CODE,
                        'message' => 'Please Check Your Mail',
                        'data' => [],
                    ], Helper::SUCCESS_CODE);
                }
                DB::rollback();
                return response()->json([
                    'status' => Helper::ERROR_CODE,
                    'message' => 'Invalid Email.',
                    'data' => [],
                ], Helper::ERROR_CODE);
            }
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], Helper::ERROR_CODE); 
        }
    }
}
