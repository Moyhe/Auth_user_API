<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\Traits\JwtAuthTrait;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase, JwtAuthTrait;

    public function test_making_a_login_api_request(): void
    {

        User::factory()->create([
            'email' => 'mohye@gmail.com',
        ]);

        $userRegister = [
            'email' => 'mohye@gmail.com',
            'password' => "password"
        ];

        $response = $this->postJson(route('api.login'), $userRegister);

        $response->assertStatus(200);

        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_user_to_logout()
    {
        $user = User::factory()->create();

        $response = $this->jwtAs($user)->postJson(route('logout'));

        $response->assertOk();

        $this->assertArrayHasKey('message', $response->json());
    }
}
