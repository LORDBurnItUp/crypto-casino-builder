<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharitableCausesTable extends Migration
{
    public function up()
    {
        Schema::create('charitable_causes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->enum('category', [
                'environment',      // Trees, Conservation
                'water',           // Clean Water, Recycling
                'food',            // Food Banks, Hunger Relief
                'children',        // Orphanages, Child Welfare
                'medical',         // Medical Aid, Healthcare
                'education',       // Schools, Literacy
                'housing',         // Homeless Shelters
                'general'          // Other Good Causes
            ]);
            $table->string('icon')->default('🌍');
            $table->string('organization')->nullable(); // Partner organization name
            $table->string('website')->nullable();
            $table->decimal('total_donations_received', 20, 2)->default(0);
            $table->integer('total_contributors')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('impact_metrics')->nullable(); // {"trees_planted": 1000, "meals_served": 5000}
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('charitable_causes');
    }
}
