<?php

namespace App\Filament\Resources\Items\Schemas;

use App\Models\Tag;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('items.name'))
                            ->required()
                            ->maxLength(255),

                        Select::make('item_type_id')
                            ->label(__('items.type'))
                            ->relationship('type', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('icon')
                                    ->nullable(),
                                Toggle::make('active')
                                    ->default(true),
                            ]),

                        TextInput::make('serial')
                            ->label(__('items.serial'))
                            ->maxLength(255),

                        TextInput::make('quantity')
                            ->label(__('items.quantity'))
                            ->integer()
                            ->default(1)
                            ->required()
                            ->minValue(1),
                    ]),

                Section::make(__('items.tags'))
                    ->schema([
                        Select::make('tags')
                            ->label(__('items.tags'))
                            ->relationship('tags', 'name')
                            ->getOptionLabelFromRecordUsing(fn(Tag $record) =>
                                "<span style='display:inline-flex;align-items:center;gap:0.4rem'>" .
                                "<span style='width:0.55rem;height:0.55rem;border-radius:50%;background:" . e($record->color) . ";flex-shrink:0'></span>" .
                                e($record->name) .
                                "</span>"
                            )
                            ->allowHtml()
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                Grid::make(2)->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),
                                    ColorPicker::make('color')
                                        ->default('#ff6467'),
                                ]),
                            ])
                            ->createOptionModalHeading(__('generic.create'))
                            ->columnSpanFull(),
                    ]),

                Section::make(__('items.notes'))
                    ->schema([
                        Textarea::make('notes')
                            ->label(__('items.notes'))
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),

                Section::make(__('items.additional-data'))
                    ->description(__('items.additional-data-desc'))
                    ->schema([
                        KeyValue::make('data')
                            ->label('')
                            ->keyLabel(__('generic.attribute'))
                            ->valueLabel(__('generic.value'))
                            ->addActionLabel(__('generic.add-row'))
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
