<?php

namespace App\Filament\Resources\Settings\ItemTypes;

use App\Filament\Resources\Settings\ItemTypes\Pages\EditItemType;
use App\Filament\Resources\Settings\ItemTypes\Pages\ListItemTypes;
use App\Models\Item;
use App\Models\ItemType;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class ItemTypeResource extends Resource
{
    protected static ?string $model = ItemType::class;

    protected static ?int $navigationSort = 21;

    public static function getNavigationGroup(): ?string
    {
        return __('settings.cluster');
    }

    public static function getNavigationLabel(): string
    {
        return __('settings.types');
    }

    public static function getModelLabel(): string
    {
        return __('settings.type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('settings.types');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                TextInput::make('name')
                    ->label(__('settings.type-name'))
                    ->columnSpan(1)
                    ->required()
                    ->maxLength(255),
                TextInput::make('icon')
                    ->label(__('settings.type-icon'))
                    ->hint(__('settings.type-icon-hint'))
                    ->placeholder('circle-stack')
                    ->maxLength(100),
                Toggle::make('active')
                    ->label(__('settings.type-active'))
                    ->default(true)
                    ->inline(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('settings.type-name'))
                    ->searchable()
                    ->sortable(),
                IconColumn::make('icon')
                    ->label(__('settings.type-icon'))
                    ->icon(fn(ItemType $type) => "heroicon-o-$type->icon")
                    ->placeholder('—'),
                ToggleColumn::make('active')
                    ->label(__('settings.type-active')),
            ])
            ->recordActions([
                EditAction::make()->iconButton(),
                DeleteAction::make()->iconButton(),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListItemTypes::route('/'),
        ];
    }
}
