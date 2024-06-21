<?php

namespace App\Filament\Widgets;

use App\Models\Blog\Post;
use Filament\Widgets\LineChartWidget;

class PostAdditionsChart extends LineChartWidget
{
    protected static ?string $heading = 'Post Additions Per Day';

    protected function getData(): array
    {
        // Query the number of posts created per day
        $posts = Post::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare the data for the chart
        $labels = $posts->pluck('date')->toArray();
        $data = $posts->pluck('count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Posts Added',
                    'data' => $data,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
