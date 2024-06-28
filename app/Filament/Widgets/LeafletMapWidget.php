<?php 
namespace App\Filament\Widgets;
use Webbingbrasil\FilamentMaps\Actions;
use Webbingbrasil\FilamentMaps\Marker;
use Webbingbrasil\FilamentMaps\Polyline;
use Webbingbrasil\FilamentMaps\Widgets\MapWidget;

class LeafletMapWidget extends MapWidget
{
    protected int | string | array $columnSpan = 2;
    
    protected bool $hasBorder = false;

    public function getMarkers(): array
    {
        return [
            Marker::make('pos2')->lat(-15.7942)->lng(-47.8822)->popup('Hello Brasilia!'),
            Marker::make('pos3')->lat(-26.052136294208974)->lng(28.02524274690296)->popup('Hello Bryanstan'),
        ];
    }

    public function getPolylines(): array
{
    return [
        Polyline::make('line1')
        ->latlngs([
            [45.51, -122.68],
            [37.77, -122.43],
            [34.04, -118.2]
        ])->options(['color' => 'red', 'weight' => 10])
        ->tooltip('I am a polygon')
        ->popup('I am a polygon'),
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