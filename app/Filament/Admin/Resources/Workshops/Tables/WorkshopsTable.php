<?php

namespace App\Filament\Admin\Resources\Workshops\Tables;


use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WorkshopsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workshop_name')
                    ->label('Nama Bengkel')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('phone_number')
                    ->label('Kontak'),

                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->label('Terdaftar Sejak'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()->label('Detail'),
            ])
            ->toolbarActions([]);
    }
}
