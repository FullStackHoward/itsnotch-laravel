<?php

namespace App\Filament\Resources\PatreonUrl\Schemas;

use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PatreonUrlForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('key')
                    ->default('patreon_url'),
                TextInput::make('value')
                    ->label('Patreon URL')
                    ->url()
                    ->required()
                    ->placeholder('https://www.patreon.com/...')
                    ->columnSpanFull(),
            ]);
    }
}
