<?php

namespace App\Filament\Resources\PatreonUrl\Pages;

use App\Filament\Resources\PatreonUrl\PatreonUrlResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPatreonUrl extends EditRecord
{
    protected static string $resource = PatreonUrlResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
