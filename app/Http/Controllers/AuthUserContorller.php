<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthUserContorller extends Controller
{

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' =>  ['required', 'string', 'unique:users', 'max:255', 'email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {

            return Response::json($validator->errors());
        }

        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);

        $user = User::query()->firstOrFail();
        $token = JWTAuth::fromUser($user);

        return Response::json([
            'data' => compact('token'),
            'success' => 'user created successfully'
        ]);
    }
}
