<?php

namespace App\Filament\Admin\Resources\Workshops\Pages;

use App\Filament\Admin\Resources\Workshops\WorkshopsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWorkshops extends ListRecords
{
    protected static string $resource = WorkshopsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}