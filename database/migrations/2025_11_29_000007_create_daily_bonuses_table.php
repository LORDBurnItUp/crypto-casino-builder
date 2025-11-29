<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyBonusesTable extends Migration
{
    public function up()
    {
        Schema::create('daily_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('login_date');
            $table->decimal('bonus_amount', 20, 2);
            $table->integer('streak_days');
            $table->timestamps();

            $table->unique(['user_id', 'login_date']);
            $table->index('login_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('daily_bonuses');
    }
}
