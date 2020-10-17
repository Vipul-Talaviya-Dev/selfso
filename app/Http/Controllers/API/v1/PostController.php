<?php

namespace App\Http\Controllers\Api\V1;

use Validator;
use Carbon\Carbon;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Models\Friend;
use App\Models\Comment;
use App\Models\SavePost;
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
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => [],
            ], Helper::ERROR_CODE);
        }
        $publicKey = NULL;
        if($request->file('media')) {
            $profilePath = Helper::storeUserImagePath($user->id).'posts/';
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

        $post = Post::create([
            'category_id' => $request->get('categoryId'),
            'user_id' => $user->id,
            'media' => $publicKey,
            'description' => $request->get('description'),
            'link' => $request->get('link'),
            'type' => $request->get('type'),
        ]);

        if($request->get('tagFriends')) {
            $tagFriends = array_filter(json_decode($request->get('tagFriends'), true));
            if(!empty($tagFriends)) {
                $tagFriendIds = User::select('id')->whereIn('id', $tagFriends)->pluck('id')->toArray();
                if(!empty($tagFriendIds)) {
                    $post->tagFriends()->sync($tagFriendIds);
                }
            }
        }

        return response()->json([
            'status' => Helper::CREATE_CODE,
            'message' => 'Successfully add new post.',
            'data' => []
        ], Helper::CREATE_CODE);
    }

    public function myPosts(Request $request)
    {
        $user = $this->user;
        $posts = Post::latest()->with(['user', 'likes', 'comments', 'tagFriends'])->active()->where('user_id', $user->id)->get();
        $feeds = $discovers = [];
        foreach ($posts as $post) {
            $savePost = SavePost::where('user_id', $user->id)->where('post_id', $post->id)->first();
            $tagFriends = $post->tagFriends->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->fullName(),
                    'image' => ($user->avatar) ? Helper::getImage($user->avatar) : Helper::USERIMAGE,
                ];
            });
            $createdAt = Carbon::parse($post->created_at);
            $feeds[] = [
                'id' => $post->id,
                'media' => ($post->media) ? Helper::getImage($post->media) : '',
                'description' => $post->description ?: '',
                'link' => $post->link ?: '',
                'type' => $post->type,
                'likeCount' => $post->likes->count(),
                'commentCount' => $post->comments->count(),
                'savePostFlag' => ($savePost) ? 1 : 0,
                'createdAt' => $createdAt->ago(),
                'user' => [
                    'id' => $post->user->id,
                    'first_name' => $post->user->first_name,
                    'last_name' => $post->user->last_name,
                    'email' => $post->user->email,
                    'mobile' => $post->user->mobile,
                    'image' => ($post->user->avatar) ? Helper::getImage($post->user->avatar) : Helper::USERIMAGE,
                ],
                'tagFriends' => $tagFriends,
            ];
        }

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'feeds' => $feeds,
                'discovers' => $feeds,
            ]
        ], Helper::SUCCESS_CODE);
    }

    public function postLikeDisLike(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'postId' => 'required|exists:posts,id,deleted_at,NULL',
            'smiley' => 'nullable'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => []
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
            'status' => Helper::SUCCESS_CODE,
            'message' => '',
        ], Helper::SUCCESS_CODE);
    }

    public function posts(Request $request)
    {
        $user = $this->user;
        $friendIds = Friend::accepted()->where('user_id', $user->id)->pluck('to_user_id')->toArray();
        $userIds = array_merge([$user->id], $friendIds);
        $posts = Post::latest()->with(['user', 'likes', 'tagFriends', 'comments'])->active()->whereIn('user_id', $userIds)->get();
        $feeds = $discovers = [];
        foreach ($posts as $post) {
            $savePost = SavePost::where('user_id', $user->id)->where('post_id', $post->id)->first();
            $tagFriends = $post->tagFriends->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->fullName(),
                    'image' => ($user->avatar) ? Helper::getImage($user->avatar) : Helper::USERIMAGE,
                ];
            });
            $createdAt = Carbon::parse($post->created_at);
            $feeds[] = [
                'id' => $post->id,
                'media' => ($post->media) ? Helper::getImage($post->media) : '',
                'description' => $post->description ?: '',
                'link' => $post->link ?: '',
                'type' => $post->type,
                'likeCount' => $post->likes->count(),
                'commentCount' => $post->comments->count(),
                'savePostFlag' => ($savePost) ? 1 : 0,
                'createdAt' => $createdAt->ago(),
                'user' => [
                    'id' => $post->user->id,
                    'first_name' => $post->user->first_name,
                    'last_name' => $post->user->last_name,
                    'email' => $post->user->email,
                    'mobile' => $post->user->mobile,
                    'image' => ($post->user->avatar) ? Helper::getImage($post->user->avatar) : Helper::USERIMAGE,
                ],
                'tagFriends' => $tagFriends,
            ];
        }

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'feeds' => $feeds,
                'discovers' => $feeds,
            ]
        ], Helper::SUCCESS_CODE);
    }

    public function comment(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'postId' => 'required|numeric|exists:posts,id',
            'commentId' => 'nullable|numeric|exists:comments,id',
            'comment'=> 'required',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => [],
            ], Helper::ERROR_CODE);
        }
        
        Comment::create([
            'parent_id' => $request->get('commentId') ?: NULL,
            'user_id' => $user->id,
            'post_id' => $request->get('postId'),
            'message' => $request->get('comment'),
        ]);

        return response()->json([
            'status' => Helper::CREATE_CODE,
            'message' => 'Successfully add new comment.',
            'data' => [],
        ], Helper::CREATE_CODE);
    }

    public function comments(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'postId' => 'required|numeric|exists:posts,id',
            'commentId' => 'nullable|numeric|exists:comments,id',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => []
            ], Helper::ERROR_CODE);
        }

        $comments = Comment::latest()->with(['user']);

        if($request->get('commentId')) {
            $comments = $comments->where('parent_id', $request->get('commentId'));
        } else {
            $comments = $comments->where('parent_id', NULL);
        }

        $comments = $comments->where('post_id', $request->get('postId'))->get()->map(function (Comment $comment) {
            return [
                'id' => $comment->id,
                'comment' => $comment->message,
                'user' => [
                    'id' => ($comment->user) ? $comment->user->id : 0,
                    'name' => ($comment->user) ? $comment->user->fullName() : '',
                    'image' => ($comment->user->avatar) ? Helper::getImage($comment->user->avatar) : Helper::USERIMAGE,
                ]
            ];
        });

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'comments' => $comments
            ]
        ], Helper::SUCCESS_CODE);
    }

    public function delete($id)
    {
        $user = $this->user;
        if(!$post = Post::latest()->with(['likes', 'comments'])->where('user_id', $user->id)->find($id)) {
            return response()->json([
                'status' => Helper::ERROR_CODE,
                'message' => "Incorrect post selected",
                'data' => [],
            ], Helper::ERROR_CODE);
        }

        $post->likes()->delete();
        $post->comments()->delete();
        $post->delete();

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'message' => "Post has been successfully deleted",
            'data' => [],
        ], Helper::SUCCESS_CODE);
    }

    public function savePostDisSave(Request $request)
    {
        $user = $this->user;
        $validator = Validator::make($request->all(), [
            'postId' => 'required|exists:posts,id,deleted_at,NULL',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all(':message');
            return response()->json([
                'status' => Helper::ERROR_CODE,
                'message' => $error[0],
                'data' => []
            ], Helper::ERROR_CODE);
        }

        if(!SavePost::where('user_id', $user->id)->where('post_id', $request->get('postId'))->exists()) {
            SavePost::create([
                'user_id' => $user->id,
                'post_id' => $request->get('postId'),
            ]);
        } else {
            SavePost::where('user_id', $user->id)->where('post_id', $request->get('postId'))->delete();
        }

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'message' => '',
        ], Helper::SUCCESS_CODE);
    }

    public function savePostList(Request $request)
    {
        $user = $this->user;
        $postIds = SavePost::where('user_id', $user->id)->pluck('post_id')->toArray();
        $posts = [];
        if(!empty($postIds)) {
            $posts = Post::with(['likes', 'tagFriends', 'comments'])->active()->whereIn('id', $postIds)->get()->map(function (Post $post) {
                $tagFriends = $post->tagFriends->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->fullName(),
                        'image' => ($user->avatar) ? Helper::getImage($user->avatar) : Helper::USERIMAGE,
                    ];
                });
                $createdAt = Carbon::parse($post->created_at);
                return [
                    'id' => $post->id,
                    'media' => ($post->media) ? Helper::getImage($post->media) : '',
                    'description' => $post->description ?: '',
                    'link' => $post->link ?: '',
                    'type' => $post->type,
                    'likeCount' => $post->likes->count(),
                    'commentCount' => $post->comments->count(),
                    'savePostFlag' => 1,
                    'createdAt' => $createdAt->ago(),
                    'tagFriends' => $tagFriends,
                ];
            });
        }

        return response()->json([
            'status' => Helper::SUCCESS_CODE,
            'data' => [
                'posts' => $posts
            ]
        ], Helper::SUCCESS_CODE);
    }
}
