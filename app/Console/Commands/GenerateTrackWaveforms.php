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
        $updated = 0;
        $unchanged = 0;
        $failed = [];

        foreach ($tracks as $track) {
            // Call the observer directly: $track->save() on an otherwise
            // unchanged model leaves is_free/audio_path/preview_path clean,
            // so updating() would skip it.
            $hasPeaks = $observer->forceGenerateWaveform($track);
            $changed = $track->isDirty(['duration_seconds', 'waveform_peaks']);

            if ($changed) {
                $track->save();
            }

            if (!$hasPeaks) {
                $failed[] = $track->title;
            } elseif ($changed) {
                $updated++;
            } else {
                $unchanged++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        $this->info($updated . ' updated, ' . $unchanged . ' already current, ' . count($failed) . ' with no waveform.');

        if ($failed !== []) {
            $this->newLine();
            $this->warn('No waveform could be generated for:');

            foreach ($failed as $title) {
                $this->line('  - ' . $title);
            }

            $this->newLine();
            $this->warn('These tracks either have no audio/preview file, or ffmpeg could not decode them.');
            $this->warn('Check that ffmpeg is installed and on PATH: ' . config('media.ffmpeg_path', 'ffmpeg') . ' -version');
        }

        return self::SUCCESS;
    }
}
