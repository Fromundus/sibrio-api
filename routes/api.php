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