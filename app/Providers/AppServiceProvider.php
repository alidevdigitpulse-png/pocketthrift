<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DB::listen(function ($query) {
            logger($query->sql, $query->bindings);
        });

        // Register morph map for TrendingItem.item() polymorphic relation
        Relation::morphMap([
            'store' => \App\Models\Store::class,
            'category' => \App\Models\Category::class,
            'offer' => \App\Models\Offer::class,
            'blog' => \App\Models\Blog::class,
        ], false);

        View::share('favicon', 'uploads/favicon.ico'); // Placeholder path
    }
}