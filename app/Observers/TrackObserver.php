<?php

namespace App\Observers;

use App\Models\Track;
use App\Services\ColorExtractorService;
use App\Services\WaveformService;
use Illuminate\Support\Facades\Storage;

class TrackObserver
{
    public function __construct(
        protected ColorExtractorService $colorExtractor,
        protected WaveformService $waveformService,
    ) {}

    public function creating(Track $track): void
    {
        if (empty($track->extracted_color)) {
            $this->extractColor($track);
        }

        $this->generateWaveform($track);
    }

    public function updating(Track $track): void
    {
        if ($track->isDirty('cover_art_path')) {
            $this->extractColor($track);
        }

        if ($track->isDirty(['is_free', 'audio_path', 'preview_path'])) {
            $this->generateWaveform($track);
        }
    }

    /**
     * Regenerate peaks regardless of what is dirty. Used by the
     * tracks:generate-waveforms backfill command, where the model itself
     * has not changed and the dirty check in updating() would skip it.
     */
    public function forceGenerateWaveform(Track $track): void
    {
        $this->generateWaveform($track);
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

    /**
     * Peaks always describe whichever file the site would actually play,
     * matching the exact selection rule already used in TrackController
     * and the homepage card: the full file for free tracks, the preview
     * clip for paid ones.
     */
    protected function generateWaveform(Track $track): void
    {
        $sourcePath = ($track->is_free && $track->audio_path)
            ? $track->audio_path
            : $track->preview_path;

        if (!$sourcePath) {
            $track->duration_seconds = null;
            $track->waveform_peaks = null;
            return;
        }

        $fullPath = Storage::disk('public')->path($sourcePath);
        $result = $this->waveformService->generate($fullPath);

        if ($result) {
            $track->duration_seconds = $result['duration'];
            $track->waveform_peaks = $result['peaks'];
        }
    }
}
