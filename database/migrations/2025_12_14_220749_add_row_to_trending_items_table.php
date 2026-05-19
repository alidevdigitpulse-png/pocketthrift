<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRowToTrendingItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasColumn('trending_items', 'row')) {
            Schema::table('trending_items', function (Blueprint $table) {
                $table->integer('row')->default(1)->after('position')->comment('Carousel row number (1, 2, or 3)');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trending_items', function (Blueprint $table) {
            $table->dropColumn('row');
        });
    }
}
