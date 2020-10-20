<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Story;
use App\Models\Friend;
use App\Library\Helper;
use App\Models\StoryMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http\json_decode;
use App\Http\Controllers\Api\V1\Controller;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $loginUser = $this->user;
        $friendIds = Friend::accepted()->where('user_id', $loginUser->id)->pluck('to_user_id')->toArray();
        // $getStories = Story::with(['user'])->latest()->whereIn('user_id', $friendIds)->get();
        $stories = [];
        foreach (User::with(['stories'])->whereIn('id', $friendIds)->get() as $user) {
            $getStories = [];
            foreach ($user->stories()->where('created_at', '>', Carbon::now()->subMinutes(1440))->get() as $story) {
                $createdAt = Carbon::parse($story->created_at);
                $getStories[] = [
                    'id' => $story->id,
                    'media' => ($story->media) ? Helper::getImage($story->media, $story->type) : '',
                    'description' => $story->description ?: '',
                    'createdAt' => $createdAt->ago(),
                    'addMemory' => $story->add_memory,
                    'type' => $story->type, // 1: Image, 2: Video
                ];
            }
            if(count($getStories) > 0) {
                $stories[] = [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'mobile' => $user->mobile,
                    'image' => ($user->avatar) ? Helper::getImage($user->avatar) : Helper::USERIMAGE,
                    'stories' => $getStories
                ];
            }
        }

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'myStories' => self::myStories()['data']['myStories'],
                'otherStories' => $stories
            ]
        ], Helper::SUCCESS_CODE);
    }

    public function myStories()
    {
        $user = $this->user;
        $stories = [];
        foreach ($user->stories()->where('created_at', '>', Carbon::now()->subMinutes(1440))->get() as $story) {
            $createdAt = Carbon::parse($story->created_at);
            $stories[] = [
                'id' => $story->id,
                'media' => ($story->media) ? Helper::getImage($story->media, $story->type) : '',
                'description' => $story->description ?: '',
                'createdAt' => $createdAt->ago(),
                'addMemory' => $story->add_memory,
                'type' => $story->type, // 1: Image, 2: Video
            ];
        }

        $myStories = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'image' => ($user->avatar) ? Helper::getImage($user->avatar) : Helper::USERIMAGE,
            'stories' => $stories
        ];
        
        return [
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'myStories' => $myStories
            ]
        ];
    }

    public function create(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'media' => 'nullable',
            'description'=> 'nullable',
            'type'=> 'required|in:1,2',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => [],
            ], Helper::ERROR_CODE);
        }
        $publicKey = NULL;
        if($request->file('media')) {
            $profilePath = Helper::storeUserImagePath($user->id).'story/';
            $imageResponse = Helper::postUpload($profilePath, $request->file('media'), $request->get('type'));

            if($imageResponse['status'] == false) {
                DB::rollback();
                return response()->json([
                    'status' => Helper::ERROR_CODE,
                    'message' => $imageResponse['message'],
                    'data' => [],
                ], Helper::ERROR_CODE);
            }
            $publicKey = $imageResponse['publicKey'];
        }

        Story::create([
            'user_id' => $user->id,
            'media' => $publicKey,
            'description' => $request->get('description'),
            'add_memory' => $request->get('addMemory') ?: 0,
        ]);

        return response()->json([
            'status' => Helper::CREATE_CODE,
            'message' => 'Successfully add new story.',
            'data' => []
        ], Helper::CREATE_CODE);
    }

    public function storyMessage(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'storyId' => 'required|exists:stories,id',
            'storyUserId' => 'required|exists:users,id',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => [],
            ], Helper::ERROR_CODE);
        }
        
        StoryMessage::create([
            'login_user_id' => $user->id,
            'to_user_id' => $request->get('storyUserId'),
            'story_id' => $request->get('storyId'),
            'message' => $request->get('message'),
        ]);

        return response()->json([
            'status' => Helper::CREATE_CODE,
            'message' => 'Successfully add story message.',
            'data' => []
        ], Helper::CREATE_CODE);
    }
}
