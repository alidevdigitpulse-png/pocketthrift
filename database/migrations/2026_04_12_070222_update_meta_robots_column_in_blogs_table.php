<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateMetaRobotsColumnInBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('blogs')) {
            DB::statement('ALTER TABLE blogs MODIFY meta_robots VARCHAR(500) NULL DEFAULT "index, follow"');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('blogs')) {
            DB::statement('ALTER TABLE blogs MODIFY meta_robots VARCHAR(255) NULL');
        }
    }
}
