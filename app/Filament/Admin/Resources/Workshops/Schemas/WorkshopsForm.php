<?php

namespace App\Filament\Admin\Resources\Workshops\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WorkshopsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('workshop_name')
                    ->label('Nama Bengkel')
                    ->required(),

                TextInput::make('email')
                    ->email()
                    ->required(),

                TextInput::make('phone_number')
                    ->label('No. Telepon'),

                Textarea::make('address')
                    ->label('Alamat')
                    ->columnSpanFull(),
            ]);
    }
}
