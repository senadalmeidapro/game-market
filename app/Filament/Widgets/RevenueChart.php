<?php

namespace App\Filament\Widgets;

use App\Models\Commerce\Order;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Chiffre d’affaires';

    public ?string $filter = '30d';

    protected function getFilters(): ?array
    {
        return ['7d' => '7 derniers jours', '30d' => '30 derniers jours', '90d' => '90 derniers jours'];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $days = match ($this->filter) {
            '7d' => 7, '90d' => 90, default => 30
        };
        $start = now()->subDays($days - 1)->startOfDay();
        $rows = Order::query()
            ->where('status', 'paid')
            ->where('paid_at', '>=', $start)
            ->selectRaw('DATE(paid_at) as day, SUM(total_amount) as amount')
            ->groupByRaw('DATE(paid_at)')
            ->orderBy('day')
            ->pluck('amount', 'day');

        $labels = [];
        $values = [];
        for ($offset = $days - 1; $offset >= 0; $offset--) {
            $day = now()->subDays($offset)->toDateString();
            $labels[] = now()->subDays($offset)->format('d/m');
            $values[] = (int) ($rows[$day] ?? 0);
        }

        return [
            'datasets' => [['label' => 'XOF encaissés', 'data' => $values, 'fill' => 'start']],
            'labels' => $labels,
        ];
    }
}
