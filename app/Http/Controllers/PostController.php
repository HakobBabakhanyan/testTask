<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::query()->with(['comments' => function ($q) {
            $q->with(['user', 'children' => function ($q) {
                $q->with('user');
            }])->whereNull('parent_id');
        }])->get();

        return response()->json([
            'posts' => PostResource::make($posts)
        ]);
    }
}
