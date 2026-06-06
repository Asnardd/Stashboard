<?php

namespace App\Filament\Resources\Gpu;

use App\Filament\RelationManagers\UsageRelationManager;
use App\Filament\Resources\Gpu\Pages\CreateGpu;
use App\Filament\Resources\Gpu\Pages\EditGpu;
use App\Filament\Resources\Gpu\Pages\ListGpu;
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

class GpuResource extends Resource
{
    protected static ?string $model = Item::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string { return 'GPU'; }
    public static function getModelLabel(): string { return 'GPU'; }
    public static function getPluralModelLabel(): string { return 'GPU'; }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('type', fn($q) => $q->where('name', 'GPU'));
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
                ->default(fn() => ItemType::where('name', 'GPU')->value('id')),

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

            Section::make('GPU')->columns(3)->schema([
                TextInput::make('data.brand')->label('Marque'),
                TextInput::make('data.model')->label('Modèle'),
                TextInput::make('data.vram')->label('VRAM'),
                Select::make('data.interface')
                    ->label('Interface')
                    ->options(['PCIe 5.0 x16' => 'PCIe 5.0 x16', 'PCIe 4.0 x16' => 'PCIe 4.0 x16', 'PCIe 3.0 x16' => 'PCIe 3.0 x16', 'PCIe 3.0 x8' => 'PCIe 3.0 x8']),
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
                TextColumn::make('data.vram')->label('VRAM')->placeholder('—'),
                TextColumn::make('data.interface')->label('Interface')->placeholder('—')->badge()->toggleable(),
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
            'index'  => ListGpu::route('/'),
            'create' => CreateGpu::route('/create'),
            'edit'   => EditGpu::route('/{record}/edit'),
        ];
    }
}
