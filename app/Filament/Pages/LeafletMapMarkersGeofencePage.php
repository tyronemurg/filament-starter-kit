<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\LeafletMapMarkerGeofenceWidget;

class LeafletMapMarkersGeofencePage extends Page
{
    public static ?string $navigationIcon = 'heroicon-o-map';
    public static ?string $navigationGroup = 'Maps';
    public static string $view = 'filament.pages.leaflet-map-markers-geofence-page';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getHeaderWidgets(): array
    {
        return [
            LeafletMapMarkerGeofenceWidget::class,
        ];
    }

    public static function getNavigationLabel(): string
{
    return 'Geofencing';
}

public static function getNavigationGroup(): ?string
{
    return 'Leaflet Maps';
}


}
