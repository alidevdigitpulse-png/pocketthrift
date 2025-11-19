<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trending_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_type'); // 'store', 'category', 'offer'
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('region_id')->nullable(); // Link to specific region or null for global
            $table->unsignedBigInteger('user_id')->nullable(); // User who marked it as trending
            $table->integer('position')->default(1); // Position in the trending list (1-5)
            $table->timestamps();
            
            // Add index for better performance
            $table->index(['item_type', 'item_id']);
            $table->index(['region_id', 'item_type', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trending_items');
    }
};