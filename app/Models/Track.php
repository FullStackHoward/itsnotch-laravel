<?php

namespace App\Models;

use App\Casts\CommaSeparated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Track extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'genre',
        'subgenre',
        'mood',
        'type',
        'cover_art_path',
        'extracted_color',
        'audio_path',
        'preview_path',
        'is_free',
        'patreon_url',
        'active',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'active' => 'boolean',
        'genre' => CommaSeparated::class,
        'subgenre' => CommaSeparated::class,
        'mood' => CommaSeparated::class,
        'type' => CommaSeparated::class,
    ];

    protected static function booted(): void
    {
        static::creating(function (Track $track) {
            if (empty($track->slug)) {
                $track->slug = Str::slug($track->title);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
