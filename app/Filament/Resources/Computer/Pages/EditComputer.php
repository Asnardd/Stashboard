<?php

namespace App\Filament\Resources\Computer\Pages;

use App\Filament\Resources\Computer\ComputerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditComputer extends EditRecord
{
    protected static string $resource = ComputerResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
