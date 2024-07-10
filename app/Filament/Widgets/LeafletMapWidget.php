<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Webbingbrasil\FilamentMaps\Actions;
use Webbingbrasil\FilamentMaps\Marker;
use Webbingbrasil\FilamentMaps\Polyline;
use Webbingbrasil\FilamentMaps\Widgets\MapWidget;

class LeafletMapWidget extends MapWidget
{
    protected int | string | array $columnSpan = 2;

    protected bool $hasBorder = false;

    protected static bool $isLazy = false;

    private function fetchGeolocationData(): array
    {
        // Fetch geolocation data for a few cities
        $cities = ['New York', 'Los Angeles', 'Chicago', 'San Francisco', 'Cape Town', 'Durban', 'London'];
        $geolocationData = [];

        foreach ($cities as $city) {
            try {
                $response = Http::get('https://nominatim.openstreetmap.org/search', [
                    'q' => $city,
                    'format' => 'json',
                    'limit' => 1,
                ]);

                if ($response->successful() && !empty($response->json())) {
                    $geolocationData[] = [
                        'name' => $city,
                        'lat' => $response->json()[0]['lat'],
                        'lon' => $response->json()[0]['lon'],
                    ];
                } else {
                    Log::error("Failed to fetch data for city: {$city}", ['response' => $response->body()]);
                }
            } catch (\Exception $e) {
                Log::error("Error fetching data for city: {$city}", ['error' => $e->getMessage()]);
            }
        }

        Log::info("Geolocation data fetched: ", $geolocationData);
        return $geolocationData;
    }

    private function isMarkerInGeoFence($markerLat, $markerLng): bool
    {
        // Assuming the geo fence is around New York for dynamic markers
        $newYorkLat = 40.7128;
        $newYorkLng = -74.0060;
        $geoFenceRadius = 0.5; // Adjust the radius as needed

        // Calculate distance from marker to center of New York (using simple Euclidean distance for demonstration)
        $distance = sqrt(pow($markerLat - $newYorkLat, 2) + pow($markerLng - $newYorkLng, 2));

        return $distance <= $geoFenceRadius;
    }

    public function getMarkers(): array
    {
        // Fetch geolocation data
        $geolocationData = $this->fetchGeolocationData();

        $markers = [];

        if (!empty($geolocationData)) {
            // Use dynamic markers with geofence check
            foreach ($geolocationData as $location) {
                $marker = Marker::make($location['name'])
                    ->lat($location['lat'])
                    ->lng($location['lon'])
                    ->popup("Hello {$location['name']}!");

                // Check if marker is inside the geo fence
                if ($this->isMarkerInGeoFence($location['lat'], $location['lon'])) {
                    Log::info("Marker {$location['name']} is inside the geo fence.");
                } else {
                    Log::info("Marker {$location['name']} is outside the geo fence.");
                }

                $markers[] = $marker;
            }
        } else {
            // Use static markers with geofence
            $markers = [
                Marker::make('pos2')
                    ->lat(-15.7942)
                    ->lng(-47.8822)
                    ->popup('Hello Brasilia!')
                    ->options(['icon' => 'default']),
                Marker::make('pos3')
                    ->lat(-26.052136294208974)
                    ->lng(28.02524274690296)
                    ->popup('Hello Bryanstan')
                    ->options(['icon' => 'default']),
            ];

            // Check if static markers are inside the geo fence (Bryanstan)
            foreach ($markers as $marker) {
                $lat = $marker->getLat();
                $lng = $marker->getLng();

                // Check if marker is inside the geo fence
                if ($this->isMarkerInGeoFence($lat, $lng)) {
                    Log::info("Static marker {$marker->getName()} is inside the geo fence.");
                } else {
                    Log::info("Static marker {$marker->getName()} is outside the geo fence.");
                }
            }

            // Add geo fence for Bryanstan
            $markers[] = Polyline::make('geoFence')
                ->latlngs([
                    [-26.052136294208974, 28.02524274690296],
                    [-26.052136294208974 + 0.1, 28.02524274690296 + 0.1], // Example points
                    [-26.052136294208974 + 0.1, 28.02524274690296 - 0.1],
                    [-26.052136294208974 - 0.1, 28.02524274690296 - 0.1],
                    [-26.052136294208974 - 0.1, 28.02524274690296 + 0.1],
                ])
                ->options(['color' => 'blue', 'fillColor' => 'blue', 'fillOpacity' => 0.4]) // Adjust color and opacity as needed
                ->tooltip('Geo Fence around Bryanstan')
                ->popup('Geo Fence around Bryanstan');
        }

        return $markers;
    }

    public function getPolylines(): array
    {
        // Fetch geolocation data
        $geolocationData = $this->fetchGeolocationData();

        // Find the coordinates for New York
        $newYorkCoordinates = null;
        foreach ($geolocationData as $location) {
            if ($location['name'] === 'New York') {
                $newYorkCoordinates = [(float)$location['lat'], (float)$location['lon']];
                break;
            }
        }

        $polylines = [];

        // Add geo fence around New York if coordinates are found
        if ($newYorkCoordinates) {
            $polylines[] = Polyline::make('geoFence')
                ->latlngs([
                    $newYorkCoordinates,
                    [(float)$newYorkCoordinates[0] + 0.1, (float)$newYorkCoordinates[1] + 0.1], // Example points
                    [(float)$newYorkCoordinates[0] + 0.1, (float)$newYorkCoordinates[1] - 0.1],
                    [(float)$newYorkCoordinates[0] - 0.1, (float)$newYorkCoordinates[1] - 0.1],
                    [(float)$newYorkCoordinates[0] - 0.1, (float)$newYorkCoordinates[1] + 0.1],
                ])
                ->options(['color' => 'red', 'fillColor' => 'red', 'fillOpacity' => 0.4]) // Adjust color and opacity as needed
                ->tooltip('Geo Fence around New York')
                ->popup('Geo Fence around New York');
        }

        // Find the coordinates for Durban and Cape Town
        $durbanCoordinates = null;
        $capeTownCoordinates = null;

        foreach ($geolocationData as $location) {
            if ($location['name'] === 'Durban') {
                $durbanCoordinates = [(float)$location['lat'], (float)$location['lon']];
            }
            if ($location['name'] === 'Cape Town') {
                $capeTownCoordinates = [(float)$location['lat'], (float)$location['lon']];
            }
        }

        // Add polyline from Durban to Cape Town if coordinates are found
        if ($durbanCoordinates && $capeTownCoordinates) {
            $polylines[] = Polyline::make('routeDurbanToCapeTown')
                ->latlngs([
                    $durbanCoordinates,
                    $capeTownCoordinates,
                    // Add more points as needed to refine the route
                ])
                ->options(['color' => 'green', 'weight' => 5])
                ->tooltip('Route from Durban to Cape Town')
                ->popup('Route from Durban to Cape Town');
        }

        return $polylines;
    }

    public function getActions(): array
    {
        return [
            Actions\ZoomAction::make(),
            Actions\CenterMapAction::make()->zoom(2),
        ];
    }
}
