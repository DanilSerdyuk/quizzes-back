<?php

namespace App\Services;

use App\Enums\RoleEnum;
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
        /** @var User $user */
        $user = User::query()->create($payload);

        $this->assigneeRoles($user, $payload);

        return $user;
    }

    /**
     * @param User  $user
     * @param array $payload
     */
    private function assigneeRoles(User $user, array $payload): void
    {
        if (!array_key_exists('roles', $payload)) {
            //by default
            $user->assignRole(RoleEnum::STUDENT);
        } else {
            $user->assignRole($payload['roles']);
        }
    }
}
