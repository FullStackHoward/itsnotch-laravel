<?php

namespace App\Filament\Resources\Tracks\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TrackInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('genre'),
                TextEntry::make('subgenre')
                    ->placeholder('-'),
                TextEntry::make('mood')
                    ->placeholder('-'),
                TextEntry::make('cover_art_path'),
                TextEntry::make('extracted_color')
                    ->placeholder('-'),
                TextEntry::make('audio_path')
                    ->placeholder('-'),
                TextEntry::make('preview_path')
                    ->placeholder('-'),
                IconEntry::make('is_free')
                    ->boolean(),
                TextEntry::make('patreon_url')
                    ->placeholder('-'),
                IconEntry::make('active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
