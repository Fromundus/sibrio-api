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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string("referral_code")->nullable();
            $table->string("referral_link")->nullable();
            $table->string('leaderboard_type')->default('daily'); // daily, weekly, monthly
            $table->decimal("first_prize", 10, 2)->default(0);
            $table->decimal("second_prize", 10, 2)->default(0);
            $table->decimal("third_prize", 10, 2)->default(0);
            $table->text('terms')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
