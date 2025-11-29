<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPurchasesTable extends Migration
{
    public function up()
    {
        Schema::create('user_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained('shop_items')->onDelete('cascade');
            $table->decimal('gold_spent', 20, 2);
            $table->timestamp('expires_at')->nullable(); // For temporary items
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('item_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_purchases');
    }
}
