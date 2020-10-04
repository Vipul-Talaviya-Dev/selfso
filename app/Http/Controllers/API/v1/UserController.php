<?php

namespace App\Http\Controllers\Api\V1;

use DB;
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
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'user' => [
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'image' => ($user->avatar) ? Helper::getImage($user->avatar) : Helper::USERIMAGE,
                ]
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
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => [],
            ], Helper::ERROR_CODE);
        }

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
            'status' => Helper::SUCCESS_CODE,
            'message' => 'Successfully Profile Updated.',
            'data' => [],
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
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => [],
            ], Helper::ERROR_CODE);
        }

        if (\Hash::check($request->get('oldPassword'), $user->password)) {
            $user->password = bcrypt($request->get('newPassword'));
            $user->save();
            
            return response()->json([
                'status' => Helper::SUCCESS_CODE,
                'message' => 'Password has been changed successfully.',
                'data' => [],
            ], Helper::SUCCESS_CODE);
        } else {
            return response()->json([
                'status' => Helper::ERROR_CODE,
                'message' => 'Invalid Old Password.',
                'data' => [],
            ], Helper::ERROR_CODE);
        }
    }

    public function logout()
    {
        \Auth::logout();
        return $response = [
            'status' => Helper::SUCCESS_CODE,
            'message' => 'Logout Successfully.',
            'data' => [],
        ];
    }

    public function friendRequestList()
    {
        $user = $this->user;
        $friends = Friend::pending()->where('user_id', $user->id)->get()->map(function (Friend $friend) {
            if(isset($friend->user)) {
                return [
                    'userConfirmId' => $friend->to_user_id,
                    'name' => $friend->user->fullName(),
                    'image' => ($friend->user->avatar) ? Helper::getImage($friend->user->avatar) : Helper::USERIMAGE,
                ];
            }
        });

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'friends' => $friends
            ]
        ], Helper::SUCCESS_CODE);
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
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => []
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
            'status' => Helper::SUCCESS_CODE,
            'message' => $message,
            'data' => []
        ], Helper::SUCCESS_CODE);
    }

    public function friendRequestConfirm(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'userConfirmId'=> 'required|exists:friends,to_user_id',
            'status' => 'required|numeric|in:1,2'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => [],
            ], Helper::ERROR_CODE);
        }
        $message = '';
        if($friend = Friend::where('user_id', $user->id)->where('to_user_id', $request->get('userConfirmId'))->first()) {
            if($request->get('status') == 1) {
                $friend->status = Friend::ACCEPTED;
                $friend->save();

                $message = 'Accept your friend request.';
            } else {
                $friend->delete();
                $message = 'Cancel your friend request.';
            }
        }

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'message' => $message,
            'data' => [],
        ], Helper::SUCCESS_CODE);
    }

    public function myFriends(Request $request)
    {
        $user = $this->user;
        $friends = Friend::with(['user'])->accepted()->where('user_id', $user->id);

        if($request->get('name')) {
            $friends = $friends->whereHas('user', function ($query) use($request) {
                $query->where('first_name', 'Like', '%'.$request->get('name').'%')->orWhere('last_name', 'Like', '%'.$request->get('name').'%')->orWhere('email', 'Like', '%'.$request->get('name').'%');
            });
        }

        $friends = $friends->get()->map(function (Friend $friend) {
            if(isset($friend->user)) {
                return [
                    'id' => $friend->user->id,
                    'name' => $friend->user->fullName(),
                    'image' => ($friend->user->avatar) ? Helper::getImage($friend->user->avatar) : Helper::USERIMAGE,
                ];
            }
        });

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'friends' => $friends
            ]
        ], Helper::SUCCESS_CODE);
    }

    public function searchFriends(Request $request)
    {
        $loginUser = $this->user;
        $users = User::with('friend')->active()->where('id', '!=', $loginUser->id);

        if(!empty($request->get('contactNos'))) {
            $users = $users->whereIn('mobile', $request->get('contactNos'));
        }

        if(!empty($request->get('categoryIds'))) {
            $userIds = DB::table('category_user')->whereIn('category_id', $request->get('categoryIds'))->pluck('user_id')->unique()->toArray();
            $users = $users->whereIn('id', $userIds);
        }

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
                'image' => ($user->avatar) ? Helper::getImage($user->avatar) : Helper::USERIMAGE,
                'friendRequestButtonStatus' => ($user->friend) ?  $user->friend->status : 0, // Friend Request:- 1: Pending, 2: Accepted, 3: Blocked
            ];
        });

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'users' => $users
            ]
        ], Helper::SUCCESS_CODE);
    }
}
