<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAchievementsTable extends Migration
{
    public function up()
    {
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('achievement_id')->constrained()->onDelete('cascade');
            $table->timestamp('unlocked_at');
            $table->boolean('reward_claimed')->default(false);

            $table->unique(['user_id', 'achievement_id']);
            $table->index('unlocked_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_achievements');
    }
}
