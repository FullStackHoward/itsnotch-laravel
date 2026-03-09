<?php

namespace App\Filament\Resources\Tracks\Schemas;

use App\Models\Track;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TrackForm
{
    public static function configure(Schema $schema): Schema
    {
        $uniqueValues = fn(string $col) => Track::whereNotNull($col)
            ->getQuery()
            ->pluck($col)
            ->flatMap(fn($v) => explode(',', $v))
            ->map(fn($v) => trim($v))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->toArray();

        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TagsInput::make('genre')
                    ->label('Genre')
                    ->separator(',')
                    ->suggestions($uniqueValues('genre')),
                TagsInput::make('subgenre')
                    ->label('Sub-Genre')
                    ->separator(',')
                    ->suggestions($uniqueValues('subgenre')),
                TagsInput::make('mood')
                    ->label('Mood')
                    ->separator(',')
                    ->suggestions($uniqueValues('mood')),
                TagsInput::make('type')
                    ->label('Type')
                    ->separator(',')
                    ->suggestions($uniqueValues('type')),
                FileUpload::make('cover_art_path')
                    ->label('Cover Art')
                    ->disk('public')
                    ->directory('covers')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->required(),
                TextInput::make('extracted_color'),
                FileUpload::make('audio_path')
                    ->label('Full Track (Free Downloads Only)')
                    ->disk('public')
                    ->directory('audio')
                    ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg']),
                FileUpload::make('preview_path')
                    ->label('Preview Clip (Paid Tracks)')
                    ->disk('public')
                    ->directory('previews')
                    ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg']),
                Toggle::make('is_free')
                    ->required(),
                Toggle::make('active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
