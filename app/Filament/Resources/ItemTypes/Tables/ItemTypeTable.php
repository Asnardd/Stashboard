<?php

namespace App\Filament\Resources\ItemTypes\Tables;

use App\Models\ItemType;
use App\Models\Tag;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ItemTypeTable
{
    public static function configure(Table $table, ItemType $type): Table
    {
        $tagColors = Tag::pluck('color', 'name')->all();

        $columns = [
            TextColumn::make('name')
                ->label(__('items.name'))
                ->searchable()
                ->sortable(),

            TextColumn::make('serial')
                ->label(__('items.serial'))
                ->placeholder('—')
                ->searchable()
                ->toggleable(),
        ];

        foreach ($type->fields ?? [] as $field) {
            $columns[] = TextColumn::make("data.{$field['key']}")
                ->label($field['label'])
                ->placeholder('—')
                ->toggleable();
        }

        $columns[] = TextColumn::make('quantity')
            ->label(__('items.quantity'))
            ->sortable()
            ->toggleable();

        $columns[] = TextColumn::make('tags.name')
            ->label(__('items.tags'))
            ->formatStateUsing(fn(string $state): string =>
                '<span style="background:' . e($tagColors[$state] ?? '#6b7280') . ';color:#fff;padding:0.1rem 0.45rem;border-radius:0.375rem;font-size:0.75rem;font-weight:500;white-space:nowrap">' . e($state) . '</span>'
            )
            ->html()
            ->wrap()
            ->separator(' ');

        return $table
            ->columns($columns)
            ->filters([
                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->label(__('items.tags'))
                    ->multiple()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->label(__('generic.edit'))
                    ->iconButton(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
