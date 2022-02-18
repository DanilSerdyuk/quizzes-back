<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->createRoles();
        $this->createAdmin();
        $this->createUsers();
    }

    private function createRoles(): void
    {
        foreach (RoleEnum::getAllValues() as $name) {
            Role::create(['name' => $name]);
        }
    }

    private function createAdmin(): void
    {
        $email = 'admin@admin.com';

        if (User::query()->where(['email' => $email])->first()) {
            return;
        }

        $user = User::factory()->create([
            'name' => 'Admin Admin',
            'email' => $email,
            'password' => Hash::make('secret')
        ]);

        $user->assignRole(RoleEnum::ADMIN);
    }

    private function createUsers(): void
    {
        $users = User::factory(9)->create();

        $users->each(function (User $user) {
            $user->assignRole(RoleEnum::STUDENT);
        });
    }
}
