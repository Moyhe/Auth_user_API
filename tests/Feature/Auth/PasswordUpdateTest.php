<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_update_his_password(): void
    {
        $user = User::factory()->create();

        $newPassword = [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ];

        $resposne = $this->jwtAs($user)->putJson(route('password.update'), $newPassword);

        $resposne->assertOk();

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }


    protected function jwtAs(User $user)
    {
        $token = JWTAuth::fromUser($user);

        $this->withHeaders(['Authorization' => 'Bearer ' . $token]);

        return $this;
    }
}
