<?php

namespace App\Filament\Admin\Resources\Subscriptions;

use App\Filament\Admin\Resources\Subscriptions\Pages\CreateSubscription;
use App\Filament\Admin\Resources\Subscriptions\Pages\EditSubscription;
use App\Filament\Admin\Resources\Subscriptions\Pages\ListSubscriptions;
use App\Filament\Admin\Resources\Subscriptions\Schemas\SubscriptionForm;
use App\Filament\Admin\Resources\Subscriptions\Tables\SubscriptionsTable;
use App\Models\Subscription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | \UnitEnum | null $navigationGroup = 'SaaS Management';

    protected static ?string $recordTitleAttribute = 'Subscription';

    public static function form(Schema $schema): Schema
    {
        return SubscriptionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        // PASTIKAN BARIS INI MEMANGGIL CLASS YANG BARU KITA EDIT
        return SubscriptionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getLabel(): string
    {
        return 'Langganan';
    }

    public static function getPluralLabel(): string
    {
        return 'Langganan';
    }

    // Untuk mematikan fitur Create
    public static function canCreate(): bool
    {
        return false;
    }

    // Untuk mematikan fitur Edit (update)
    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSubscriptions::route('/')
        ];
    }
}