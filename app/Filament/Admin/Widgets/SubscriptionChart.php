<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Plan;
use App\Models\Subscription;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class SubscriptionChart extends ChartWidget
{
    protected ?string $heading = 'Langganan Paket per Bulan';

    protected static bool $isLazy = true;
    protected function getPollingInterval(): ?string
    {
        return null;
    }

    protected function getData(): array
    {
        return Cache::remember(
            'admin:subscription:chart:' . now()->year,
            now()->addMinutes(15),
            function () {
                return $this->buildChartData();
            }
        );
    }

    protected function buildChartData(): array
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        $plans = Plan::select('plan_id', 'plan_name')->get();

        // 1 QUERY SAJA
        $subscriptions = Subscription::selectRaw('
                plan_id,
                MONTH(created_at) as month,
                COUNT(*) as total
            ')
            ->whereYear('created_at', now()->year)
            ->groupBy('plan_id', 'month')
            ->get()
            ->groupBy('plan_id');

        $datasets = [];

        foreach ($plans as $plan) {
            $monthlyData = array_fill(0, 12, 0);

            foreach ($subscriptions->get($plan->plan_id, []) as $row) {
                $monthlyData[$row->month - 1] = $row->total;
            }

            $datasets[] = [
                'label'   => $plan->plan_name,
                'data'    => $monthlyData,
                'fill'    => true,
                'tension' => 0.4,
            ];
        }

        return [
            'datasets' => $datasets,
            'labels'   => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}