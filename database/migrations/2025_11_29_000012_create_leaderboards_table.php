<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaderboardsTable extends Migration
{
    public function up()
    {
        Schema::create('leaderboards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('category', ['gold_earned', 'donations', 'games_played', 'win_streak', 'total_wins']);
            $table->decimal('score', 28, 2);
            $table->enum('period', ['daily', 'weekly', 'monthly', 'all_time']);
            $table->date('period_date'); // For daily/weekly/monthly tracking
            $table->integer('rank')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'category', 'period', 'period_date']);
            $table->index(['category', 'period', 'period_date', 'score']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('leaderboards');
    }
}
