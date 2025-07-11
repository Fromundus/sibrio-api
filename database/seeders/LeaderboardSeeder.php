<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create one leaderboard
        $leaderboardId = DB::table('leaderboards')->insertGetId([
            'cookie' => Str::random(32),
            'cookie_status' => 'active',
            'has_winner' => false,
            'created_at' => now(),
            'updated_at' => now(),
            "name" =>  "Leaderboard",
            "first_prize" => 100.00,
            "second_prize" => 50.00,
            "third_prize" => 20.00,
            'leaderboard_ends_at' => Carbon::now()->addDays(7),
            'description' => 'Top players for July rewards',
            'status' => "active",
        ]);

        // Create 10 referred users for that leaderboard
        for ($i = 1; $i <= 10; $i++) {
            DB::table('referred_users')->insert([
                'leaderboard_id' => $leaderboardId,
                'user_id' => Str::uuid(),
                'name' => 'User ' . $i,
                'avatar' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
                'level' => rand(1, 100),
                'user_badges' => json_encode(['badge1', 'badge2']),
                'steam_id' => 'STEAM_' . rand(1000000, 9999999),
                'referral_since' => Carbon::now()->subDays(rand(10, 100))->timestamp,
                'last_seen' => Carbon::now()->subDays(rand(0, 10))->timestamp,
                'total_wagered' => rand(1000, 10000),
                'total_commission' => rand(10, 1000) / 100,
                'commission_percent' => rand(10, 500) / 1000,
                'is_depositor' => rand(0, 1),
                // 'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
