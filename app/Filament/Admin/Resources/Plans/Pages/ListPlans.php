<?php

namespace App\Filament\Admin\Resources\Plans\Pages;

use App\Filament\Admin\Resources\Plans\PlansResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPlans extends ListRecords
{
    protected static string $resource = PlansResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Buat Paket Baru'),
        ];
    }
}