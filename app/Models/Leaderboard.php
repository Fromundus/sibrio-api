<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    use HasFactory;

    protected $fillable = [
        "cookie",
        "cookie_status",
        "has_winner",
        "prize",
    ];

    protected $hidden = [
        'cookie',
    ];

    public function referredUsers(){
        return $this->hasMany(ReferredUser::class);
    }
}
