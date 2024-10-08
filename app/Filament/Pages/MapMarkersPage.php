<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\LeafletMapWidget;

class MapMarkersPage extends Page
{
    public static ?string $navigationIcon = 'heroicon-o-map';
    public static ?string $navigationGroup = 'Maps';
    public static string $view = 'filament.pages.map-markers-page';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getHeaderWidgets(): array
    {
        return [
            LeafletMapWidget::class,
        ];
    }

    public static function getNavigationLabel(): string
{
    return 'Map Markers';
}

public static function getNavigationGroup(): ?string
{
    return 'Leaflet Maps';
}

}
