<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing 'Banner' types to 'Bottom Banner' to match the new nomenclature
        DB::table('banners')->where('type', 'Banner')->update(['type' => 'Bottom Banner']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'Bottom Banner' types back to 'Banner'
        DB::table('banners')->where('type', 'Bottom Banner')->update(['type' => 'Banner']);
    }
};
