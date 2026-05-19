<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Using raw SQL to avoid needing doctrine/dbal package
        DB::statement("ALTER TABLE banners MODIFY name VARCHAR(255) NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Reverting back to NOT NULL
        DB::statement("ALTER TABLE banners MODIFY name VARCHAR(255) NOT NULL");
    }
};
