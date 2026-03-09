<?php

namespace App\Filament\Resources\Packs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PackForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                FileUpload::make('cover_art_path')
                    ->label('Cover Art')
                    ->disk('public')
                    ->directory('covers')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                Select::make('status')
                    ->options([
                        'download'    => 'Download',
                        'patreon'     => 'Patreon Only',
                        'coming_soon' => 'Coming Soon!',
                    ])
                    ->required()
                    ->default('coming_soon')
                    ->live(),
                FileUpload::make('download_path')
                    ->label('Download File')
                    ->disk('public')
                    ->directory('downloads')
                    ->visible(fn ($get) => $get('status') === 'download'),
            ]);
    }
}
