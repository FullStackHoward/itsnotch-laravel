<?php

namespace App\Filament\Resources\Packs\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PackInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('cover_art_path')
                    ->placeholder('-'),
                IconEntry::make('is_free')
                    ->boolean(),
                TextEntry::make('download_path')
                    ->placeholder('-'),
                TextEntry::make('patreon_url')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
