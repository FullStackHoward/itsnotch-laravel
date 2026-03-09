<?php

namespace App\Filament\Resources\PatreonUrl;

use App\Filament\Resources\PatreonUrl\Pages\CreatePatreonUrl;
use App\Filament\Resources\PatreonUrl\Pages\EditPatreonUrl;
use App\Filament\Resources\PatreonUrl\Pages\ListPatreonUrls;
use App\Filament\Resources\PatreonUrl\Pages\ViewPatreonUrl;
use App\Filament\Resources\PatreonUrl\Schemas\PatreonUrlForm;
use App\Filament\Resources\PatreonUrl\Tables\PatreonUrlTable;
use App\Models\SiteSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PatreonUrlResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?string $navigationLabel = 'Patreon URL';

    protected static ?string $modelLabel = 'Patreon URL';

    protected static ?string $slug = 'patreon-url';

    protected static ?string $recordTitleAttribute = 'key';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('key', 'patreon_url');
    }

    public static function form(Schema $schema): Schema
    {
        return PatreonUrlForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatreonUrlTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatreonUrls::route('/'),
            'create' => CreatePatreonUrl::route('/create'),
            'view' => ViewPatreonUrl::route('/{record}'),
            'edit' => EditPatreonUrl::route('/{record}/edit'),
        ];
    }
}
