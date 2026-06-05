<?php

namespace App\Filament\Resources\Items\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('items.name'))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type.name')
                    ->label(__('items.type'))
                    ->badge()
                    ->sortable(),

                TextColumn::make('serial')
                    ->label(__('items.serial'))
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('quantity')
                    ->label(__('items.quantity'))
                    ->sortable(),

                TextColumn::make('tags.name')
                    ->label(__('items.tags'))
                    ->badge()
                    ->separator(','),

                TextColumn::make('notes')
                    ->label(__('items.notes'))
                    ->limit(50)
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label(__('items.type'))
                    ->relationship('type', 'name')
                    ->preload(),

                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->label(__('items.tags'))
                    ->multiple()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->color(Color::Yellow)
                    ->iconButton()
                    ->label(__('generic.edit')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
