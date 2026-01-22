<?php

namespace App\Filament\Admin\Resources\Plans\Schemas;

use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlansForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('plan_name')
                    ->label('Plan Name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('price')
                    ->label('Plan Price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp '),
                TextInput::make('duration_days')
                    ->label('Plan Duration (Days)')
                    ->numeric(),
                Toggle::make('is_popular')
                    ->label('Tandai sebagai Paket Populer')
                    ->inline(false)
                    ->default(false),

                TextInput::make('badge')
                    ->label('Label Badge')
                    ->placeholder('Contoh: Populer 🔥 atau Hemat 2 Bulan 💎')
                    ->maxLength(255)
                    ->helperText('Opsional — tampil di kartu paket di halaman pricing.'),
                TagsInput::make('features')
                    ->label('Plan Features')
                    ->placeholder('Tekan Enter setelah menulis fitur')
                    ->helperText('Pisahkan tiap fitur dengan Enter')
                    ->required(),
                Textarea::make('description')
                    ->label('Plan Description')
                    ->columnSpanFull(),
            ]);
    }
}