<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CalculateTime;

class PostController extends Controller
{
    function index()
    {
        $posts = Post::with(['user', 'comments', 'likes'])
            ->withCount(['comments', 'likes'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($posts as $item){
            $item['last'] = CalculateTime::transfer($item->created_at->toDateTimeString());
            foreach ($item->comments as $item){
                $item['last'] = CalculateTime::transfer($item->created_at->toDateTimeString());
                foreach ($item->replies as $item){
                    $item['last'] = CalculateTime::transfer($item->created_at->toDateTimeString());
                }
            }
        }

        return response($posts);
    }

    function store(Request $request)
    {

        $request->validate([
            'content' => ['required', 'max:225'],
        ]);
        $create = Post::create([
            'user_id' => Auth::user()->id,
            'content' => $request['content'],
        ]);

        return response($create, 200);

    }

    function allPost()
    {
        return response(Post::with(['likes', 'user'])->withCount(['likes', 'comments'])->get());
    }
}
