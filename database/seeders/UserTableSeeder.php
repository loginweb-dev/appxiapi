<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// Models
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Admin",
            "email" => "admin@admin.com",
            "password" => bcrypt("password"),
            "role_id" => 1,
        ]);
    }
}
