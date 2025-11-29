<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForumGoldTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('forum_gold_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 20, 2);
            $table->enum('type', ['earn', 'spend', 'bonus', 'donation', 'game_win', 'game_loss', 'achievement', 'referral', 'admin_adjustment']);
            $table->text('description');
            $table->decimal('balance_after', 20, 2);
            $table->string('reference_id')->nullable(); // Game session ID, donation ID, etc.
            $table->string('reference_type')->nullable(); // Type of reference
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });
    }

    public function down()
    {
        Schema::dropIfExists('forum_gold_transactions');
    }
}
