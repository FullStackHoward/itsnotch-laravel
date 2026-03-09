<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function getValue(string $key, $default = null): ?string
    {
        return static::where('key', $key)->value('value') ?? $default;
    }

    public static function getYoutubeEmbedUrl(?string $input): ?string
    {
        if (blank($input)) {
            return null;
        }

        $input = trim($input);

        // Already an embed URL — extract the ID from the path
        if (preg_match('#youtube\.com/embed/([a-zA-Z0-9_-]+)#', $input, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // Full watch URL: https://www.youtube.com/watch?v=ABC123...
        if (preg_match('#[?&]v=([a-zA-Z0-9_-]+)#', $input, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // Short URL: https://youtu.be/ABC123 (may have query params)
        if (preg_match('#youtu\.be/([a-zA-Z0-9_-]+)#', $input, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1];
        }

        // Bare video ID (11 URL-safe characters, nothing else)
        if (preg_match('#^[a-zA-Z0-9_-]{11}$#', $input)) {
            return 'https://www.youtube.com/embed/' . $input;
        }

        // If it looks like a full URL we don't recognize, return null
        // so the fallback in Blade kicks in
        return null;
    }
}
