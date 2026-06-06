<?php

namespace App\Filament\Resources\Disk\Pages;

use App\Filament\Resources\Disk\DiskResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDisk extends EditRecord
{
    protected static string $resource = DiskResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
