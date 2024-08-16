<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthUserContorller extends Controller
{


    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' =>  ['required', 'string', 'unique:users', 'max:255', 'email'],
            'password' => ['required', Password::defaults()->min(8), 'confirmed']
        ]);

        if ($validator->fails()) {

            return Response::json($validator->errors(), 400);
        }

        User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
        ]);


        $user = User::query()->firstOrFail();
        $token = JWTAuth::fromUser($user);


        return Response::json(compact('user', 'token'), 200);
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' =>  ['required', 'string', 'max:255', 'email'],
            'password' => ['required', Password::defaults()->min(8)]

        ]);

        if ($validator->fails()) {

            return Response::json($validator->errors(), 400);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {

                return Response::json(['error' => 'invalid user name and password'], [401]);
            }
        } catch (JWTException $e) {

            return Response::json(['error' => 'could not create token'], [500]);
        }

        return response()->json(compact('token'), 200);
    }



    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json(['message' => 'Successfully logged out']);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Failed to logout, token invalid'], 500);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to logout'], 500);
        }
    }
}
