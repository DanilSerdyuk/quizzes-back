<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * @param array $payload
     *
     * @return User
     */
    public function registration(array $payload): User
    {
        $this->hashPassword($payload['password']);

        //here fire event registration
        return (new UserService())->create($payload);
    }

    /**
     * @param string $password
     *
     * @return void
     */
    private function hashPassword(string &$password): void
    {
        $password = Hash::make($password);
    }
}
