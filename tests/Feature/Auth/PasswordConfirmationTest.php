<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Feature\Traits\JwtAuthTrait;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase, JwtAuthTrait;
    /**
     * A basic feature test example.
     */
    public function test_user_to_confirm_his_password(): void
    {
        $user = User::factory()->create();

        $response = $this->jwtAs($user)->postJson(route('password.confirm'), ['password' => 'password']);

        $response->assertOk();

        $response->assertSessionHasNoErrors();
    }
}
