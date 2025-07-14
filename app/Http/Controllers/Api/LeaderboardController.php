<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\ReferredUser;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class LeaderboardController extends Controller
{

    public function index(){
        // $leaderboards = Leaderboard::all();
        $leaderboards = Leaderboard::orderByDesc(column: "id")
        ->get();

        if($leaderboards->count() > 0){
            return response()->json([
                "data" => $leaderboards
            ],200);
        } else {
            return response()->json([
                "message" => "No Leaderboards Found"
            ], 404);
        }
    }

    public function show($id){
        $originalUsers = Leaderboard::with(["referredUsers" => function($query){
            $query->orderByDesc('wagered_in_leaderboard');
        }])->where("id", $id)->first();

        $leaderboard = Leaderboard::where("id", $id)->first();

        if ($originalUsers) {
            $users = $originalUsers->referredUsers->map(function ($user) {
                return [
                    'avatar' => $user->avatar,
                    'name' => $user->name,
                    'wagered_in_leaderboard' => (float) $user->wagered_in_leaderboard * .01,
                ];
            });

            return response()->json([
                "leaderboard" => $leaderboard,
                'users' => $users,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No leaderboard found'
            ], 404);
        }
    }

    public function guestShow($id){
        $originalUsers = Leaderboard::with(["referredUsers" => function($query){
            $query->orderByDesc('wagered_in_leaderboard');
        }])->where("id", $id)->first();

        $leaderboard = Leaderboard::where("id", $id)->first();

        if ($originalUsers) {
            $users = $originalUsers->referredUsers->map(function ($user) {
                return [
                    'avatar' => $user->avatar,
                    'name' => $user->name,
                    'wagered_in_leaderboard' => (float) $user->wagered_in_leaderboard * .01,
                ];
            });

            return response()->json([
                "leaderboard" => $leaderboard,
                'users' => $users,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No leaderboard found'
            ], 404);
        }
    }

    public function latestLeaderboard(){
        $leaderboards = Leaderboard::all();
        
        $originalUsers = Leaderboard::with(["referredUsers" => function($query){
            $query->orderByDesc('wagered_in_leaderboard');
        }])->latest("id")->where("status", "active")->orWhere("status", "paused")->first();

        $leaderboard = Leaderboard::latest("id")->where("status", "active")->orWhere("status", "paused")->first();

        if($leaderboards->count() > 0){
            if ($originalUsers) {
                $users = $originalUsers->referredUsers->map(function ($user) {
                    return [
                        'avatar' => $user->avatar,
                        'name' => $user->name,
                        'wagered_in_leaderboard' => (float) $user->wagered_in_leaderboard * .01,
                    ];
                });

                return response()->json([
                    "leaderboard" => $leaderboard,
                    'users' => $users,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Active Leaderboard Found'
                ], status: 200);
            }

        } else {
            return response()->json([
                'message' => 'No Leaderboard Found'
            ], 404);
        }

        if ($originalUsers) {
            $users = $originalUsers->referredUsers->map(function ($user) {
                return [
                    'avatar' => $user->avatar,
                    'name' => $user->name,
                    'wagered_in_leaderboard' => (float) $user->wagered_in_leaderboard * .01,
                ];
            });

            return response()->json([
                "leaderboard" => $leaderboard,
                'users' => $users,
            ], 200);
        } else {
            return response()->json([
                'message' => 'No leaderboard found'
            ], 404);
        }
    }

    public function leaderboardHistory()
    {
        $history = Leaderboard::where("has_winner", 1)->where("status", "ended")
            ->orderByDesc("id")
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

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "cookie" => "required",
            "first_prize" => "required",
            "second_prize" => "required",
            "third_prize" => "required",
            "leaderboard_ends_at" => "required|date|after:today",
            // "status" => "required",
            "description" => "required",
        ]);

        if($validator->fails()){
            return response()->json([
                "message" => $validator->errors()
            ], 422);
        } else {
            $leaderboards = Leaderboard::all();

            if($leaderboards->count() === 0){
                $referrals = Http::withHeaders([
                    'Cookie' => "auth_token={$request->cookie}",
                ])
                    ->get('https://csgoempire.com/api/v2/referrals/referred-users?per_page=100&page=1');

                if($referrals->json() === null){
                    return response()->json([
                        "message" => [
                            "cookie" => ["The cookie expired."]
                        ]
                    ], 422);
                } else {
                    $users = $referrals->json()['data'] ?? [];

                    $leaderboard =  Leaderboard::create([
                        "name" => $request->name,
                        "cookie" => $request->cookie,
                        "first_prize" => $request->first_prize,
                        "second_prize" => $request->second_prize,
                        "third_prize" => $request->third_prize,
                        "leaderboard_ends_at" => $request->leaderboard_ends_at,
                        "status" => "active",
                        "description" => $request->description,
                        "cookie_status" => "active",
                    ]);

                    foreach ($users as $user) {
                        ReferredUser::create(
                            [
                                'user_id'                   => $user['user_id'],
                                'avatar'                    => $user['avatar'],
                                'level'                     => $user['level'],
                                'name'                      => $user['name'],
                                'steam_id'                  => $user['steam_id'],
                                'referral_since'            => $user['referral_since'],
                                'last_seen'                 => $user['last_seen'],
                                'total_wagered'             => $user['total_wagered'],
                                'wagered_at_start'          => 0,
                                'wagered_in_leaderboard'    => $user['total_wagered'],
                                'total_commission'          => $user['total_commission'],
                                'commission_percent'        => $user['commission_percent'],
                                'is_depositor'              => $user['is_depositor'],
                                'leaderboard_id'            => $leaderboard->id,
                            ]
                        );
                    }
                        
                    if($leaderboard){
                        $updatedLeaderboards = Leaderboard::orderByDesc("id")
                        ->get();

                        return response()->json([
                            "message" => "Leaderboard Successfully Created",
                            "data" => $updatedLeaderboards,
                        ], 200);
                    }
                }
            } else {
                $activeLeaderboards = Leaderboard::where("status", "active")->get();

                if($activeLeaderboards->count() > 0){
                    return response()->json([
                        "message" => "Please end all active leaderboards before starting a new one."
                    ], 422);
                } else {
                    $referrals = Http::withHeaders([
                        'Cookie' => "auth_token={$request->cookie}",
                    ])
                        ->get('https://csgoempire.com/api/v2/referrals/referred-users?per_page=100&page=1');

                    if($referrals->json() === null){
                        return response()->json([
                            "message" => [
                                "cookie" => ["The cookie expired."]
                            ]
                        ], 422);
                    } else {
                        $users = $referrals->json()['data'] ?? [];

                        $leaderboard =  Leaderboard::create([
                            "name" => $request->name,
                            "cookie" => $request->cookie,
                            "first_prize" => $request->first_prize,
                            "second_prize" => $request->second_prize,
                            "third_prize" => $request->third_prize,
                            "leaderboard_ends_at" => $request->leaderboard_ends_at,
                            "status" => "active",
                            "description" => $request->description,
                            "cookie_status" => "active",
                        ]);

                        foreach ($users as $user) {
                            ReferredUser::create(
                                [
                                    'user_id'                   => $user['user_id'],
                                    'avatar'                    => $user['avatar'],
                                    'level'                     => $user['level'],
                                    'name'                      => $user['name'],
                                    'steam_id'                  => $user['steam_id'],
                                    'referral_since'            => $user['referral_since'],
                                    'last_seen'                 => $user['last_seen'],
                                    'total_wagered'             => $user['total_wagered'],
                                    'wagered_at_start'          => $user['total_wagered'],
                                    'wagered_in_leaderboard'    => $user['total_wagered'] - $user['total_wagered'],
                                    'total_commission'          => $user['total_commission'],
                                    'commission_percent'        => $user['commission_percent'],
                                    'is_depositor'              => $user['is_depositor'],
                                    'leaderboard_id'            => $leaderboard->id,
                                ]
                            );
                        }
                            
                        if($leaderboard){
                            $updatedLeaderboards = Leaderboard::orderByDesc("id")
                            ->get();

                            return response()->json([
                                "message" => "Leaderboard Successfully Created",
                                "data" => $updatedLeaderboards,
                            ], 200);
                        }
                    }

                    // return response()->json([
                    //     "message" => "Create logic for resetting."
                    // ], 422);
                }
            }
        }
    }

    public function updateLeaderboardSettings(Request $request, $id){
        $validator = Validator::make($request->all(), [
            "name" => "required",
            // "cookie" => "required",
            "first_prize" => "required",
            "second_prize" => "required",
            "third_prize" => "required",
            "leaderboard_ends_at" => "required|date|after:today",
            // "status" => "required",
            "description" => "required",
        ]);

        if($validator->fails()){
            return response()->json([
                "message" => $validator->errors()
            ], 422);
        } else {
            // $leaderboards = Leaderboard::all();

            // if($leaderboards->count() === 1){
            //     $leaderboard = Leaderboard::where("id", $id)->first();

            //     $leaderboard->update([
            //         "name" => $request->name,
            //         "first_prize" => $request->first_prize,
            //         "second_prize" => $request->second_prize,
            //         "third_prize" => $request->third_prize,
            //         "leaderboard_ends_at" => $request->leaderboard_ends_at,
            //         "status" => "active",
            //         "description" => $request->description,
            //     ]);
                    
            //     if($leaderboard){
            //         $updatedLeaderboards = Leaderboard::with("topReferredUsers")
            //         ->orderByDesc("created_at")
            //         ->get();

            //         return response()->json([
            //             "message" => "Leaderboard Successfully Updated",
            //             "data" => $updatedLeaderboards,
            //         ], 200);
            //     }
            // } else {
            //     $activeLeaderboards = Leaderboard::where("status", "active")->get();

            //     if($activeLeaderboards->count() > 0){
            //         return response()->json([
            //             "message" => "Please end all active leaderboards before starting a new one."
            //         ], 422);
            //     } else {
            //         return response()->json([
            //             "message" => "Create logic for resetting."
            //         ], 422);
            //     }
            // }

            $leaderboard = Leaderboard::where("id", $id)->first();

            $leaderboard->update([
                "name" => $request->name,
                "first_prize" => $request->first_prize,
                "second_prize" => $request->second_prize,
                "third_prize" => $request->third_prize,
                "leaderboard_ends_at" => $request->leaderboard_ends_at,
                // "status" => "active",
                "description" => $request->description,
            ]);
                
            if($leaderboard){
                $updatedLeaderboards = Leaderboard::orderByDesc("id")
                ->get();

                return response()->json([
                    "message" => "Leaderboard Successfully Updated",
                    "data" => $updatedLeaderboards,
                ], 200);
            }
        }
    }

    public function pauseLeaderboard($id){
        $leaderboard = Leaderboard::where("id", $id)->first();

        if($leaderboard){
            $leaderboard->update([
                "status" => "paused"
            ]);

            $updatedLeaderboard = Leaderboard::where("id", $id)->first();

            return response()->json([
                "message" => "Successfully Updated",
                "leaderboard" => $updatedLeaderboard,
            ], 200);
        } else {
            return response()->json([
                "message" => "Leaderboard not found"
            ], 404);
        }
    }

    public function updateLeaderboardPlayers(Request $request, $id)
    {
        if($request->cookie_still_active == "no"){
            $validator = Validator::make($request->all(), [
                "cookie_still_active" => "required",
                "cookie" => "required",
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                "cookie_still_active" => "required",
            ]);
        }

        if($validator->fails()){
            return response()->json([
                "message" => $validator->errors()
            ], 422);
        } else {
            $token = "";

            if($request->cookie_still_active == "no"){
                $token = $request->cookie;
            } else {
                $leaderboard = Leaderboard::where("id", $id)->first();

                $token = $leaderboard["cookie"];
            }
            
            $referrals = Http::withHeaders([
                'Cookie' => "auth_token={$token}",
            ])
                ->get('https://csgoempire.com/api/v2/referrals/referred-users?per_page=100&page=1');

            if($referrals->json() === null){
                $leaderboard = Leaderboard::where(column: "id", operator: $id)->first();

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

                $leaderboard = Leaderboard::where("id", $id)->first();

                $leaderboard->update([
                    "cookie" => $token,
                    "cookie_status" => "active",
                    "updated_at" => Carbon::now(),
                    "status" => "active",
                ]);

                foreach ($users as $user) {
                    $existing = ReferredUser::where('user_id', $user['user_id'])
                    ->where('leaderboard_id', $leaderboard->id)
                    ->first();

                    $wageredAtStart = $existing->wagered_at_start ?? 0;
                    $wageredInLeaderboard = $user['total_wagered'] - $wageredAtStart;

                    ReferredUser::updateOrCreate([
                        "user_id" => $user['user_id'],
                        "leaderboard_id" => $leaderboard->id,
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
                        'leaderboard_id'     => $leaderboard->id,
                    ]);
                }

                
                $originalUsers = Leaderboard::with(["referredUsers" => function($query){
                    $query->orderByDesc('wagered_in_leaderboard');
                }])->where("id", $id)->first();
                
                $topUsers = $originalUsers->referredUsers->sortByDesc('wagered_in_leaderboard')->take(3)->values();

                $leaderboard = Leaderboard::where("id", $id)->first();

                if ($originalUsers) {
                    $users = $originalUsers->referredUsers->map(function ($user) {
                        return [
                            'avatar' => $user->avatar,
                            'name' => $user->name,
                            'wagered_in_leaderboard' => (float) $user->wagered_in_leaderboard * .01,
                        ];
                    });

                    return response()->json([
                        'message' => 'Users saved to leaderboard successfully',
                        "leaderboard" => $leaderboard,
                        'users' => $users,
                        'top_three' => $topUsers,
                    ], 200);
                }
            }
        }

    }

    public function declareWinner($id){
        $originalUsers = Leaderboard::with("referredUsers")->where("id", $id)->first();

        if ($originalUsers) {
            foreach($originalUsers->referredUsers as $user){
                $user->update([
                    "wagered_at_end" => $user->total_wagered,
                ]);
            }

            $originalUsers->update([
                "has_winner" => true,
                "leaderboard_ends_at" => Carbon::now(),
                "status" => "ended",
            ]);

            $topUsers = $originalUsers->referredUsers->sortByDesc('wagered_in_leaderboard')->take(3)->values();

            $statuses = ['first', 'second', 'third'];

            foreach ($topUsers as $index => $user) {
                $user->update([
                    'status' => $statuses[$index],
                ]);
            }

            return response()->json([
                "message" => "Successfully Declared"
            ], 200);
        } else {
            return response()->json([
                "message" => "404"
            ], 404);
        }
    }

}
