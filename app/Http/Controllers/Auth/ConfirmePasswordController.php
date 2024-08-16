<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ConfirmePasswordController extends Controller
{
    public function __invoke(Request $request)
    {

        $request->validate([
            'password' => ['required', Password::defaults()->min(8)]
        ]);

        if (! Hash::check($request->password, Auth::user()->password)) {
            return response()->json(['message' => 'your password is wrong please try again...'], 400);
        }


        return response()->json([
            'auth.password_confirmed_at' => time(),
            'message' => 'your password was confirmted. thanks for waiting...'
        ], 200);
    }
}
