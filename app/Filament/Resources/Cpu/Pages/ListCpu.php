<?php

namespace App\Filament\Resources\Cpu\Pages;

use App\Filament\Resources\Cpu\CpuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCpu extends ListRecords
{
    protected static string $resource = CpuResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
