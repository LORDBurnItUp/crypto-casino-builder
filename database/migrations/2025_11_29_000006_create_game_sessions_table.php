<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('game_id')->constrained()->onDelete('cascade');
            $table->decimal('bet_amount', 20, 2);
            $table->decimal('win_amount', 20, 2)->default(0);
            $table->decimal('profit', 20, 2); // win_amount - bet_amount
            $table->json('game_data'); // Game-specific data (roulette number, slot symbols, etc.)
            $table->string('server_seed');
            $table->string('client_seed');
            $table->string('nonce');
            $table->string('result_hash'); // For provably fair verification
            $table->boolean('is_win')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('game_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('game_sessions');
    }
}
