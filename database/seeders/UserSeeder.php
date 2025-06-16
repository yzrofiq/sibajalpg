<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user   = [
            'username' => 'admin',
            'name' => 'Admin',
            'password' => '$2y$10$G49WG5OjNbIv56.4z10pOuOZMX6oHguX0DZ5L4xD2OnD2GwQ935sC', // passwordVMS
            'role_id' => 1,
        ];

        User::firstOrCreate($user);
    }
}
