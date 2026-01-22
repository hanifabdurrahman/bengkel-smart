<?php

namespace App\Filament\Admin\Resources\Subscriptions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class SubscriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('workshop_id')
                    ->relationship('workshop', 'workshop_name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Bengkel'),

                Select::make('plan_id')
                    ->relationship('plan', 'plan_name')
                    ->required()
                    ->label('Paket'),

                DatePicker::make('date_start')
                    ->label('Mulai')
                    ->required(),

                DatePicker::make('date_end')
                    ->label('Berakhir')
                    ->required(),

                Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'pending' => 'Pending',
                        'expired' => 'Expired',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
            ]);
    }
}
