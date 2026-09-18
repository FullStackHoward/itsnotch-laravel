<?php

namespace App\Console\Commands;

use App\Models\Track;
use App\Observers\TrackObserver;
use Illuminate\Console\Command;

class GenerateTrackWaveforms extends Command
{
    protected $signature = 'tracks:generate-waveforms {--force : Regenerate even if peaks already exist}';
    protected $description = 'Backfill waveform peaks and duration for existing tracks';

    public function handle(TrackObserver $observer): int
    {
        $query = Track::query();

        if (!$this->option('force')) {
            $query->whereNull('waveform_peaks');
        }

        $tracks = $query->get();

        if ($tracks->isEmpty()) {
            $this->info('Nothing to backfill.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($tracks->count());
        $generated = 0;
        $skipped = 0;

        foreach ($tracks as $track) {
            // Call the observer directly: $track->save() on an otherwise
            // unchanged model leaves is_free/audio_path/preview_path clean,
            // so updating() would skip it.
            $observer->forceGenerateWaveform($track);

            if ($track->isDirty(['duration_seconds', 'waveform_peaks'])) {
                $track->save();
                $generated++;
            } else {
                $skipped++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info($generated . ' track(s) updated, ' . $skipped . ' skipped (no audio, or ffmpeg failed).');

        return self::SUCCESS;
    }
}
