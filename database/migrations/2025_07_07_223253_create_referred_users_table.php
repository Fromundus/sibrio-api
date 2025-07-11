<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('referred_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("leaderboard_id");

            $table->string('user_id'); // Unique user ID from API
            $table->string('name');
            $table->string('avatar')->nullable();
            $table->unsignedInteger('level')->default(0);
            $table->json('user_badges')->nullable();
            $table->string('steam_id')->nullable();
            $table->unsignedBigInteger('referral_since')->nullable(); // UNIX timestamp
            $table->unsignedBigInteger('last_seen')->nullable(); // UNIX timestamp
            $table->decimal('wagered_at_start', 15, 2)->default(0);
            $table->decimal('wagered_at_end', 15, 2)->default(0)->nullable();
            $table->decimal('wagered_in_leaderboard', 15, 2)->default(0);
            $table->decimal('total_wagered', 15, 2)->default(0);
            $table->decimal('total_commission', 15, 4)->default(0);
            $table->decimal('commission_percent', 5, 3)->default(0);
            $table->boolean('is_depositor')->default(false);
            $table->string("status")->nullable();

            $table->timestamps();

            $table->foreign("leaderboard_id")->references("id")->on("leaderboards")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referred_users');
    }
};
