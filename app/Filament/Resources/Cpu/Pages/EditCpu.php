<?php

namespace App\Filament\Resources\Cpu\Pages;

use App\Filament\Resources\Cpu\CpuResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCpu extends EditRecord
{
    protected static string $resource = CpuResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
