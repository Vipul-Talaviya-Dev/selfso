<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use Carbon\Carbon;
use App\Models\Like;
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
            'categoryId' => 'nullable|numeric|exists:categories,id',
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
            'category_id' => $request->get('categoryId'),
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
            'message' => 'Successfully add new post.',
        ], Helper::SUCCESS_CODE);
    }

    public function myPost(Request $request)
    {
        $user = $this->user;
        $posts = Post::with(['likes'])->active()->where('user_id', $user->id)->get()->map(function (Post $post) {
            $createdAt = Carbon::parse($post->created_at);
            return [
                'id' => $post->id,
                'media' => ($post->media) ? Helper::getImage($post->media) : '',
                'description' => $post->description ?: '',
                'link' => $post->link ?: '',
                'likeCount' => $post->likes->count(),
                'commentCount' => 0,
                'createdAt' => $createdAt->ago(),
            ];
        });

        return response()->json([
            'status' => true,
            'posts' => $posts
        ], Helper::SUCCESS_CODE);
    }

    public function postLikeDisLike(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'postId' => 'required|exists:posts,id',
            'smiley' => 'nullable'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => false,
                'message' => $error[0],
            ], Helper::ERROR_CODE);
        }

        if(!Like::where('user_id', $user->id)->where('post_id', $request->get('postId'))->exists()) {
            Like::create([
                'user_id' => $user->id,
                'post_id' => $request->get('postId'),
                'smiley' => $request->get('smiley'),
            ]);
        } else {
            Like::where('user_id', $user->id)->where('post_id', $request->get('postId'))->delete();
        }

        return response()->json([
            'status' => true,
            'message' => '',
        ], Helper::SUCCESS_CODE);
    }
}
