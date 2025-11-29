<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopItemsTable extends Migration
{
    public function up()
    {
        Schema::create('shop_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->enum('type', ['avatar', 'theme', 'boost', 'badge', 'vip', 'donation_bundle']);
            $table->text('description');
            $table->decimal('cost_gold', 20, 2);
            $table->string('icon')->nullable();
            $table->json('item_data')->nullable(); // Type-specific data
            $table->integer('duration_days')->nullable(); // For temporary items like boosts
            $table->boolean('is_active')->default(true);
            $table->integer('purchase_count')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shop_items');
    }
}
