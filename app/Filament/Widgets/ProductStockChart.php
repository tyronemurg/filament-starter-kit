<?php
namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\PieChartWidget;

class ProductStockChart extends PieChartWidget
{
    protected static ?string $heading = 'Product Stock';

    protected function getData(): array
    {
        $products = Product::all();

        return [
            'labels' => $products->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Stock',
                    'data' => $products->pluck('stock')->toArray(),
                    'backgroundColor' => [
                        '#63ff94',
                        '#FF6384',
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                    ],
                ],
            ],
        ];
    }
}
