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

        $visibleBanners = Banner::selectRaw('banner_category_id, COUNT(*) as total_visible_banners')
            ->where('is_visible', true)
            ->groupBy('banner_category_id')
            ->get();

        $startedBanners = Banner::selectRaw('banner_category_id, COUNT(*) as total_started_banners')
            ->whereNotNull('start_date')
            ->groupBy('banner_category_id')
            ->get();

        // Assuming `banner_category_id` is the same for both queries
        $categories = $visibleBanners->pluck('banner_category_id')->toArray();

        return [
            'labels' => $metrics->pluck('name')->toArray(),
            'datasets' => [
                [
                    'label' => 'Total Banners',
                    'data' => $metrics->pluck('total_banners')->toArray(),
                    'backgroundColor' => '#63ff9487',
                    'borderColor' => 'green',
                    'pointBackgroundColor' => '#63ff9487',
                    'pointBorderColor' => 'green',
                    'pointHoverBackgroundColor' => 'green',
                    'pointHoverBorderColor' => '#63ff9487',
                ],
                [
                    'label' => 'Visible Banners',
                    'data' => $visibleBanners->pluck('total_visible_banners')->toArray(),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'pointBackgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'pointBorderColor' => 'rgba(54, 162, 235, 1)',
                    'pointHoverBackgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'pointHoverBorderColor' => 'rgba(54, 162, 235, 1)',
                ],
                [
                    'label' => 'Started Banners',
                    'data' => $startedBanners->pluck('total_started_banners')->toArray(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'pointBackgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'pointBorderColor' => 'rgba(255, 99, 132, 1)',
                    'pointHoverBackgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'pointHoverBorderColor' => 'rgba(255, 99, 132, 1)',
                ],
                // Add other datasets here if needed
            ],
        ];
    }
}