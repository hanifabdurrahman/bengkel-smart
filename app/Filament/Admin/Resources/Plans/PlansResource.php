<?php

namespace App\Filament\Admin\Resources\Plans;

use App\Filament\Admin\Resources\Plans\Pages\CreatePlans;
use App\Filament\Admin\Resources\Plans\Pages\EditPlans;
use App\Filament\Admin\Resources\Plans\Pages\ListPlans;
use App\Filament\Admin\Resources\Plans\Schemas\PlansForm;
use App\Filament\Admin\Resources\Plans\Tables\PlansTable;
use App\Models\Plan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PlansResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | \UnitEnum | null $navigationGroup = 'SaaS Management';


    protected static ?string $recordTitleAttribute = 'plan_name';
    public static function form(Schema $schema): Schema
    {
        return PlansForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PlansTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getLabel(): string
    {
        return 'Paket';
    }

    public static function getPluralLabel(): string
    {
        return 'Paket Langganan';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlans::route('/'),
            'create' => CreatePlans::route('/create'),
            'edit' => EditPlans::route('/{record}/edit'),
        ];
    }
}