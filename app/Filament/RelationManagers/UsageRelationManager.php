<?php

namespace App\Filament\RelationManagers;

use App\Models\Item;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsageRelationManager extends RelationManager
{
    // Points to the Item::usages() HasMany relationship (item_id = this item)
    protected static string $relationship = 'usages';

    protected static ?string $title = 'Installé dans';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            // Pick which item this is installed in
            Select::make('used_in_id')
                ->label('Appareil hôte')
                ->options(fn() => Item::orderBy('name')->pluck('name', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('note')
                ->label('Note')
                ->placeholder('ex. Slot 1, baie inférieure...')
                ->maxLength(255),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                // Reads the name of the host item via ItemUsage::host() (used_in_id → items)
                TextColumn::make('host.name')
                    ->label('Appareil'),

                TextColumn::make('note')
                    ->label('Note')
                    ->placeholder('—'),

                TextColumn::make('created_at')
                    ->label('Depuis')
                    ->date('d/m/Y'),
            ])
            ->headerActions([
                // Creates a new ItemUsage row — effectively attaching this item to a host
                CreateAction::make()->label('Attacher'),
            ])
            ->recordActions([
                // Soft-deletes the ItemUsage row — detaches without losing history
                DeleteAction::make()->label('Détacher'),
            ]);
    }
}
