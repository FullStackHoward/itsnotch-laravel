<?php

namespace App\Services;

use League\ColorExtractor\Color;
use League\ColorExtractor\ColorExtractor;
use League\ColorExtractor\Palette;

class ColorExtractorService
{
    /**
     * Extract the dominant color from an image and return it as a hex string.
     */
    public function extractDominantColor(string $imagePath): ?string
    {
        if (!file_exists($imagePath)) {
            return null;
        }

        try {
            $palette = Palette::fromFilename($imagePath);
            $extractor = new ColorExtractor($palette);
            $colors = $extractor->extract(1);

            if (empty($colors)) {
                return null;
            }

            return Color::fromIntToHex($colors[0]);
        } catch (\Throwable $e) {
            report($e);
            return null;
        }
    }
}
