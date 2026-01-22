<?php

namespace App\Filament\Admin\Resources\Workshops\Pages;

use App\Filament\Admin\Resources\Workshops\WorkshopsResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkshops extends EditRecord
{
    protected static string $resource = WorkshopsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('Hapus Bengkel'),
        ];
    }
}