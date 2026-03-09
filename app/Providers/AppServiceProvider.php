<?php

namespace App\Providers;

use App\Models\Track;
use App\Observers\TrackObserver;
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
        Track::observe(TrackObserver::class);
    }
}
