<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::latest("id")->first();

        if($settings){
            return response()->json([
                'settings' => $settings
            ], 200);
        } else {
            return response()->json([
                'message' => "The settings is currently on setup."
            ], 404);
        }

    }

    public function settingsWithLeaderboards(){
        $settings = Setting::latest("id")->first();
        $leaderboard = Leaderboard::latest("id")->first();

        return response()->json([
            'settings' => $settings,
            'leaderboard' => $leaderboard,
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referral_code' => "required",
            'referral_link' => "required",
            // 'first_prize' => "required",
            // 'second_prize' => "required",
            // 'third_prize' => "required",
            // 'terms' => "required",
            // 'is_active' => "required",
            // 'leaderboard_ends_at' => "required",
        ]);

        if($validator->fails()){
            return response()->json([
                "message" => $validator->errors()
            ], 422);
        } else {
            $settings = Setting::firstOrCreate([]);
        
            $settings->update($request->all([
                'referral_code',
                'referral_link',
                // 'first_prize',
                // 'second_prize',
                // 'third_prize',
                // 'terms',
                // 'is_active',
                // 'leaderboard_ends_at',
            ]));

            $newSettings = Setting::latest("id")->first();

            return response()->json([
                'message' => 'Settings updated successfully',
                "settings" => $newSettings
            ], 200);
        }
    }
}
