<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Product;
use App\Models\Blog\Post;
use Illuminate\Support\Facades\Auth;

class OverallStatsWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    public function getHeading(): string
    {
        return 'Dashboard Statistics';
    }

    protected function getStats(): array
    {
        // Get the counts from the respective models
        $userCount = User::count();
        $productCount = Product::count();
        $postCount = Post::count();

        // Generate random chart data for demonstration purposes
        $chartData = collect(range(1, 7))->map(function () {
            return random_int(1, 20);
        });

        return [
            Stat::make('Total Users', $userCount)
                ->description('Total number of users')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart($chartData->toArray())
                ->color('success'),
            Stat::make('Total Products', $productCount)
                ->description('Total number of products')
                ->descriptionIcon('heroicon-m-cube')
                ->chart($chartData->toArray())
                ->color('primary'),
            Stat::make('Total Posts', $postCount)
                ->description('Total number of posts')
                ->descriptionIcon('heroicon-m-document-text')
                ->chart($chartData->toArray()) // You can use different chart data if needed
                ->color('danger')
        ];
    }

    public function getColumnSpan(): int | string | array
    {
        return 2; // Assuming 2 columns layout, span across both columns
    }
}
