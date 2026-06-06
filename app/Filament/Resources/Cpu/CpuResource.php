<?php

namespace App\Filament\Resources\Cpu;

use App\Filament\RelationManagers\UsageRelationManager;
use App\Filament\Resources\Cpu\Pages\CreateCpu;
use App\Filament\Resources\Cpu\Pages\EditCpu;
use App\Filament\Resources\Cpu\Pages\ListCpu;
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

class CpuResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCpuChip;

    protected static ?int $navigationSort = 5;

    public static function getNavigationLabel(): string { return 'CPU'; }
    public static function getModelLabel(): string { return 'CPU'; }
    public static function getPluralModelLabel(): string { return 'CPU'; }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('type', fn($q) => $q->where('name', 'CPU'));
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
                ->default(fn() => ItemType::where('name', 'CPU')->value('id')),

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

            Section::make('CPU')->columns(3)->schema([
                TextInput::make('data.brand')->label('Marque'),
                TextInput::make('data.model')->label('Modèle'),
                Select::make('data.socket')
                    ->label('Socket')
                    ->options([
                        'AM4'     => 'AM4',
                        'AM5'     => 'AM5',
                        'LGA1700' => 'LGA1700',
                        'LGA1200' => 'LGA1200',
                        'LGA1151' => 'LGA1151',
                        'LGA2066' => 'LGA2066',
                        'TR4'     => 'TR4',
                        'sTRX4'   => 'sTRX4',
                    ]),
                TextInput::make('data.cores')->label('Cœurs')->integer(),
                TextInput::make('data.threads')->label('Threads')->integer(),
                TextInput::make('data.base_clock')->label('Fréquence base')->placeholder('3.6 GHz'),
                TextInput::make('data.boost_clock')->label('Fréquence boost')->placeholder('5.0 GHz'),
                TextInput::make('data.tdp')->label('TDP (W)')->integer(),
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
                TextColumn::make('data.model')->label('Modèle')->placeholder('—'),
                TextColumn::make('data.socket')->label('Socket')->placeholder('—')->badge(),
                TextColumn::make('data.cores')->label('Cœurs')->placeholder('—')->toggleable(),
                TextColumn::make('data.base_clock')->label('Base')->placeholder('—')->toggleable(),
                TextColumn::make('data.boost_clock')->label('Boost')->placeholder('—')->toggleable(),
                TextColumn::make('data.tdp')->label('TDP (W)')->placeholder('—')->toggleable(),
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
            'index'  => ListCpu::route('/'),
            'create' => CreateCpu::route('/create'),
            'edit'   => EditCpu::route('/{record}/edit'),
        ];
    }
}
