<?php

namespace Tests\Feature\Auth;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
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


    public function test_password_can_be_rest_with_valid_token()
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->postJson(route('password.forget'), ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->postJson(route('password.reset'), [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertOk();

            $this->assertArrayHasKey('status', $response->json());

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
