<?php

namespace Database\Seeders;

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
        // \App\Models\User::factory(10)->create();
        //ADMIN

        \App\Models\User::factory()->create([
            'username' => "admin",
            'password' => Hash::make("1234"),
            'role' => "admin",
            'status' => "active"
        ]);
    }

}