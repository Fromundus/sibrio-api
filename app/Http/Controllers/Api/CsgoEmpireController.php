<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Leaderboard;
use App\Models\ReferredUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CsgoEmpireController extends Controller
{
    public function referrals(Request $request)
    {   
        $token = "";
        
        $referrals = Http::withHeaders([
            'Cookie' => "auth_token={$token}",
        ])
            ->get('https://csgoempire.com/api/v2/referrals/referred-users?per_page=100&page=1');

        return response()->json([
            "message" => "success",
            // 'user' => $auth->json()['user'] ?? null,
            // 'socket_token' => $auth->json()['socket_token'] ?? null,
            // 'socket_signature' => $auth->json()['socket_signature'] ?? null,
            "referrals" => $referrals->json(),
        ]);
    }
}
