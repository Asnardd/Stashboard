<?php

namespace App\Filament\Resources\Settings\Tags;

use App\Filament\Resources\Settings\Tags\Pages\ListTags;
use App\Models\Tag;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): ?string
    {
        return __('settings.cluster');
    }

    public static function getNavigationLabel(): string
    {
        return __('settings.tags');
    }

    public static function getModelLabel(): string
    {
        return __('settings.tag');
    }

    public static function getPluralModelLabel(): string
    {
        return __('settings.tags');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label(__('settings.tag-name'))
                ->required()
                ->maxLength(255),
            ColorPicker::make('color')
                ->label(__('settings.tag-color'))
                ->default('#6b7280'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ColorColumn::make('color')
                    ->label(__('settings.tag-color')),
                TextColumn::make('name')
                    ->label(__('settings.tag-name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('items_count')
                    ->label(__('settings.tag-items'))
                    ->counts('items')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make()->iconButton()->color(Color::Yellow),
                DeleteAction::make()->iconButton()->color(Color::Red),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTags::route('/'),
        ];
    }
}
