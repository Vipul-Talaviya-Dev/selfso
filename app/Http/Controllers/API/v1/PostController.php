<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use App\Models\Post;
use App\Library\Helper;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Api\V1\Controller;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'media' => 'nullable',
            'description'=> 'nullable',
            'link'=> 'nullable|url',
            'type'=> 'nullable|in:0,1,2',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => false,
                'message' => $error[0],
            ], Helper::ERROR_CODE);
        }
        $publicKey = NULL;
        if($request->file('media')) {
            $profilePath = Helper::storeUserImagePath($user->id).'posts/';
            $imageResponse = Helper::postUpload($profilePath, $request->file('media'));

            if($imageResponse['status'] == false) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => $imageResponse['message'],
                ], Helper::ERROR_CODE);
            }
            $publicKey = $imageResponse['publicKey'];
        }

        $post = Post::create([
            'user_id' => $user->id,
            'media' => $publicKey,
            'description' => $request->get('description'),
            'link' => $request->get('link'),
            'type' => $request->get('type'),
        ]);

        if($request->get('tagFriends')) {
            $post->tagFriends()->sync($request->get('tagFriends'));
        }

        return response()->json([
            'status' => true,
            'message' => 'Successfully Profile Updated.',
        ], Helper::SUCCESS_CODE);
    }
}
