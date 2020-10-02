<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use Carbon\Carbon;
use App\Models\Story;
use App\Models\Friend;
use App\Library\Helper;
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
                'media' => ($story->media) ? Helper::getImage($story->media) : '',
                'description' => $story->description ?: '',
                'createdAt' => $createdAt->ago(),
                'user' => [
                    'id' => $story->user->id,
                    'name' => $story->user->fullName(),
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
                'media' => ($story->media) ? Helper::getImage($story->media) : '',
                'description' => $story->description ?: '',
                'createdAt' => $createdAt->ago(),
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
            $imageResponse = Helper::postUpload($profilePath, $request->file('media'));

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
        ]);

        return response()->json([
            'status' => Helper::CREATE_CODE,
            'message' => 'Successfully add new story.',
            'data' => []
        ], Helper::CREATE_CODE);
    }
}
