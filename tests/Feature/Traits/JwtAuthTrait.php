<?php

namespace Tests\Feature\Traits;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

trait JwtAuthTrait
{
    protected function jwtAs(User $user)
    {
        $token = JWTAuth::fromUser($user);

        $this->withHeaders(['Authorization' => 'Bearer ' . $token]);

        return $this;
    }
}
