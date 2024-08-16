<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_making_a_login_api_request(): void
    {

        User::create([
            'name' => 'mohye',
            'email' => 'mohye@gmail.com',
            'password' => Hash::make('12345678'),
            'password_confirmation' => Hash::make('12345678')
        ]);

        $userRegister = [
            'email' => 'mohye@gmail.com',
            'password' => "12345678"
        ];

        $response = $this->postJson(route('api.login'), $userRegister);

        $response->assertStatus(200);

        $this->assertArrayHasKey('token', $response->json());
    }
}
