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
    // public function run(): void
    // {
    //     // Create one leaderboard
    //     $leaderboardId = DB::table('leaderboards')->insertGetId([
    //         'cookie' => Str::random(32),
    //         'cookie_status' => 'active',
    //         'has_winner' => false,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //         "name" =>  "Leaderboard",
    //         "first_prize" => 100.00,
    //         "second_prize" => 50.00,
    //         "third_prize" => 20.00,
    //         'leaderboard_ends_at' => Carbon::now()->addDays(7),
    //         'description' => 'Top players for July rewards',
    //         'status' => "active",
    //     ]);

    //     // Create 10 referred users for that leaderboard
    //     for ($i = 1; $i <= 10; $i++) {
    //         DB::table('referred_users')->insert([
    //             'leaderboard_id' => $leaderboardId,
    //             'user_id' => Str::uuid(),
    //             'name' => 'User ' . $i,
    //             'avatar' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png',
    //             'level' => rand(1, 100),
    //             'user_badges' => json_encode(['badge1', 'badge2']),
    //             'steam_id' => 'STEAM_' . rand(1000000, 9999999),
    //             'referral_since' => Carbon::now()->subDays(rand(10, 100))->timestamp,
    //             'last_seen' => Carbon::now()->subDays(rand(0, 10))->timestamp,
    //             'total_wagered' => rand(1000, 10000),
    //             'total_commission' => rand(10, 1000) / 100,
    //             'commission_percent' => rand(10, 500) / 1000,
    //             'is_depositor' => rand(0, 1),
    //             // 'status' => 'active',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);
    //     }
    // }

    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $leaderboard = \DB::table('leaderboards')->insertGetId([
                'name' => "Leaderboard #{$i}",
                'cookie' => Str::random(32),
                'cookie_status' => 'active',
                'has_winner' => false,
                'first_prize' => rand(100, 500),
                'second_prize' => rand(50, 200),
                'third_prize' => rand(10, 100),
                'leaderboard_ends_at' => now()->addDays(rand(7, 30)),
                'status' => 'active',
                'description' => "This is a test leaderboard number {$i}",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            for ($j = 1; $j <= 10; $j++) {
                $wageredStart = rand(1000, 5000);
                $currentWagered = $wageredStart + rand(0, 5000);
                $wageredInLeaderboard = $currentWagered - $wageredStart;

                \DB::table('referred_users')->insert([
                    'leaderboard_id' => $leaderboard,
                    'user_id' => Str::uuid(),
                    'name' => "User{$i}_{$j}",
                    'avatar' => "https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_960_720.png",
                    'level' => rand(1, 100),
                    'user_badges' => json_encode(['badge1', 'badge2']),
                    'steam_id' => 'STEAM_' . rand(100000, 999999),
                    'referral_since' => now()->subDays(rand(10, 100))->timestamp,
                    'last_seen' => now()->subMinutes(rand(10, 1440))->timestamp,
                    'wagered_at_start' => $wageredStart,
                    'wagered_at_end' => null,
                    'wagered_in_leaderboard' => $wageredInLeaderboard,
                    'total_wagered' => $currentWagered,
                    'total_commission' => $currentWagered * 0.05,
                    'commission_percent' => 0.050,
                    'is_depositor' => rand(0, 1),
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
