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
        // $leaderboards = Leaderboard::all();
        $leaderboards = Leaderboard::with(["referredUsers" => function($query){
            $query->orderByDesc("total_wagered")->take(3);
        }])
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
            $query->orderByDesc('total_wagered');
        }])->where("id", $id)->first();

        $leaderboard = Leaderboard::where("id", $id)->first();

        if ($originalUsers) {
            $users = $originalUsers->referredUsers->map(function ($user) {
                return [
                    'avatar' => $user->avatar,
                    'name' => $user->name,
                    'total_wagered' => (float) $user->total_wagered * .01,
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
        $originalUsers = Leaderboard::with(["referredUsers" => function($query){
            $query->orderByDesc('total_wagered');
        }])->latest("id")->first();

        $leaderboard = Leaderboard::latest("id")->first();

        if ($originalUsers) {
            $users = $originalUsers->referredUsers->map(function ($user) {
                return [
                    'avatar' => $user->avatar,
                    'name' => $user->name,
                    'total_wagered' => (float) $user->total_wagered * .01,
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

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "cookie" => "required",
            "first_prize" => "required",
            "second_prize" => "required",
            "third_prize" => "required",
            "leaderboard_ends_at" => "required",
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
                            "cookie" => ["The cookie field has a custom error."]
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

                    // $leaderboard = Leaderboard::with(["referredUsers" => function($query){
                    //     $query->orderByDesc('total_wagered');
                    // }])->latest(column: "id")->first();

                    // $topUsers = $leaderboard->referredUsers->sortByDesc('total_wagered')->take(3)->values();

                    // return response()->json([
                        //     'message' => 'Users saved to leaderboard successfully',
                        //     'leaderboard' => $leaderboard,
                        //     'top_three' => $topUsers,
                        //     'count'   => count($users),
                        // ]);
                        
                    if($leaderboard){
                        $updatedLeaderboards = Leaderboard::all();

                        return response()->json([
                            "message" => "Leaderboard Successfully Created",
                            "data" => $updatedLeaderboards,
                        ], 200);
                    }
                }
            } else {
                return response()->json([
                    "message" => "create logic for resetting."
                ]);
            }
        }
    }

    public function leaderboardHistory()
    {
        $history = Leaderboard::where("has_winner", 1)
            ->with(['referredUsers' => function ($query) {
                $query->where('status', 'first');
            }])
            ->orderByDesc("created_at")
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

                $leaderboard = Leaderboard::with(["referredUsers" => function($query){
                    $query->orderByDesc('total_wagered');
                }])->latest("id")->first();

                $topUsers = $leaderboard->referredUsers->sortByDesc('total_wagered')->take(3)->values();

                return response()->json([
                    'message' => 'Users saved to leaderboard successfully',
                    'leaderboard' => $leaderboard,
                    'top_three' => $topUsers,
                    'count'   => count($users),
                ]);
            }
        }

    }

    public function declareWinner(){
        $originalUsers = Leaderboard::with("referredUsers")->latest("id")->first();
        $prize = Setting::latest()->first();

        if ($originalUsers) {
            $originalUsers->update([
                "has_winner" => true,
                "prize" => $prize["first_prize"],
            ]);

            $topUsers = $originalUsers->referredUsers->sortByDesc('total_wagered')->take(3)->values();

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
