<?php

namespace App\Observers;

use App\Models\Track;
use App\Services\ColorExtractorService;
use Illuminate\Support\Facades\Storage;

class TrackObserver
{
    public function __construct(
        protected ColorExtractorService $colorExtractor,
    ) {}

    public function creating(Track $track): void
    {
        if (empty($track->extracted_color)) {
            $this->extractColor($track);
        }
    }

    public function updating(Track $track): void
    {
        if ($track->isDirty('cover_art_path')) {
            $this->extractColor($track);
        }
    }

    protected function extractColor(Track $track): void
    {
        if (empty($track->cover_art_path)) {
            return;
        }

        $fullPath = Storage::disk('public')->path($track->cover_art_path);
        $color = $this->colorExtractor->extractDominantColor($fullPath);

        if ($color) {
            $track->extracted_color = $color;
        }
    }
}
