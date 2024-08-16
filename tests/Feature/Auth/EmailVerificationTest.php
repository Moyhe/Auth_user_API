<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\Feature\Traits\JwtAuthTrait;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase, JwtAuthTrait;

    public function test_user_can_verify_email(): void
    {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->jwtAs($user)->getJson($verificationUrl);

        $response->assertOk();

        Event::assertDispatched(Verified::class);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }



    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $user = User::factory()->unverified()->create();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->jwtAs($user)->getJson($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
