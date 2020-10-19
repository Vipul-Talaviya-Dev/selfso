<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use Carbon\Carbon;
use App\Models\Story;
use App\Models\Friend;
use App\Library\Helper;
use App\Models\StoryMessage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Api\V1\Controller;

class StoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->user;
        $friendIds = Friend::accepted()->where('user_id', $user->id)->pluck('to_user_id')->toArray();
        $stories = Story::with(['user'])->latest()->whereIn('user_id', $friendIds)->get()->map(function (Story $story) {
            $createdAt = Carbon::parse($story->created_at);
            return [
                'id' => $story->id,
                'media' => ($story->media) ? Helper::getImage($story->media, $story->type) : '',
                'description' => $story->description ?: '',
                'createdAt' => $createdAt->ago(),
                'addMemory' => $story->add_memory,
                'type' => $story->type, // 1: Image, 2: Video
                'user' => [
                    'id' => $story->user->id,
                    'first_name' => $story->user->first_name,
                    'last_name' => $story->user->last_name,
                    'email' => $story->user->email,
                    'mobile' => $story->user->mobile,
                    'image' => ($story->user->avatar) ? Helper::getImage($story->user->avatar) : Helper::USERIMAGE,
                ],
            ];
        });

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'stories' => $stories
            ]
        ], Helper::SUCCESS_CODE);
    }

    public function myStories(Request $request)
    {
        $user = $this->user;
        $stories = $user->stories->map(function (Story $story) {
            $createdAt = Carbon::parse($story->created_at);
            return [
                'id' => $story->id,
                'media' => ($story->media) ? Helper::getImage($story->media, $story->type) : '',
                'description' => $story->description ?: '',
                'createdAt' => $createdAt->ago(),
                'addMemory' => $story->add_memory,
                'type' => $story->type,
                'user' => [
                    'id' => $story->user->id,
                    'first_name' => $story->user->first_name,
                    'last_name' => $story->user->last_name,
                    'email' => $story->user->email,
                    'mobile' => $story->user->mobile,
                    'image' => ($story->user->avatar) ? Helper::getImage($story->user->avatar) : Helper::USERIMAGE,
                ],
            ];
        });

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'stories' => $stories
            ]
        ], Helper::SUCCESS_CODE);
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
