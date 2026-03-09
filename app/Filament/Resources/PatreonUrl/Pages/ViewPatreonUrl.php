<?php

namespace App\Filament\Resources\PatreonUrl\Pages;

use App\Filament\Resources\PatreonUrl\PatreonUrlResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPatreonUrl extends ViewRecord
{
    protected static string $resource = PatreonUrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
