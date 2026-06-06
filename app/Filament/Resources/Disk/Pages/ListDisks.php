<?php

namespace App\Filament\Resources\Disk\Pages;

use App\Filament\Resources\Disk\DiskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDisks extends ListRecords
{
    protected static string $resource = DiskResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
