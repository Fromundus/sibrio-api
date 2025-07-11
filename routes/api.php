<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartItemController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\CsgoEmpireController;
use App\Http\Controllers\Api\LeaderboardController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductVariantController;
use App\Http\Controllers\Api\RateController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\UserController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//PROTECTED API
Route::middleware('auth:sanctum')->group(function (){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    // Route::get('/users', [UserController::class, 'index']);
    Route::put('/updateuser/{id}', [UserController::class, 'update']);
    Route::put('/changepassword/{id}', [UserController::class, 'changePassword']);
    
    Route::post('/updatesettings', [SettingController::class, 'update']);

    Route::get('/leaderboards', [LeaderboardController::class, 'index']);
    Route::get('/leaderboards/{id}', [LeaderboardController::class, 'show']);
    Route::post('/createleaderboard', [LeaderboardController::class, 'store']);
    Route::put('/updateleaderboardsettings/{id}', [LeaderboardController::class, 'updateLeaderboardSettings']);

    Route::put('/updateleaderboardplayers/{id}', [LeaderboardController::class, 'updateLeaderboardPlayers']);
    Route::put('/declarewinner', [LeaderboardController::class, 'declareWinner']);
    Route::get('/settings-with-leaderboard', action: [SettingController::class, 'settingsWithLeaderboards']);
    
    // Route::get('/admin/{user_id}', [UserController::class, 'admin']);
    // Route::get('/superadmin/{user_id}', [UserController::class, 'superAdmin']);
    // Route::get('/rider/{user_id}', [UserController::class, 'rider']);
    // Route::get('/useraccounts', [UserController::class, 'useraccounts']);
    // Route::put('/userupdatestatus/{id}/{status}', [UserController::class, 'updateStatus']);
    // Route::delete('deleteuser/{id}', [UserController::class, 'delete']);
    // Route::put('/updateroleandstatus/{id}', [UserController::class, 'updateRoleAndStatus']);
});


// Route::get('/users/{id}', [UserController::class, 'show']);

//PUBLIC API

Route::get('/referrals', [CsgoEmpireController::class, 'referrals']);

Route::get('/settings', [SettingController::class, 'index']);

// Route::get('/leaderboard', [LeaderboardController::class, 'activeLeaderboard']);
Route::get('/latestleaderboard', [LeaderboardController::class, 'latestLeaderboard']);

Route::get('/leaderboardhistory', [LeaderboardController::class, 'leaderboardHistory']);

//REGISTER LOGIN
Route::post('/register', [UserController::class, 'store']);
// Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/test-endpoint', function () {
    return response()->json([
        "message" => "success"
    ], 200);
});

Route::get('/test-referred', function(){
    $avatar = 'https://www.gravatar.com/avatar/4832420a68a29327584e8b1e95e0a266?d=https%3A%2F%2Favatars.csgoempire.com%2Favatars%2Fbc99db94d6d3408ff538fa7677659165f7af1ecc.jpg';

    $users = [
        [
            'avatar' => $avatar,
            'level' => 3,
            'name' => 'Djwaren23',
            'user_id' => 'e63821832d5ba3d6f17fa16f0795913d3486e8bd1d63e3b25e1a02ffd0feaa4e',
            'user_badges' => [],
            'steam_id' => null,
            'referral_since' => 1747614058,
            'last_seen' => 1747612800,
            'total_wagered' => '5',
            'total_commission' => '0.0549',
            'commission_percent' => '30.000000',
            'is_depositor' => false,
        ],
        [
            'avatar' => $avatar,
            'level' => 5,
            'name' => 'ShadowRift',
            'user_id' => 'ab293a55bbf861f3f22d1e872234bd981fb45b216a8d4519de995ca06eea8897',
            'user_badges' => [],
            'steam_id' => null,
            'referral_since' => 1747614090,
            'last_seen' => 1747613000,
            'total_wagered' => '120',
            'total_commission' => '1.5234',
            'commission_percent' => '30.000000',
            'is_depositor' => true,
        ],
        [
            'avatar' => $avatar,
            'level' => 2,
            'name' => 'CrimsonFox',
            'user_id' => 'fe019e6b32a34dcb6f8e3aab6509ef9abed91a002a9c973083d2b1781ad882e1',
            'user_badges' => [],
            'steam_id' => null,
            'referral_since' => 1747614120,
            'last_seen' => 1747613100,
            'total_wagered' => '30',
            'total_commission' => '0.8761',
            'commission_percent' => '30.000000',
            'is_depositor' => false,
        ],
        [
            'avatar' => $avatar,
            'level' => 1,
            'name' => 'BlueTitan',
            'user_id' => '7ab28d0c71838e218d4c7a07d739205b965d6fa1fc1dd36148a6cecfbf70ed34',
            'user_badges' => [],
            'steam_id' => null,
            'referral_since' => 1747614140,
            'last_seen' => 1747613200,
            'total_wagered' => '80',
            'total_commission' => '0.9421',
            'commission_percent' => '30.000000',
            'is_depositor' => true,
        ],
        [
            'avatar' => $avatar,
            'level' => 4,
            'name' => 'DarkNova',
            'user_id' => '119f9182cc8ff776cc432ad77e7dc3efef6607294e749efb71a07ef87453e6d6',
            'user_badges' => [],
            'steam_id' => null,
            'referral_since' => 1747614170,
            'last_seen' => 1747613300,
            'total_wagered' => '150',
            'total_commission' => '2.9000',
            'commission_percent' => '30.000000',
            'is_depositor' => true,
        ],
        [
            'avatar' => $avatar,
            'level' => 6,
            'name' => 'PixelHawk',
            'user_id' => 'd7a812e3f7a6f7ffb2de4b1c3c2433cf8477657c9a3c9f4cfc9053c42a4dc1c2',
            'user_badges' => [],
            'steam_id' => null,
            'referral_since' => 1747614200,
            'last_seen' => 1747613400,
            'total_wagered' => '200',
            'total_commission' => '3.6542',
            'commission_percent' => '30.000000',
            'is_depositor' => true,
        ],
        [
            'avatar' => $avatar,
            'level' => 3,
            'name' => 'IronFist',
            'user_id' => '7e91f07a7b4fabc157b7bc6b1dc0d22c37948a31d10936d35b2b69f3ef25c1e5',
            'user_badges' => [],
            'steam_id' => null,
            'referral_since' => 1747614230,
            'last_seen' => 1747613500,
            'total_wagered' => '65',
            'total_commission' => '0.7821',
            'commission_percent' => '30.000000',
            'is_depositor' => false,
        ],
        [
            'avatar' => $avatar,
            'level' => 2,
            'name' => 'FlameShot',
            'user_id' => '4a9b3fdde65e93166aa943321cabc0d7ecba9cc1de7aa5838d5144adba499bce',
            'user_badges' => [],
            'steam_id' => null,
            'referral_since' => 1747614260,
            'last_seen' => 1747613600,
            'total_wagered' => '90',
            'total_commission' => '1.4125',
            'commission_percent' => '30.000000',
            'is_depositor' => true,
        ],
        [
            'avatar' => $avatar,
            'level' => 7,
            'name' => 'EchoStorm',
            'user_id' => '1b5d6e1c119dbf4a5fcd0cf48981dc52e69f1338ccf46fef1a0e902b6e6a4022',
            'user_badges' => [],
            'steam_id' => null,
            'referral_since' => 1747614290,
            'last_seen' => 1747613700,
            'total_wagered' => '340',
            'total_commission' => '5.9821',
            'commission_percent' => '30.000000',
            'is_depositor' => true,
        ],
        [
            'avatar' => $avatar,
            'level' => 1,
            'name' => 'SilentCore',
            'user_id' => 'faee7d3d5dc8031e328a799f7cb27c6de1b1a3d38a6e0aaf75e4cc875f1f1234',
            'user_badges' => [],
            'steam_id' => null,
            'referral_since' => 1747614320,
            'last_seen' => 1747613800,
            'total_wagered' => '15',
            'total_commission' => '0.1023',
            'commission_percent' => '30.000000',
            'is_depositor' => false,
        ],
    ];

    return response()->json([
        'success' => true,
        'current_page' => 1,
        'data' => $users,
        'first_page_url' => url('/api/referrals/referred-users?page=1'),
        'from' => 1,
        'last_page' => 1,
        'last_page_url' => url('/api/referrals/referred-users?page=1'),
        'links' => [
            ['url' => null, 'label' => '&laquo; Previous', 'active' => false],
            ['url' => url('/api/referrals/referred-users?page=1'), 'label' => '1', 'active' => true],
            ['url' => null, 'label' => 'Next &raquo;', 'active' => false],
        ],
        'next_page_url' => null,
        'path' => url('/api/referrals/referred-users'),
        'per_page' => 20,
        'prev_page_url' => null,
        'to' => 10,
        'total' => 10,
        'historical_data' => [
            'cached_at' => now()->timestamp,
            'resolution' => 'day',
            'cumulative_totals' => [
                'count_visitors' => count($users),
                'count_depositors' => collect($users)->where('is_depositor', true)->count(),
                'wagered_amount' => array_sum(array_column($users, 'total_wagered')),
            ],
        ],
    ]);
});