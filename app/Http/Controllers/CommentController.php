<?php

namespace App\Http\Controllers;

use App\Comment;
use App\CalculateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => ['required', 'exists:posts,id'],
            'content' => ['required', 'max:255'],
        ]);

        $create = Comment::create([
            'user_id' => Auth::user()->id,
            'post_id' => $request['post_id'],
            'content' => $request['content'],
        ]);

        return response($create, 200);

    }

    function allComment(Request $request)
    {
        $request->validate([
            'post_id' => ['required', 'exists:posts,id']
        ]);

        $comments = Comment::with(['user', 'replies'])->where('post_id', $request->post_id)->get();

        foreach ($comments as $item) {
            $item['last'] = CalculateTime::transfer($item->created_at->toDateTimeString());
            foreach ($item->replies as $item) {
                $item['last'] = CalculateTime::transfer($item->created_at->toDateTimeString());
            }
        }

        return response($comments);
    }
}
