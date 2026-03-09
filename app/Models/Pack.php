<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pack extends Model
{
    protected $fillable = [
        'name',
        'cover_art_path',
        'is_free',
        'download_path',
        'patreon_url',
        'status',
    ];

    protected $casts = [
        'is_free' => 'boolean',
    ];
}
