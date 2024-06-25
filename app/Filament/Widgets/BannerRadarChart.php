<?php
namespace App\Filament\Widgets;

use App\Models\Banner;
use App\Models\BannerCategory;
use Filament\Widgets\RadarChartWidget;

class BannerRadarChart extends RadarChartWidget
{
    protected static ?string $heading = 'Banner Category Metrics';
    protected static ?int $navigationSort = 3;

    protected function getData(): array
    {
        // Fetch all banner categories
        $categories = BannerCategory::all();

        // Example metrics for each category
        $metrics = $categories->map(function ($category) {
            return [
                'name' => $category->name,
                'total_banners' => Banner::where('banner_category_id', $category->id)->count(),
                // Add other metrics here if needed
            ];
        });

        return [
            'labels' => $metrics->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Total Banners',
                    'data' => $metrics->pluck('total_banners')->toArray(),
                    'backgroundColor' => 'rgba(179, 181, 198, 0.2)',
                    'borderColor' => 'rgba(179, 181, 198, 1)',
                    'pointBackgroundColor' => 'rgba(179, 181, 198, 1)',
                    'pointBorderColor' => '#fff',
                    'pointHoverBackgroundColor' => '#fff',
                    'pointHoverBorderColor' => 'rgba(179, 181, 198, 1)',
                ],
                // Add other datasets here if needed
            ],
        ];
    }
}