<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;



class AuthUserContorller extends Controller
{

    /**
     * @OA\Schema(
     *    schema="RegisterRequest",
     *    @OA\Property(
     *        property="name",
     *        type="string",
     *        description="User Name",
     *        nullable=false,
     *
     *    ),
     *    @OA\Property(
     *        property="email",
     *        type="string",
     *        description="User EMail",
     *        nullable=false,
     *        format="email"
     *    ),
     *
     *  *    @OA\Property(
     *        property="password",
     *        type="string",
     *        description="User Password",
     *        nullable=false,
     *        example="password"
     *    ),
     * )
     *
     * @OA\Post(
     *     path="/api/user/register",
     *     tags={"users"},
     *     summary="Authorize user",
     *     description="register user",
     *     operationId="register",
     *     @OA\RequestBody(
     *        @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authentication successful",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                property="token",
     *                type="string",
     *                description="JWT authorization token",
     *                example="1|fSPJ2AR0TU0dLB6aiYgtSGHkPnFTfBdh4ltISiSo",
     *             ),
     *             @OA\Property(
     *                property="type",
     *                type="string",
     *                description="Token type",
     *                example="bearer",
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' =>  ['required', 'string', 'unique:users', 'max:255', 'email'],
            'password' => ['required']
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


        return Response::json(compact('token'), 200);
    }


    /**
     * @OA\Schema(
     *    schema="LoginRequest",
     *    @OA\Property(
     *        property="email",
     *        type="string",
     *        description="User EMail",
     *        nullable=false,
     *        format="email"
     *    ),
     *    @OA\Property(
     *        property="password",
     *        type="string",
     *        description="User Password",
     *        nullable=false,
     *        example="password"
     *    ),
     * )
     *
     * @OA\Post(
     *     path="/api/user/login",
     *     tags={"users"},
     *     summary="Authorize user",
     *     description="Authorizes user by its email and password",
     *     operationId="login",
     *     @OA\RequestBody(
     *        @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Authentication successful",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                property="token",
     *                type="string",
     *                description="Sanctum authorization token",
     *                example="1|fSPJ2AR0TU0dLB6aiYgtSGHkPnFTfBdh4ltISiSo",
     *             ),
     *             @OA\Property(
     *                property="type",
     *                type="string",
     *                description="Token type",
     *                example="bearer",
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'email' =>  ['required', 'string', 'max:255', 'email'],
            'password' => 'required'

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

        $user_id = auth()->user()->id;

        return response()->json(compact('token', 'user_id'), 200);
    }



    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response('', 204);
    }
}
