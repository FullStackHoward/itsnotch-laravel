<?php

namespace App\Services;

use Symfony\Component\Process\Process;

class WaveformService
{
    protected int $sampleRate = 8000;
    protected int $bucketCount = 200;

    /**
     * Decode an audio file to mono PCM with ffmpeg and reduce it to a small
     * amplitude envelope plus the exact duration. Returns null on any failure
     * so callers (the observer) never have to guard against exceptions.
     *
     * @return array{duration: float, peaks: array<int, float>}|null
     */
    public function generate(string $absoluteAudioPath): ?array
    {
        if (!is_file($absoluteAudioPath)) {
            return null;
        }

        $process = new Process([
            config('media.ffmpeg_path', 'ffmpeg'),
            '-v', 'error',
            '-i', $absoluteAudioPath,
            '-f', 'f32le',
            '-acodec', 'pcm_f32le',
            '-ac', '1',
            '-ar', (string) $this->sampleRate,
            '-',
        ]);
        $process->setTimeout(60);

        try {
            $process->run();
        } catch (\Throwable $e) {
            report($e);
            return null;
        }

        if (!$process->isSuccessful()) {
            report(new \RuntimeException('ffmpeg failed to decode ' . $absoluteAudioPath . ': ' . $process->getErrorOutput()));
            return null;
        }

        $raw = $process->getOutput();

        if ($raw === '') {
            return null;
        }

        $unpacked = unpack('f*', $raw);

        if ($unpacked === false) {
            return null;
        }

        $samples = array_values($unpacked);
        $totalSamples = count($samples);

        if ($totalSamples === 0) {
            return null;
        }

        return [
            'duration' => $totalSamples / $this->sampleRate,
            'peaks' => $this->computePeaks($samples, $this->bucketCount),
        ];
    }

    /**
     * Reduce the sample buffer to one RMS value per bucket, normalised so the
     * loudest bucket is 1.0.
     *
     * RMS rather than peak amplitude: these masters are heavily limited, so
     * peak-per-bucket pins almost every bucket near full scale and the
     * waveform renders as a solid block. RMS tracks perceived loudness and
     * keeps the track's dynamics visible.
     *
     * @param array<int, float> $samples
     * @return array<int, float>
     */
    protected function computePeaks(array $samples, int $bucketCount): array
    {
        $total = count($samples);
        $samplesPerBucket = max(1, (int) floor($total / $bucketCount));
        $peaks = [];

        for ($i = 0; $i < $bucketCount; $i++) {
            $start = $i * $samplesPerBucket;
            $end = ($i === $bucketCount - 1) ? $total : min($total, $start + $samplesPerBucket);
            $sumSquares = 0.0;
            $count = 0;

            for ($j = $start; $j < $end; $j++) {
                $sumSquares += $samples[$j] * $samples[$j];
                $count++;
            }

            $peaks[] = $count > 0 ? sqrt($sumSquares / $count) : 0.0;
        }

        $overallMax = max($peaks) ?: 1.0;

        return array_map(fn ($p) => round($p / $overallMax, 4), $peaks);
    }
}
