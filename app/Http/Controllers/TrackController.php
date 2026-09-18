<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use App\Models\Track;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TrackController extends Controller
{
    public function show(Track $track)
    {
        abort_unless($track->active, 404);

        $coverUrl = asset('storage/' . $track->cover_art_path);
        $coverDimensions = $this->coverDimensions($track);

        $audioSrc = $track->is_free && $track->audio_path
            ? asset('storage/' . $track->audio_path)
            : ($track->preview_path ? asset('storage/' . $track->preview_path) : null);

        $isPreview = !($track->is_free && $track->audio_path) && (bool) $track->preview_path;

        $patreonUrl = SiteSetting::getValue('patreon_url');
        $shareUrl = route('tracks.show', $track);
        $shareDescription = $this->buildDescription($track);

        $waveformPayload = json_encode([
            'peaks' => $track->waveform_peaks ?? [],
            'duration' => $track->duration_seconds,
        ], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);

        return view('tracks.show', [
            'track' => $track,
            'coverUrl' => $coverUrl,
            'coverDimensions' => $coverDimensions,
            'audioSrc' => $audioSrc,
            'isPreview' => $isPreview,
            'patreonUrl' => $patreonUrl,
            'shareUrl' => $shareUrl,
            'shareDescription' => $shareDescription,
            'waveformPayload' => $waveformPayload,
            'hasPeaks' => !empty($track->waveform_peaks),
            'jsonLd' => $this->buildJsonLd($track, $coverUrl, $shareUrl),
        ]);
    }

    public function download(Track $track): BinaryFileResponse
    {
        if (!$track->is_free || !$track->audio_path) {
            abort(403, 'This track is not available for free download.');
        }

        $path = Storage::disk('public')->path($track->audio_path);

        return response()->download($path, $track->title . '.' . pathinfo($track->audio_path, PATHINFO_EXTENSION));
    }

    /**
     * Real cover dimensions, so og:image:width/height are accurate instead of
     * inheriting the layout's site-wide 1200x630 default.
     *
     * @return array{width: int, height: int}|null
     */
    protected function coverDimensions(Track $track): ?array
    {
        if (!$track->cover_art_path) {
            return null;
        }

        $fullPath = Storage::disk('public')->path($track->cover_art_path);

        if (!is_file($fullPath)) {
            return null;
        }

        $size = @getimagesize($fullPath);

        if (!$size) {
            return null;
        }

        return ['width' => $size[0], 'height' => $size[1]];
    }

    /**
     * Tracks have no free-text description field, so build a short one from
     * the tag columns for meta description, OG/Twitter, and share text.
     */
    protected function buildDescription(Track $track): string
    {
        $descriptor = collect([$track->genre[0] ?? null, $track->type[0] ?? null])
            ->filter()
            ->implode(' ');

        $mood = $track->mood[0] ?? null;
        $description = $track->title . ' by Notch64.';

        if ($descriptor !== '') {
            // Most tracks carry type "Instrumental", which would otherwise
            // render as "A Hip Hop Instrumental instrumental".
            if (!Str::endsWith(Str::lower($descriptor), 'instrumental')) {
                $descriptor .= ' instrumental';
            }

            $description .= ' A ' . $descriptor;
            $description .= $mood ? ' with a ' . $mood . ' feel.' : '.';
        } elseif ($mood) {
            $description .= ' A ' . $mood . ' instrumental.';
        }

        $description .= ' Part of the Pixelwave catalog.';

        return Str::limit($description, 160, '');
    }

    protected function buildJsonLd(Track $track, string $coverUrl, string $shareUrl): string
    {
        $genres = collect(array_merge($track->genre, $track->subgenre))
            ->filter()
            ->unique()
            ->values()
            ->all();

        $payload = [
            '@context' => 'https://schema.org',
            '@type' => 'MusicRecording',
            'name' => $track->title,
            'byArtist' => ['@type' => 'MusicGroup', 'name' => 'Notch64'],
            'image' => $coverUrl,
            'url' => $shareUrl,
            'inAlbum' => ['@type' => 'MusicAlbum', 'name' => 'Pixelwave'],
        ];

        if ($track->duration_seconds) {
            $payload['duration'] = 'PT' . (int) round($track->duration_seconds) . 'S';
        }

        if (!empty($genres)) {
            $payload['genre'] = $genres;
        }

        // JSON_HEX_TAG escapes < and > so a title containing "</script>" cannot
        // break out of the <script type="application/ld+json"> block.
        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    }
}
