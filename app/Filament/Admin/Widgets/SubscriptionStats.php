<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\Subscriptions\SubscriptionResource;
use App\Models\Subscription;
use App\Models\Workshop;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class SubscriptionStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $stats = Cache::remember(
            'admin.subscription.stats',
            now()->addMinutes(5),
            function () {
                return [
                    'active' => Subscription::where('status', 'active')->count(),
                    'expired' => Subscription::where('status', 'expired')->count(),
                    'cancelled' => Subscription::where('status', 'cancelled')->count(),
                    'workshops' => Workshop::count(),
                ];
            }
        );

        return [
            Stat::make('Langganan Aktif', $stats['active'])
                ->description('Bengkel dengan langganan aktif')
                ->color('success')
                // Pastikan strukturnya: tableFilters -> nama_filter -> value -> nilai
                ->url(SubscriptionResource::getUrl('index', [
                    'tableFilters' => [
                        'status' => [
                            'value' => 'active',
                        ],
                    ],
                ])),

            Stat::make('Langganan Kedaluwarsa', $stats['expired'])
                ->description('Langganan habis masa berlaku')
                ->color('warning')
                ->url(SubscriptionResource::getUrl('index', [
                    'tableFilters' => [
                        'status' => [
                            'value' => 'expired',
                        ],
                    ],
                ])),

            Stat::make('Langganan Dinonaktifkan', $stats['cancelled'])
                ->description('Langganan dinonaktifkan manual')
                ->color('danger')
                ->url(SubscriptionResource::getUrl('index', [
                    'tableFilters' => [
                        'status' => [
                            'value' => 'cancelled',
                        ],
                    ],
                ])),

            Stat::make('Total Bengkel', $stats['workshops'])
                ->description('Total bengkel terdaftar')
                ->color('info')
                ->url(route('filament.admin.resources.workshops.index')),
        ];
    }
}