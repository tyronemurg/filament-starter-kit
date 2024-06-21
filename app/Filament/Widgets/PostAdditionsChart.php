<?php

namespace App\Filament\Widgets;

use App\Models\Blog\Post;
use Filament\Widgets\LineChartWidget;

class PostAdditionsChart extends LineChartWidget
{
    protected static ?string $heading = 'Post Additions Over Time';

    protected function getData(): array
    {
        // Query the number of posts created per month
        $posts = Post::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare the data for the chart
        $labels = $posts->pluck('month')->toArray();
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
