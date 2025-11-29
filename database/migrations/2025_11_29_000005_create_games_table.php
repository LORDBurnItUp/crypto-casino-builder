<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['slots', 'roulette', 'dice', 'blackjack', 'poker', 'wheel', 'mines', 'crash']);
            $table->text('description');
            $table->decimal('min_bet', 20, 2)->default(10);
            $table->decimal('max_bet', 20, 2)->default(10000);
            $table->decimal('house_edge', 5, 2)->default(2.00); // Percentage
            $table->json('game_config')->nullable(); // Game-specific configuration
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->bigInteger('total_plays')->default(0);
            $table->decimal('total_wagered', 28, 2)->default(0);
            $table->decimal('total_payout', 28, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('games');
    }
}
