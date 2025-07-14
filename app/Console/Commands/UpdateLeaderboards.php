<?php

namespace App\Console\Commands;

use App\Models\Leaderboard;
use App\Models\ReferredUser;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateLeaderboards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-leaderboards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update leaderboards.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $currentActiveLeaderboard = Leaderboard::latest('id')->where("status", "active")->first();

        if($currentActiveLeaderboard->cookie_status !== "expired"){
            $token = $currentActiveLeaderboard->cookie;

            $id = $currentActiveLeaderboard->id;

            $referrals = Http::withHeaders([
                'Cookie' => "auth_token={$token}",
            ])
                ->get('https://csgoempire.com/api/v2/referrals/referred-users?per_page=100&page=1');
    
            if($referrals->json() === null){
    
                $currentActiveLeaderboard->update([
                    "cookie_status" => "expired"
                ]);
    
            } else {
                $users = $referrals->json()['data'] ?? [];
    
                $currentActiveLeaderboard->update(attributes: [
                    "cookie_status" => "active",
                    "updated_at" => Carbon::now(),
                    "status" => "active",
                ]);
    
                foreach ($users as $user) {
                    $existing = ReferredUser::where('user_id', $user['user_id'])
                    ->where('leaderboard_id', $id)
                    ->first();
    
                    $wageredAtStart = $existing->wagered_at_start ?? 0;
                    $wageredInLeaderboard = $user['total_wagered'] - $wageredAtStart;
    
                    ReferredUser::updateOrCreate([
                        "user_id" => $user['user_id'],
                        "leaderboard_id" => $id,
                    ], 
                    [
                        'avatar'             => $user['avatar'],
                        'level'              => $user['level'],
                        'name'               => $user['name'],
                        'steam_id'           => $user['steam_id'],
                        'referral_since'     => $user['referral_since'],
                        'last_seen'          => $user['last_seen'],
                        'total_wagered'      => $user['total_wagered'],
                        'wagered_in_leaderboard'      => $wageredInLeaderboard,
                        'total_commission'   => $user['total_commission'],
                        'commission_percent' => $user['commission_percent'],
                        'is_depositor'       => $user['is_depositor'],
                        'leaderboard_id'     => $id,
                    ]);
                }
            }
        }
    }
}
