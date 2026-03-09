<?php

namespace App\Filament\Resources\PatreonUrl\Pages;

use App\Filament\Resources\PatreonUrl\PatreonUrlResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPatreonUrls extends ListRecords
{
    protected static string $resource = PatreonUrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
