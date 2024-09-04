<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\LeafletMapMarkerWidget;

class LeafletMapMarkersPage extends Page
{
    public static ?string $navigationIcon = 'heroicon-o-map';
    public static ?string $navigationGroup = 'Maps';
    public static string $view = 'filament.pages.map-markers-page';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getHeaderWidgets(): array
    {
        return [
            LeafletMapMarkerWidget::class,
        ];
    }

    public static function getNavigationLabel(): string
{
    return 'Markers';
}

public static function getNavigationGroup(): ?string
{
    return 'Leaflet Maps';
}

}
