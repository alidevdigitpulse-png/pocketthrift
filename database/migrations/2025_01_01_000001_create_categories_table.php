<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_eng')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_meta_keyword')->nullable();
            $table->string('url_slug')->unique();
            $table->text('meta_description')->nullable();
            $table->string('title_h1')->nullable();
            $table->string('subtitle_h2')->nullable();
            $table->longText('content_body')->nullable();
            $table->string('logo')->nullable();
            $table->string('image_alt')->nullable();
            $table->string('image_title')->nullable();
            $table->string('meta_robots')->nullable()->default('index, follow');
            $table->json('country_codes')->nullable(); // Stores multiple region IDs
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_seasonal')->default(false);
            $table->boolean('active')->default(true);
            $table->integer('sort')->default(0);
            $table->timestamps();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}