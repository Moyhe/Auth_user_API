<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


class EmailVerificationController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'your email was verified',
                'redirect_url' => env('FRONT_URL') . '/dashbaord'
            ]);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'message' => 'your email was verified',
            'redirect_url' => env('FRONT_URL') . '/dashbaord'
        ]);
    }
}
