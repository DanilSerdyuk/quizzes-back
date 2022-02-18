<?php

namespace Database\Seeders;

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
        $this->createAdmin();
        $this->createUsers();
    }

    private function createAdmin(): void
    {
        $email = 'admin@admin.com';

        if (User::query()->where(['email' => $email])->first()) {
            return;
        }

        User::factory()->create([
            'name' => 'Admin Admin',
            'email' => $email,
            'password' => Hash::make('secret')
        ]);
    }

    private function createUsers(): void
    {
        User::factory(9)->create();
    }
}
