<?php

namespace App\Providers;

use App\Models\Track;
use App\Observers\TrackObserver;
use App\Support\Asset;
use Illuminate\Support\Facades\Blade;
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

        // @versioned('css/style.css') -> /css/style.css?v=<mtime>
        Blade::directive('versioned', fn (string $expression) => "<?php echo " . Asset::class . "::versioned({$expression}); ?>");
    }
}
