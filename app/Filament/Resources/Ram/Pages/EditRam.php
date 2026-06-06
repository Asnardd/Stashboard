<?php

namespace App\Filament\Resources\Ram\Pages;

use App\Filament\Resources\Ram\RamResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRam extends EditRecord
{
    protected static string $resource = RamResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
