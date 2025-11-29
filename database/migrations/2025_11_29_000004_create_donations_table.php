<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cause_id')->constrained('charitable_causes')->onDelete('cascade');
            $table->decimal('forum_gold_amount', 20, 2);
            $table->decimal('real_money_amount', 10, 2); // Converted amount in USD
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->text('certificate_message')->nullable();
            $table->string('transaction_reference')->nullable(); // Real donation receipt/tracking
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index('cause_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('donations');
    }
}
