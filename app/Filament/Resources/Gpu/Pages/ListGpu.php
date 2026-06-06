<?php

namespace App\Filament\Resources\Gpu\Pages;

use App\Filament\Resources\Gpu\GpuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListGpu extends ListRecords
{
    protected static string $resource = GpuResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
