<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (!\Illuminate\Support\Facades\Schema::hasTable('site_settings')) {
                $view->with('settings', null);
                return;
            }

            $settings = Cache::remember('site-settings', 3600, function () {
                return SiteSetting::first();
            });

            $view->with('settings', $settings);
        });

        Paginator::useBootstrapFour();
    }
}
