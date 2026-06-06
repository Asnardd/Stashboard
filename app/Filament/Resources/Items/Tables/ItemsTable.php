<?php

namespace App\Filament\Resources\Items\Tables;

use App\Models\Tag;
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
        $tagColors = Tag::pluck('color', 'name')->all();

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
                    ->formatStateUsing(fn(string $state): string =>
                        '<span style="background:' . e($tagColors[$state] ?? '#6b7280') . ';color:#fff;padding:0.1rem 0.45rem;border-radius:0.375rem;font-size:0.75rem;font-weight:500;white-space:nowrap">' . e($state) . '</span>'
                    )
                    ->html()
                    ->wrap()
                    ->separator(' '),

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
