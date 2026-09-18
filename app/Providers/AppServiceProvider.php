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
        // This host overrides serialize_precision to 100, so json_encode() prints
        // floats to full binary expansion: a waveform peak of 0.1236 ships as
        // 0.12360000000000000153210777398271602578461170196533203125, bloating the
        // 200-peak payload on every track page from ~1KB to ~11KB. -1 is PHP's own
        // default: the shortest representation that still round-trips exactly.
        ini_set('serialize_precision', '-1');

        Track::observe(TrackObserver::class);

        // @versioned('css/style.css') -> /css/style.css?v=<mtime>
        Blade::directive('versioned', fn (string $expression) => "<?php echo " . Asset::class . "::versioned({$expression}); ?>");
    }
}
