<?php

namespace App\Filament\Resources\ItemTypes\RelationManagers;

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
    protected static string $relationship = 'usages';

    protected static ?string $title = 'Installé dans';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
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
                CreateAction::make()->label('Attacher'),
            ])
            ->recordActions([
                DeleteAction::make()->label('Détacher'),
            ]);
    }
}
