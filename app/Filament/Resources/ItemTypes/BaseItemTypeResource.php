<?php

namespace App\Filament\Resources\ItemTypes;

use App\Filament\Resources\ItemTypes\RelationManagers\UsageRelationManager;
use App\Filament\Resources\ItemTypes\Schemas\ItemTypeForm;
use App\Filament\Resources\ItemTypes\Tables\ItemTypeTable;
use App\Models\Item;
use App\Models\ItemType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseItemTypeResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static ?int $navigationSort = 1;

    protected static string $typeName;

    private static array $typeCache = [];

    protected static function getItemType(): ItemType
    {
        return self::$typeCache[static::class]
            ??= ItemType::where('name', static::$typeName)->firstOrFail();
    }

    public static function getModelLabel(): string
    {
        return static::getItemType()->name;
    }

    public static function getPluralModelLabel(): string
    {
        return static::getItemType()->name;
    }

    public static function getNavigationLabel(): string
    {
        return static::getItemType()->name;
    }

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        $icon = static::getItemType()->icon;
        return $icon ? "heroicon-o-{$icon}" : Heroicon::OutlinedCube;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return static::getItemType()->active;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('item_type_id', static::getItemType()->id);
    }

    public static function getRelations(): array
    {
        return [
            UsageRelationManager::class,
        ];
    }

    public static function table(Table $table): Table
    {
        return ItemTypeTable::configure($table, static::getItemType());
    }

    public static function form(Schema $schema): Schema
    {
        return ItemTypeForm::configure($schema, static::getItemType());
    }
}
