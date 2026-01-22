<?php

namespace App\Filament\Admin\Resources\Subscriptions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class SubscriptionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('workshop.workshop_name')
                    ->label('Bengkel')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('plan.plan_name')
                    ->label('Paket')
                    ->badge()
                    ->color('info'),

                TextColumn::make('date_start')
                    ->label('Dimulai Pada')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('date_end')
                    ->label('Berakhir Pada')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn(string $state) => match ($state) {
                        'active'    => 'Aktif',
                        'pending'   => 'Menunggu',
                        'expired'   => 'Kedaluwarsa',
                        'cancelled' => 'Dinonaktifkan',
                        default     => ucfirst($state),
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'active'    => 'success',
                        'pending'   => 'warning',
                        'expired'   => 'secondary',
                        'cancelled' => 'danger',
                    }),
            ])

            ->filters([
                //
                SelectFilter::make('status')
                    ->label('Status Langganan')
                    // Gunakan attribute untuk menunjuk nama tabel spesifik
                    // Ini menggantikan fungsi ->query() manual Anda
                    ->attribute('subscriptions.status')
                    ->options([
                        'active'    => 'Aktif',
                        'expired'   => 'Kedaluwarsa',
                        'cancelled' => 'Dinonaktifkan',
                        'pending'   => 'Menunggu',
                    ]),

            ])
            ->recordActions([
                ViewAction::make()->label('Detail'),
                Action::make('cancel')
                    ->label('Nonaktifkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn($record) => $record->status === 'active')
                    ->action(fn($record) => $record->update([
                        'status' => 'cancelled',
                        'date_end' => now(),
                    ])),
            ])
            ->toolbarActions([]);
    }
}