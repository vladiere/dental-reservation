<?php

namespace Database\Seeders;

use App\Models\Details;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $details = Details::create([
            "first_name" => "admin",
            "last_name" => "soy",
        ]);
        User::create([
            "details_id" => $details->id,
            "email" => "admin@soy.com",
            "password" => Hash::make("admin@soy"),
            "user_role" => 0,
            "user_status" => "admin",
        ]);
    }
}
