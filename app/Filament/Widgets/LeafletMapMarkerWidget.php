<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webbingbrasil\FilamentMaps\Actions;
use Webbingbrasil\FilamentMaps\Marker;
use Webbingbrasil\FilamentMaps\Polyline;
use Webbingbrasil\FilamentMaps\Widgets\MapWidget;

class LeafletMapMarkerWidget extends MapWidget
{
    protected int | string | array $columnSpan = 2;

    protected bool $hasBorder = false;

    protected static bool $isLazy = false;

    public function getMarkers(): array
    {
        // Hardcoded locations with markers
        return [
            Marker::make('New York')->lat(40.7128)->lng(-74.0060)->popup('New York'),
            Marker::make('Los Angeles')->lat(34.0522)->lng(-118.2437)->popup('Los Angeles'),
            Marker::make('Chicago')->lat(41.8781)->lng(-87.6298)->popup('Chicago'),
            Marker::make('San Francisco')->lat(37.7749)->lng(-122.4194)->popup('San Francisco'),
            Marker::make('Miami')->lat(25.7617)->lng(-80.1918)->popup('Miami'),
            Marker::make('Houston')->lat(29.7604)->lng(-95.3698)->popup('Houston'),
            Marker::make('Seattle')->lat(47.6062)->lng(-122.3321)->popup('Seattle'),
            Marker::make('Denver')->lat(39.7392)->lng(-104.9903)->popup('Denver'),
            Marker::make('Boston')->lat(42.3601)->lng(-71.0589)->popup('Boston'),
            Marker::make('Atlanta')->lat(33.7490)->lng(-84.3880)->popup('Atlanta'),
            Marker::make('London')->lat(51.5074)->lng(-0.1278)->popup('London'),
            Marker::make('Paris')->lat(48.8566)->lng(2.3522)->popup('Paris'),
            Marker::make('Berlin')->lat(52.5200)->lng(13.4050)->popup('Berlin'),
            Marker::make('Sydney')->lat(-33.8688)->lng(151.2093)->popup('Sydney'),
            Marker::make('Tokyo')->lat(35.6895)->lng(139.6917)->popup('Tokyo'),
            Marker::make('Dubai')->lat(25.276987)->lng(55.296249)->popup('Dubai'),
            Marker::make('Cape Town')->lat(-33.9249)->lng(18.4241)->popup('Cape Town'),
            Marker::make('Johannesburg')->lat(-26.2041)->lng(28.0473)->popup('Johannesburg'),
            Marker::make('Moscow')->lat(55.7558)->lng(37.6173)->popup('Moscow'),
            Marker::make('Beijing')->lat(39.9042)->lng(116.4074)->popup('Beijing'),
        ];
    }

    public function getActions(): array
    {
        return [
            Actions\ZoomAction::make(),
            Actions\CenterMapAction::make()->zoom(2),
        ];
    }
}

?>
