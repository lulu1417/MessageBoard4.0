<?php

namespace App\Http\Controllers;

use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
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
            'post_id' => ['required', 'exists:posts,id'],
        ]);

        if(Like::where('user_id', Auth::user()->id)->where('post_id', $request['post_id'])->get()->count() > 0){
            return response('already_liked');

        }
        $create = Like::create([
            'user_id' => Auth::user()->id,
            'post_id' => $request['post_id'],
        ]);

        return response($create, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
           'like_id' => ['required', 'exists:likes,id']
        ]);
            $like = Like::find($request->like_id);
            $delete = $like->delete();
            $result['status'] = $delete;
            return response($result, 200);

    }
    function allLike(Request $request){
        $request->validate([
           'post_id' => ['required', 'exists:posts,id']
        ]);
        return response(Like::with('user')->where('post_id', $request->post_id)->get());
    }
}
