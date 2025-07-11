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
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("cookie");
            $table->string("cookie_status")->default("active");
            $table->boolean("has_winner")->default(false);
            $table->decimal("first_prize", 10, 2)->default(0);
            $table->decimal("second_prize", 10, 2)->default(0);
            $table->decimal("third_prize", 10, 2)->default(value: 0);
            $table->dateTime("leaderboard_ends_at")->nullable();
            $table->string('status')->default("active"); //acitve/paused/ended
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leaderboards');
    }
};
