<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\LeafletHeatMapWidget;

class LeafletHeatMapPage extends Page
{
    public static ?string $navigationIcon = 'heroicon-o-map';
    public static ?string $navigationGroup = 'Maps';
    public static string $view = 'filament.pages.leaflet-heat-map-page';

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
