<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "cookie",
        "cookie_status",
        "has_winner",
        "first_prize",
        "second_prize",
        "third_prize",
        "leaderboard_ends_at",
        'status',
        'description',
        'updated_at'
    ];

    protected $hidden = [
        'cookie',
    ];

    protected $appends = ['top_referred_users'];

    public function referredUsers(){
        return $this->hasMany(ReferredUser::class);
    }

    public function getTopReferredUsersAttribute()
    {
        return $this->referredUsers()
        ->orderByDesc('wagered_in_leaderboard')
        ->take(3)
        ->get();
    }

    // public function topReferredUsers(){
    //     return $this->hasMany(ReferredUser::class)
    //         ->orderByDesc('wagered_in_leaderboard')
    //         ->limit(2);
    // }

}
