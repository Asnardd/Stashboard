<?php

namespace App\Filament\Resources\Ram\Pages;

use App\Filament\Resources\Ram\RamResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRam extends ListRecords
{
    protected static string $resource = RamResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
