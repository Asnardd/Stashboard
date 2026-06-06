<?php

namespace App\Filament\Resources\Gpu\Pages;

use App\Filament\Resources\Gpu\GpuResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGpu extends EditRecord
{
    protected static string $resource = GpuResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
