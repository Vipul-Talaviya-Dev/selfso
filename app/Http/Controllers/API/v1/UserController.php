<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use App\Models\User;
use App\Models\Friend;
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
            'image' => 'nullable|image',
            'email'=> ['required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'mobile' => 'required|numeric',
            'categories' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => false,
                'message' => $error[0],
            ], Helper::ERROR_CODE);
        }

        if($request->file('image')) {
            $profilePath = Helper::storeUserImagePath($user->id);
            $imageResponse = Helper::imageUpload($profilePath, $request->file('image'));

            if($imageResponse['status'] == false) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => $imageResponse['message'],
                ], Helper::ERROR_CODE);
            }
            #exist image delete
            if($user->image) {
                Helper::imageRemove($user->image);
            }
            $user->image = $imageResponse['publicKey'];
        }

        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        $user->mobile = $request->get('mobile');
        $user->gender = $request->get('gender') ?: 0;
        $user->save();

        if($request->get('categories')) {
            $user->categories()->sync($request->get('categories'));
        }

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

    public function friendRequest(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'userId'=> 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => false,
                'message' => $error[0],
            ], Helper::ERROR_CODE);
        }

        $message = '';
        if(!Friend::where('user_id', $user->id)->where('to_user_id', $request->get('userId'))->exists()) {
            Friend::create([
                'user_id' => $user->id,
                'to_user_id' => $request->get('userId'),
            ]);
            $message = 'Friend request has been successfully sent.';
        } else {
            Friend::where('user_id', $user->id)->where('to_user_id', $request->get('userId'))->delete();
            $message = 'Friend request has been successfully cancel.';
        }

        return response()->json([
            'status' => true,
            'message' => $message,
        ], Helper::SUCCESS_CODE);
    }

    public function searchFriends(Request $request)
    {
        $loginUser = $this->user;
        $users = User::with('friend')->active()->where('id', '!=', $loginUser->id);

        if($request->get('name')) {
            $users = $users->where(function ($query) use($request) {
                $query->where('first_name', 'Like', '%'.$request->get('name').'%')->orWhere('last_name', 'Like', '%'.$request->get('name').'%')->orWhere('email', 'Like', '%'.$request->get('name').'%');
            });
        }

        $users = $users->get()->map(function (User $user) {
            return [
                'id' => $user->id,
                'name' => $user->fullName(),
                'email' => $user->email,
                'friendRequestButtonStatus' => ($user->friend) ?  $user->friend->status : 0, // Friend Request:- 1: Pending, 2: Accepted, 3: Blocked
            ];
        });

        return response()->json([
            'status' => true,
            'users' => $users
        ], Helper::SUCCESS_CODE);
    }
}
