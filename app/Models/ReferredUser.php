<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferredUser extends Model
{
    use HasFactory;

    protected $fillable = [
        "leaderboard_id",
        'user_id',
        'name',
        'avatar',
        'level',
        'user_badges',
        'steam_id',
        'referral_since',
        'last_seen',
        'wagered_at_start',
        'wagered_at_end',
        'wagered_in_leaderboard',
        'total_wagered', // current wagered from api
        'total_commission',
        'commission_percent',
        'is_depositor',
        'status',
    ];

    protected $hidden = [
        "user_id",
        'level',
        'user_badges',
        'steam_id',
        "referral_since",
        "last_seen",
        "total_commission",
        "commission_percent",
        "is_depositor",
    ];

    public function leaderboard(){
        return $this->belongsTo(Leaderboard::class);
    }
}
