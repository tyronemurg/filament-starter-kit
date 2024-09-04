<?php

namespace App\Filament\Widgets;

use Webbingbrasil\FilamentMaps\Actions;
use Webbingbrasil\FilamentMaps\Marker;
use Webbingbrasil\FilamentMaps\Polyline;
use Webbingbrasil\FilamentMaps\Widgets\MapWidget;

class LeafletMapMarkerGeofenceWidget extends MapWidget
{
    protected int | string | array $columnSpan = 2;

    protected bool $hasBorder = false;

    protected static bool $isLazy = false;

    private function createGeofence($lat, $lng, $radius = 0.1)
    {
        return Polyline::make('geofence')
            ->latlngs([
                [$lat + $radius, $lng + $radius],
                [$lat + $radius, $lng - $radius],
                [$lat - $radius, $lng - $radius],
                [$lat - $radius, $lng + $radius],
                [$lat + $radius, $lng + $radius],
            ])
            ->options(['color' => 'red', 'fillColor' => 'red', 'fillOpacity' => 0.2])
            ->popup('Geofence');
    }

    private function isMarkerInGeofence($markerLat, $markerLng, $fenceLat, $fenceLng, $radius = 0.1): bool
    {
        $distance = sqrt(pow($markerLat - $fenceLat, 2) + pow($markerLng - $fenceLng, 2));
        return $distance <= $radius;
    }

    public function getMarkers(): array
    {
        $locations = [
            ['name' => 'New York', 'lat' => 40.7128, 'lng' => -74.0060],
            ['name' => 'Los Angeles', 'lat' => 34.0522, 'lng' => -118.2437],
            ['name' => 'Chicago', 'lat' => 41.8781, 'lng' => -87.6298],
            ['name' => 'San Francisco', 'lat' => 37.7749, 'lng' => -122.4194],
            ['name' => 'Miami', 'lat' => 25.7617, 'lng' => -80.1918],
            ['name' => 'Houston', 'lat' => 29.7604, 'lng' => -95.3698],
            ['name' => 'Seattle', 'lat' => 47.6062, 'lng' => -122.3321],
            ['name' => 'Denver', 'lat' => 39.7392, 'lng' => -104.9903],
            ['name' => 'Boston', 'lat' => 42.3601, 'lng' => -71.0589],
            ['name' => 'Atlanta', 'lat' => 33.7490, 'lng' => -84.3880],
            // Add more locations as needed...
        ];

        $markers = [];

        foreach ($locations as $location) {
            $lat = $location['lat'];
            $lng = $location['lng'];

            $isInGeofence = $this->isMarkerInGeofence($lat, $lng, $lat, $lng);

            $popupText = $isInGeofence ? "Marker in Geofence" : "Marker outside Geofence";

            $markers[] = Marker::make($location['name'])
                ->lat($lat)
                ->lng($lng)
                ->popup($popupText);
        }

        return $markers;
    }

    public function getPolylines(): array
    {
        $geofences = [];

        // Creating geofences for the first 10 locations
        $locations = [
            ['lat' => 40.7128, 'lng' => -74.0060], // New York
            ['lat' => 34.0522, 'lng' => -118.2437], // Los Angeles
            ['lat' => 41.8781, 'lng' => -87.6298], // Chicago
            ['lat' => 37.7749, 'lng' => -122.4194], // San Francisco
            ['lat' => 25.7617, 'lng' => -80.1918], // Miami
            ['lat' => 29.7604, 'lng' => -95.3698], // Houston
            ['lat' => 47.6062, 'lng' => -122.3321], // Seattle
            ['lat' => 39.7392, 'lng' => -104.9903], // Denver
            ['lat' => 42.3601, 'lng' => -71.0589], // Boston
            ['lat' => 33.7490, 'lng' => -84.3880], // Atlanta
        ];

        foreach ($locations as $location) {
            $geofences[] = $this->createGeofence($location['lat'], $location['lng']);
        }

        return $geofences;
    }

    public function getActions(): array
    {
        return [
            Actions\ZoomAction::make(),
            Actions\CenterMapAction::make()->fitBounds([
                [40.7128, -74.0060],
                
             ])
        ];
    }
}

?>
