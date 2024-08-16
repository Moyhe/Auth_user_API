<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_making_a_retister_api_request(): void
    {
        $userRegister = [

            'name' => 'mohye',
            'email' => 'geni@gmail.com',
            'password' => "123456789",
            'password_confirmation' => "123456789"

        ];

        $response = $this->postJson(route('api.register'), $userRegister);

        $response->assertStatus(200);

        $this->assertArrayHasKey('user', $response->json());
    }
}
