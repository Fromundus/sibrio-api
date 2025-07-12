<?php

namespace App\Console\Commands;

use App\Models\Leaderboard;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckLeaderboards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-leaderboards';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check leaderboards and declare winners if time ended';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $leaderboards = Leaderboard::with('referredUsers')
            ->where('leaderboard_ends_at', '<=', $now)
            ->where('status', '!=', 'ended')
            ->get();

        foreach ($leaderboards as $leaderboard) {
            $this->declareWinner($leaderboard);
        }

        return 0;
    }

    private function declareWinner($leaderboard)
    {
        foreach($leaderboard->referredUsers as $user){
            $user->update([
                "wagered_at_end" => $user->total_wagered,
            ]);
        }

        $leaderboard->update([
            "has_winner" => true,
            "leaderboard_ends_at" => Carbon::now(),
            "status" => "ended",
        ]);

        $topUsers = $leaderboard->referredUsers
            ->sortByDesc('wagered_in_leaderboard')
            ->take(3)
            ->values();

        $statuses = ['first', 'second', 'third'];

        foreach ($topUsers as $index => $user) {
            $user->update([
                'status' => $statuses[$index],
            ]);
        }

        $this->info("Leaderboard {$leaderboard->id} ended and winners declared.");
    }
}
