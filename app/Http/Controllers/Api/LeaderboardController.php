<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\ReferredUser;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LeaderboardController extends Controller
{
    public function index(){
        $originalUsers = Leaderboard::with(["referredUsers" => function($query){
            $query->orderByDesc('total_wagered');
        }])->latest()->first();
        
        $leaderboard = Leaderboard::latest()->first();

        if ($originalUsers) {
            // $users = $originalUsers->referredUsers->map(function ($user) {
            //     $name = $user->name;
            //     $maskedName = strlen($name) <= 2
            //         ? $name  // if name is too short, return as-is
            //         : substr($name, 0, 1) . str_repeat('*', strlen($name) - 2) . substr($name, -1);

            //     return [
            //         'avatar' => $user->avatar,
            //         'name' => $maskedName,
            //         'total_wagered' => (float) $user->total_wagered,
            //     ];
            // });

            $users = $originalUsers->referredUsers;
            
            return response()->json([
                "leaderboard" => $leaderboard,
                'data' => $users,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No leaderboard found'
            ], 404);
        }
    }

    // public function leaderboardHistory(){
    //     $history = Leaderboard::where("has_winner", 1)->with("referredUsers")->get();

    //     if($history){
    //         return response()->json([
    //             "data" => $history,
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             "message" => "404",
    //         ], 404);
    //     }
    // }

    public function leaderboardHistory()
    {
        $history = Leaderboard::where("has_winner", 1)
            ->with(['referredUsers' => function ($query) {
                $query->where('status', 'winner');
            }])
            ->get();

        if ($history->isNotEmpty()) {
            return response()->json([
                "data" => $history,
            ], 200);
        } else {
            return response()->json([
                "message" => "No leaderboard history found",
            ], 404);
        }
    }



    public function updateLeaderboard(Request $request)
    {
        if($request->has_cookie == "yes"){
            $validator = Validator::make($request->all(), [
                "has_cookie" => "required",
                "cookie" => "required",
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                "has_cookie" => "required",
            ]);
        }

        if($validator->fails()){
            return response()->json([
                "message" => $validator->errors()
            ], 422);
        } else {
            $token = "";

            if($request->has_cookie == "yes"){
                $token = $request->cookie;
            } else {
                $leaderboard = Leaderboard::latest()->first();

                $token = $leaderboard["cookie"];
            }
            
            $referrals = Http::withHeaders([
                'Cookie' => "auth_token={$token}",
            ])
                ->get('https://csgoempire.com/api/v2/referrals/referred-users?per_page=100&page=1');

            if($referrals->json() === null){
                $leaderboard = Leaderboard::latest()->first();

                if($leaderboard){
                    $leaderboard->update([
                        "cookie_status" => "expired"
                    ]);
    
                    return response()->json([
                        "message" => "Cookie Expired",
                        "leaderboard" => $leaderboard,
                    ], 200);
                } else {
                    return response()->json([
                        "message" => "Invalid Cookie"
                    ], 200);
                }

            } else {
                $users = $referrals->json()['data'] ?? [];

                $leaderboard = Leaderboard::create([
                    "cookie" => $token,
                    "cookie_status" => "active",
                ]);

                foreach ($users as $user) {
                    ReferredUser::create(
                        [
                            'user_id'            => $user['user_id'],
                            'avatar'             => $user['avatar'],
                            'level'              => $user['level'],
                            'name'               => $user['name'],
                            'steam_id'           => $user['steam_id'],
                            'referral_since'     => $user['referral_since'],
                            'last_seen'          => $user['last_seen'],
                            'total_wagered'      => $user['total_wagered'],
                            'total_commission'   => $user['total_commission'],
                            'commission_percent' => $user['commission_percent'],
                            'is_depositor'       => $user['is_depositor'],
                            'leaderboard_id'     => $leaderboard->id,
                        ]
                    );
                }

                $leaderboard = Leaderboard::with("referredUsers")->latest()->first();

                return response()->json([
                    'message' => 'Users saved to leaderboard successfully',
                    'leaderboard' => $leaderboard,
                    'count'   => count($users),
                ]);
            }
        }

    }

    public function declareWinner(){
        $originalUsers = Leaderboard::with("referredUsers")->latest()->first();
        $prize = Setting::latest()->first();

        if ($originalUsers) {
            $originalUsers->update([
                "has_winner" => true,
                "prize" => $prize["first_prize"],
            ]);

            $topUser = $originalUsers->referredUsers->sortByDesc('total_wagered')->first();

            if ($topUser) {
                $topUser->update([
                    'status' => 'winner',
                ]);

                return response()->json([
                    "message" => "Successfully Declared"
                ], 200);
            }
        } else {
            return response()->json([
                "message" => "404"
            ], 404);
        }
    }
}
