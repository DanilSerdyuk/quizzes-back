<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserService
{
    //TODO needed dto, cuz service can call any place
    /**
     * @param array $payload
     *
     * @return Model|User
     */
    public function create(array $payload): User
    {
        return User::query()->create($payload);
    }
}
