<?php

namespace Filament\Pages;

use Filament\Facades\Filament;
use Filament\Support\Facades\FilamentIcon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\Widgets\ProductStockChart;
use App\Filament\Widgets\BannerRadarChart;
use App\Filament\Widgets\CategoryBarChart;
use App\Filament\Widgets\PostAdditionsChart;
use App\Filament\Widgets\OverallStatsWidget;
use App\Filament\Widgets\LeafletMapWidget;

class Dashboard extends Page
{
    protected static string $routePath = '/';

    protected static ?int $navigationSort = -2;

    /**
     * @var view-string
     */
    protected static string $view = 'filament-panels::pages.dashboard';

    public static function getNavigationLabel(): string
    {
        return static::$navigationLabel ??
            static::$title ??
            __('filament-panels::pages/dashboard.title');
    }

    public static function getNavigationIcon(): string | Htmlable | null
    {
        return static::$navigationIcon
            ?? FilamentIcon::resolve('panels::pages.dashboard.navigation-item')
            ?? (Filament::hasTopNavigation() ? 'heroicon-m-home' : 'heroicon-o-home');
    }

    public static function getRoutePath(): string
    {
        return static::$routePath;
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        $widgets = Filament::getWidgets();

        // Specify the desired order of widgets
        $orderedWidgets = [
            OverallStatsWidget::class,
            PostAdditionsChart::class,
            CategoryBarChart::class,
            ProductStockChart::class,
            BannerRadarChart::class,
            LeafletMapWidget::class,
            // Include other widgets as needed...
        ];

        // Merge with the remaining widgets to ensure all widgets are included
        $remainingWidgets = array_diff($widgets, $orderedWidgets);
        $finalWidgets = array_merge($remainingWidgets, $orderedWidgets);

        return $finalWidgets;
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    /**
     * @return int | string | array<string, int | string | null>
     */
    public function getColumns(): int | string | array
    {
        return 2;
    }

    public function getTitle(): string | Htmlable
    {
        return static::$title ?? __('filament-panels::pages/dashboard.title');
    }
}
