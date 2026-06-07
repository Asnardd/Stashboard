<?php

namespace App\Providers;

use Filament\Forms\Components\Concerns\CanBeNative;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Column::configureUsing(function (Column $column) {
            $column->searchable();
            $column->sortable();
            if ($column instanceof TextColumn) {
                $column->wrap();
            }
        });

        Field::configureUsing(function (Field $field) {
            $field->translateLabel();
            if (in_array(CanBeNative::class,class_uses_recursive($field))){
                $field->native(false);
            }
        });

        TextInput::configureUsing(function (TextInput $textInput): void {
            $textInput
                ->dehydrateStateUsing(function (?string $state): ?string {
                    return is_string($state) ? trim($state) : $state;
                });
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
