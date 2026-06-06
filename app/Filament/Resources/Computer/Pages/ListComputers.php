<?php

namespace App\Filament\Resources\Computer\Pages;

use App\Filament\Resources\Computer\ComputerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComputers extends ListRecords
{
    protected static string $resource = ComputerResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
