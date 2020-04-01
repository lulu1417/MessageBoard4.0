<?php

namespace App\Http\Controllers;

use App\CalculateTime;
use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'comment_id' => ['required', 'exists:comments,id'],
            'content' => ['required', 'max:255'],
        ]);

        $create = Reply::create([
            'user_id' => Auth::user()->id,
            'comment_id' => $request['comment_id'],
            'content' => $request['content'],
        ]);

        return response($create, 200);
    }


    function allReply(Request $request)
    {
        $request->validate([
            'comment_id' => ['required', 'exists:comments,id']
        ]);
        $replies = Reply::with('user')->where('comment_id', $request->comment_id)->get();
        foreach ($replies as $item) {
            $item['last'] = CalculateTime::transfer($item->created_at->toDateTimeString());
        }
        return response($replies);
    }
}
