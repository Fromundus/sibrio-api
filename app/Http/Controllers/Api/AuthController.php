<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use app\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request){
        $credentials = $request->validated();
        if(!Auth::attempt($credentials)){
            return response([
                "message" => "Invalid Credentials"
            ]);
        } else {
            $user = User::where("username", $request->username)->first();

            if($user){
                if($user->status === "active"){

                    /** @var User $user */

                    $user = Auth::user();
                    $token = $user->createToken($request->username)->plainTextToken;

                    return response()->json([
                        "token" => $token,
                        "role" => $user->role,
                        "name" => $user->name,
                        "id" => $user->id,
                    ], 200);

                } else if($user->status === "pending") {
                    return response()->json([
                        "message" => "Pending Account"
                    ], 200);
                } else {
                    return response()->json([
                        "message" => "Invalid Account"
                    ], 200);
                }

            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "User not found"
                ], 404);
            }
            // /** @var User $user */
    
            // $user = Auth::user();
            // $token = $user->createToken($request->username)->plainTextToken;
    
            // return response()->json([
            //     "token" => $token,
            //     "role" => $user->role,
            //     "name" => $user->name,
            //     "id" => $user->id,
            // ], 200);

        }

    }

    public function logout(Request $request){
        /** @var User $user */

        $user = $request->user();
        $user->currentAccessToken()->delete();

        return response("", 204);
    }
}
