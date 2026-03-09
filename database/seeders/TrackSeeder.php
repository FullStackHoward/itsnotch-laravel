<?php

namespace Database\Seeders;

use App\Models\Pack;
use App\Models\SiteSetting;
use App\Models\Track;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TrackSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure the storage/app/public/covers directory exists
        Storage::disk('public')->makeDirectory('covers');

        // Create a simple placeholder image for seeding
        $this->createPlaceholderImage('covers/pixelwave.png', '#6C3483');
        $this->createPlaceholderImage('covers/arcade.png', '#E74C3C');
        $this->createPlaceholderImage('covers/wings.png', '#2ECC71');
        $this->createPlaceholderImage('covers/neon-drift.png', '#F39C12');
        $this->createPlaceholderImage('covers/midnight-protocol.png', '#3498DB');

        Track::create([
            'title' => 'Pixelwave',
            'slug' => 'pixelwave',
            'genre' => 'Hip-Hop',
            'subgenre' => 'Chiptune',
            'mood' => 'Hype',
            'cover_art_path' => 'covers/pixelwave.png',
            'extracted_color' => '#6C3483',
            'is_free' => true,
            'audio_path' => null,
            'active' => true,
        ]);

        Track::create([
            'title' => 'Arcade',
            'slug' => 'arcade',
            'genre' => 'Hip-Hop',
            'subgenre' => 'Trap',
            'mood' => 'Hype',
            'cover_art_path' => 'covers/arcade.png',
            'extracted_color' => '#E74C3C',
            'is_free' => false,
            'patreon_url' => 'https://www.patreon.com/notch64',
            'active' => true,
        ]);

        Track::create([
            'title' => 'Wings',
            'slug' => 'wings',
            'genre' => 'House',
            'subgenre' => 'Lo-Fi',
            'mood' => 'Uplifting',
            'cover_art_path' => 'covers/wings.png',
            'extracted_color' => '#2ECC71',
            'is_free' => false,
            'patreon_url' => 'https://www.patreon.com/notch64',
            'active' => true,
        ]);

        Track::create([
            'title' => 'Neon Drift',
            'slug' => 'neon-drift',
            'genre' => 'Electronic',
            'subgenre' => 'Synthwave',
            'mood' => 'Nostalgic',
            'cover_art_path' => 'covers/neon-drift.png',
            'extracted_color' => '#F39C12',
            'is_free' => true,
            'audio_path' => null,
            'active' => true,
        ]);

        Track::create([
            'title' => 'Midnight Protocol',
            'slug' => 'midnight-protocol',
            'genre' => 'Electronic',
            'subgenre' => 'Lo-Fi',
            'mood' => 'Chill',
            'cover_art_path' => 'covers/midnight-protocol.png',
            'extracted_color' => '#3498DB',
            'is_free' => false,
            'patreon_url' => 'https://www.patreon.com/notch64',
            'active' => true,
        ]);

        // Seed packs
        Pack::create([
            'name' => 'G-Funk Kit Vol. 1',
            'is_free' => true,
            'download_path' => null,
        ]);

        Pack::create([
            'name' => 'Chiptune Drum Kit',
            'is_free' => false,
            'patreon_url' => 'https://www.patreon.com/notch64',
        ]);

        Pack::create([
            'name' => 'Lo-Fi Sample Pack Vol. 2',
            'is_free' => true,
            'download_path' => null,
        ]);

        // Seed site settings
        SiteSetting::create([
            'key' => 'youtube_embed_url',
            'value' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
        ]);

        SiteSetting::create([
            'key' => 'patreon_url',
            'value' => 'https://www.patreon.com/notch64',
        ]);
    }

    /**
     * Create a simple solid-color placeholder image for seeding purposes.
     */
    private function createPlaceholderImage(string $path, string $hexColor): void
    {
        $fullPath = Storage::disk('public')->path($path);

        // Only create if GD is available
        if (!function_exists('imagecreatetruecolor')) {
            // Fallback: create a tiny 1x1 PNG manually
            $this->createTinyPng($fullPath);
            return;
        }

        $img = imagecreatetruecolor(250, 250);
        $r = hexdec(substr($hexColor, 1, 2));
        $g = hexdec(substr($hexColor, 3, 2));
        $b = hexdec(substr($hexColor, 5, 2));
        $color = imagecolorallocate($img, $r, $g, $b);
        imagefill($img, 0, 0, $color);
        imagepng($img, $fullPath);
        imagedestroy($img);
    }

    private function createTinyPng(string $path): void
    {
        // Minimal valid 1x1 red PNG
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg=='
        );
        file_put_contents($path, $png);
    }
}
