<?php

namespace App\Filament\Resources\ItemTypes\Schemas;

use App\Models\ItemType;
use App\Models\Tag;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ItemTypeForm
{
    // Fields of an item type are stored as a json in the fields column as {key:"",label:"",type:""}
    public static function configure(Schema $schema, ItemType $type): Schema
    {
        $dataFields = array_map(fn($field) => match ($field['type']) {

            'integer' => TextInput::make("data.{$field['key']}")
                ->label($field['label'])
                ->integer(),

            'select'  => Select::make("data.{$field['key']}")
                ->label($field['label'])
                ->options(array_combine($field['options'], $field['options'])),

            default   => TextInput::make("data.{$field['key']}")
                ->label($field['label']),

        }, $type->fields ?? []);

        return $schema->components([

            Hidden::make('item_type_id')
                ->default($type->id),

            Group::make([
                Section::make('General')
                    ->columns()
                    ->schema([

                        TextInput::make('name')
                            ->label(__('items.name'))
                            ->required()
                            ->maxLength(255),

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
                                    TextInput::make('name')->required()->maxLength(255),
                                    ColorPicker::make('color')->default('#ff6467'),
                                ]),
                            ])
                            ->createOptionModalHeading(__('generic.create'))
                            ->columnSpanFull(),
                    ]),
            ]),

            Section::make($type->name)
                ->columns(2)
                ->schema($dataFields)
                ->visible(fn() => !empty($dataFields)),



            Section::make(__('items.notes'))
                ->columnSpanFull()
                ->schema([
                    Textarea::make('notes')
                        ->label(__('items.notes'))
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->collapsed(),
        ]);
    }
}
