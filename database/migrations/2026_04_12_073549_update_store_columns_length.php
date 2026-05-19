<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateStoreColumnsLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('stores')) {
            // We set database columns to a larger size to avoid SQL truncation errors,
            // while Laravel validation and HTML maxlength will enforce the user's specific limits.
            DB::statement('ALTER TABLE stores MODIFY title VARCHAR(255)');
            DB::statement('ALTER TABLE stores MODIFY seo_title VARCHAR(255)');
            DB::statement('ALTER TABLE stores MODIFY seo_meta_keyword VARCHAR(255)');
            DB::statement('ALTER TABLE stores MODIFY meta_description VARCHAR(500)');
            DB::statement('ALTER TABLE stores MODIFY title_h1 VARCHAR(255)');
            DB::statement('ALTER TABLE stores MODIFY subtitle_h2 VARCHAR(255)');
            DB::statement('ALTER TABLE stores MODIFY image_alt VARCHAR(255)');
            DB::statement('ALTER TABLE stores MODIFY image_title VARCHAR(255)');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No need to revert specifically as larger is usually better for app stability
    }
}
