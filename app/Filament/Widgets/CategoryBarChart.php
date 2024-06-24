<?php

namespace App\Filament\Widgets;

use App\Models\Blog\Category;
use Filament\Widgets\BarChartWidget;

class CategoryBarChart extends BarChartWidget
{
    protected static ?string $heading = 'Categories Bar Chart';

    protected function getData(): array
    {
        $categories = Category::withCount('posts')->get();

        $labels = $categories->pluck('name')->toArray();
        $data = $categories->pluck('posts_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Number of Posts',
                    'data' => $data,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
