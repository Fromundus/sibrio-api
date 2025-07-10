<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_code',
        'referral_link',
        'leaderboard_type',
        'first_prize',
        'second_prize',
        'third_prize',
        'terms',
        'is_active',
    ];
}
