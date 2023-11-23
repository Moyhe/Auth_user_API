<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
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
            'password' => 123456789

        ];

        $response = $this->postJson(route('api.register'), $userRegister);

        $response
            ->assertStatus(200);


        $this->assertArrayHasKey('token', $response->json());
    }

    public function test_making_a_login_api_request(): void
    {

        User::create([
            'name' => 'mohye',
            'email' => 'mohye@gmail.com',
            'password' => Hash::make('1234567')
        ]);

        $userRegister = [
            'email' => 'mohye@gmail.com',
            'password' => 1234567
        ];

        $response = $this->postJson(route('api.login'), $userRegister);

        $response
            ->assertStatus(200);

        $this->assertArrayHasKey('token', $response->json());
    }
}
