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

    private array $geofenceCoordinates = [];

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

    public function getMarkers(): array
    {
        // Fetch geolocation data
        $geolocationData = $this->fetchGeolocationData();

        $markers = [];

        if (!empty($geolocationData)) {
            // Successfully fetched data, use dynamic markers
            $markers = collect($geolocationData)->map(function ($location) {
                return Marker::make($location['name'])
                    ->lat($location['lat'])
                    ->lng($location['lon'])
                    ->popup("Hello {$location['name']}!");
            })->toArray();
        } else {
            // API request failed, use static markers as fallback
            $markers = [
                Marker::make('pos2')->lat(-15.7942)->lng(-47.8822)->popup('Hello Brasilia!'),
                Marker::make('pos3')->lat(-26.052136294208974)->lng(28.02524274690296)->popup('Hello Bryanstan'),
            ];
        }

        // Add additional markers for New York suburbs
        $newYorkSuburbs = [
            ['name' => 'Brooklyn', 'lat' => 40.6782, 'lon' => -73.9442],
            ['name' => 'Queens', 'lat' => 40.7282, 'lon' => -73.7949],
            ['name' => 'Manhattan', 'lat' => 40.7831, 'lon' => -73.9712],
        ];

        foreach ($newYorkSuburbs as $suburb) {
            $markers[] = Marker::make($suburb['name'])
                ->lat($suburb['lat'])
                ->lng($suburb['lon'])
                ->popup("Hello {$suburb['name']}!");
        }

        // Log marker details
        foreach ($markers as $marker) {
            Log::info("Marker created: ", [
                'name' => $marker->name,
                'lat' => $marker->lat,
                'lng' => $marker->lng,
            ]);
        }

        return $markers;
    }

    public function getPolylines(): array
    {
        // Fetch geolocation data
        $geolocationData = $this->fetchGeolocationData();

        // Initialize an array to store all polylines
        $polylines = [];

        // Add geo fence around New York if found
        foreach ($geolocationData as $location) {
            if ($location['name'] === 'New York') {
                $this->geofenceCoordinates = [
                    [(float)$location['lat'], (float)$location['lon']],
                    [(float)$location['lat'] + 0.1, (float)$location['lon'] + 0.1],
                    [(float)$location['lat'] + 0.1, (float)$location['lon'] - 0.1],
                    [(float)$location['lat'] - 0.1, (float)$location['lon'] - 0.1],
                    [(float)$location['lat'] - 0.1, (float)$location['lon'] + 0.1],
                ];

                Log::info("Geofence coordinates set: ", $this->geofenceCoordinates);

                $polylines[] = Polyline::make('geoFence')
                    ->latlngs($this->geofenceCoordinates)
                    ->options(['color' => 'blue', 'fillColor' => 'blue', 'fillOpacity' => 0.4])
                    ->tooltip('Geo Fence around New York')
                    ->popup('Geo Fence around New York');
            }
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

    public function mount()
    {
        Log::info("Mounting LeafletMapWidget");
        $this->checkMarkersInGeofence();
    }

    private function checkMarkersInGeofence()
    {
        $markers = $this->getMarkers();
        Log::info("Markers: ", $markers);

        if (empty($this->geofenceCoordinates)) {
            Log::warning("Geofence not found");
            return;
        }

        $markersInGeofence = [];
        $markersOutGeofence = [];

        foreach ($markers as $marker) {
            $markerPosition = [$marker->lat, $marker->lng];
            if ($this->isPointInPolygon($markerPosition, $this->geofenceCoordinates)) {
                $markersInGeofence[] = $marker->name;
            } else {
                $markersOutGeofence[] = $marker->name;
            }
        }

        Log::info("Markers in geofence: ", $markersInGeofence);
        Log::info("Markers out of geofence: ", $markersOutGeofence);
    }

    private function isPointInPolygon(array $point, array $polygon): bool
    {
        $inside = false;
        $x = $point[0];
        $y = $point[1];
        $n = count($polygon);
        
        for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
            $xi = $polygon[$i][0];
            $yi = $polygon[$i][1];
            $xj = $polygon[$j][0];
            $yj = $polygon[$j][1];
            
            $intersect = (($yi > $y) != ($yj > $y)) &&
                         ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
            if ($intersect) {
                $inside = !$inside;
            }
        }
        
        return $inside;
    }
}
