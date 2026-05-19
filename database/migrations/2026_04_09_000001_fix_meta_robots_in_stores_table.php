<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixMetaRobotsInStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if table exists first just to be super safe
        if (Schema::hasTable('stores')) {
            // Increase length and allow nulls, similar to what was done for categories
            DB::statement('ALTER TABLE stores MODIFY meta_robots VARCHAR(500) NULL DEFAULT "index, follow"');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('stores')) {
            DB::statement('ALTER TABLE stores MODIFY meta_robots VARCHAR(255) NULL');
        }
    }
}
