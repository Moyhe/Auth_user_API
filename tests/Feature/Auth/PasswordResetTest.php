<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_making_a_forgot_password_api_request(): void
    {

        Notification::fake();

        $user = User::factory()->create();

        $response = $this->postJson(route('password.forget'), ['email' => $user->email]);

        $response->assertOk();

        Notification::assertSentTo($user, ResetPassword::class);
    }
}
