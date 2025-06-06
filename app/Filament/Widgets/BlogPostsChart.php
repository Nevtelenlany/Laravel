<?php

namespace App\Filament\Widgets;

use App\Models\SparePart;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BlogPostsChart extends ApexChartWidget
{
    protected static ?string $chartId = 'sparePartStockChart';

    protected static ?string $heading = null;

    public function getHeading(): ?string
    {
        return __('module_names.widgets.spare_part_stock');
    }

    protected function getOptions(): array
    {
        $spareParts = SparePart::all();
        $data = $spareParts->pluck('stock_quantity')->map(fn($q) => (int) $q)->toArray();

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 350,
            ],
            'series' => [
                [
                    'name' => __('module_names.fields.stock_quantity'),
                    'data' => $data,
                ],
            ],
            'xaxis' => [
                'categories' => $spareParts->pluck('name')->toArray(),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
        ];
    }
}
