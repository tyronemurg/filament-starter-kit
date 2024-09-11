<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\LeafletHeatMapWidget;

class LeafletHeatMapPage extends Page
{
    public static ?string $navigationIcon = 'heroicon-o-map';
    public static ?string $navigationGroup = 'Maps';
    public static string $view = 'filament.pages.leaflet-heat-map-page';

    // This is the method you override to customize the URL
    public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null): string
    {
        // Set a custom URL, for example, /custom-heatmap-url
        return url('/heatmap');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getHeaderWidgets(): array
    {
        return [
            LeafletHeatMapWidget::class,
        ];
    }

    public static function getNavigationLabel(): string
{
    return 'Heatmaps';
}

public static function getNavigationGroup(): ?string
{
    return 'Leaflet Maps';
}
}
