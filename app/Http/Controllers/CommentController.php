<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function create(CommentRequest $request)
    {
        Comment::query()->create(array_merge($request->validated(), ['user_id'=>$request->user()->id]));

        return response()->json([
            'posts' => Post::query()->with(['comments' => function($q){
                $q->with(['user', 'children'=> function($q){
                    $q->with('user');
                }])->whereNull('parent_id');
            }])->get()
        ]);
    }
}
