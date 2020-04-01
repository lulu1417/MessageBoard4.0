<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{

    function store(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => ['required', 'unique:users'],
            'password' => ['required', 'between:4,20'],
        ]);

        if ($validator->fails()) {
            $status['message'] = 'invalid input';
            return response($status, 400);
        }

        $user = User::create([
            'name' => $request['name'],
            'password' => hash('sha256', $request['password']),
            'api_token' => Str::random(20),

        ]);
        return response($user->makeVisible(['api_token', 'created_at']), 200);

    }

    function login(Request $request)
    {
        $validator = validator::make($request->all(), [
            'name' => ['required'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            $status['message'] = 'invalid input';
            return response($status, 400);
        }

        $user = User::where('name', $request->name)->first();
        if (!$user) {
            $status['message'] = 'name not found';
            return response($status, 400);
        } elseif ($user->password !== hash('sha256', $request['password'])) {
            $status['message'] = 'wrong password';
            return response($status, 400);
        }
        $user->update([
            'api_token' => Str::random(20),
        ]);
        return response($user->makeVisible(['api_token', 'created_at']), 200);

    }

}
