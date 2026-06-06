<?php

namespace App\Filament\Resources\Ram;

use App\Filament\RelationManagers\UsageRelationManager;
use App\Filament\Resources\Ram\Pages\CreateRam;
use App\Filament\Resources\Ram\Pages\EditRam;
use App\Filament\Resources\Ram\Pages\ListRam;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\Tag;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RamResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string { return 'RAM'; }
    public static function getModelLabel(): string { return 'RAM'; }
    public static function getPluralModelLabel(): string { return 'RAM'; }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('type', fn($q) => $q->where('name', 'RAM'));
    }

    public static function getRelations(): array
    {
        return [UsageRelationManager::class];
    }

    public static function form(Schema $schema): Schema
    {
        $tagColors = Tag::pluck('color', 'name')->all();

        return $schema->components([
            Hidden::make('item_type_id')
                ->default(fn() => ItemType::where('name', 'RAM')->value('id')),

            Section::make('Général')->columns(3)->schema([
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

            Section::make(__('items.tags'))->schema([
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
                    ->createOptionModalHeading(__('generic.create')),
            ]),

            Section::make('RAM')->columns(3)->schema([
                TextInput::make('data.brand')->label('Marque'),
                TextInput::make('data.model')->label('Modèle'),
                TextInput::make('data.capacity')->label('Capacité'),
                Select::make('data.type')
                    ->label('Type')
                    ->options(['DDR' => 'DDR','DDR2' => 'DDR2','DDR3' => 'DDR3', 'DDR4' => 'DDR4', 'DDR5' => 'DDR5', 'LPDDR4' => 'LPDDR4', 'LPDDR5' => 'LPDDR5']),
                TextInput::make('data.speed')->label('Fréquence (MHz)')->integer(),
                Select::make('data.form_factor')
                    ->label('Format')
                    ->options(['DIMM' => 'DIMM', 'SO-DIMM' => 'SO-DIMM']),
            ]),

            Section::make(__('items.notes'))->collapsed()->schema([
                Textarea::make('notes')->label(__('items.notes'))->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        $tagColors = Tag::pluck('color', 'name')->all();

        return $table
            ->columns([
                TextColumn::make('name')->label(__('items.name'))->searchable()->sortable(),
                TextColumn::make('serial')->label(__('items.serial'))->placeholder('—')->searchable()->toggleable(),
                TextColumn::make('data.brand')->label('Marque')->placeholder('—')->toggleable(),
                TextColumn::make('data.capacity')->label('Capacité')->placeholder('—'),
                TextColumn::make('data.type')->label('Type')->placeholder('—')->badge(),
                TextColumn::make('data.speed')->label('MHz')->placeholder('—')->toggleable(),
                TextColumn::make('data.form_factor')->label('Format')->placeholder('—')->toggleable(),
                TextColumn::make('quantity')->label(__('items.quantity'))->sortable()->toggleable(),
                TextColumn::make('tags.name')
                    ->label(__('items.tags'))
                    ->formatStateUsing(fn(string $state): string =>
                        '<span style="background:' . e($tagColors[$state] ?? '#6b7280') . ';color:#fff;padding:0.1rem 0.45rem;border-radius:0.375rem;font-size:0.75rem;font-weight:500;white-space:nowrap">' . e($state) . '</span>'
                    )
                    ->html()
                    ->wrap()
                    ->separator(' '),
            ])
            ->filters([
                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->label(__('items.tags'))
                    ->multiple()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()->iconButton()->label(__('generic.edit')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListRam::route('/'),
            'create' => CreateRam::route('/create'),
            'edit'   => EditRam::route('/{record}/edit'),
        ];
    }
}
