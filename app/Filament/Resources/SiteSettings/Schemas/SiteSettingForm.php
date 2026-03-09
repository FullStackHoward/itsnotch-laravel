<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('key')
                    ->default('youtube_embed_url'),
                TextInput::make('value')
                    ->label('YouTube URL')
                    ->required()
                    ->placeholder('https://www.youtube.com/watch?v=... or video ID')
                    ->columnSpanFull(),
            ]);
    }
}
