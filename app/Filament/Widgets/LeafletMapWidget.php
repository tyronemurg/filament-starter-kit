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
                    //return false; // If any request fails, return false
                }
            } catch (\Exception $e) {
                Log::error("Error fetching data for city: {$city}", ['error' => $e->getMessage()]);
                //return false; // If any request fails, return false
            }
        }
        Log::info("Geolocation data fetched: ", $geolocationData);
        return $geolocationData;
    }

    public function getMarkers(): array
    {
       // Fetch geolocation data
       $geolocationData = $this->fetchGeolocationData();

       if (!empty($geolocationData)) {
           // Successfully fetched data, use dynamic markers
           return collect($geolocationData)->map(function ($location) {
               return Marker::make($location['name'])
                   ->lat($location['lat'])
                   ->lng($location['lon'])
                   ->popup("Hello {$location['name']}!");
           })->toArray();
       } else {
           // API request failed, use static markers as fallback
           return [
               Marker::make('pos2')->lat(-15.7942)->lng(-47.8822)->popup('Hello Brasilia!'),
               Marker::make('pos3')->lat(-26.052136294208974)->lng(28.02524274690296)->popup('Hello Bryanstan'),
           ];
       }
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
                $polylines[] = Polyline::make('geoFence')
                    ->latlngs([
                        [(float)$location['lat'], (float)$location['lon']],
                        // Add other points to form a polygon (geo fence)
                        [(float)$location['lat'] + 0.1, (float)$location['lon'] + 0.1], // Example points
                        [(float)$location['lat'] + 0.1, (float)$location['lon'] - 0.1],
                        [(float)$location['lat'] - 0.1, (float)$location['lon'] - 0.1],
                        [(float)$location['lat'] - 0.1, (float)$location['lon'] + 0.1],
                    ])
                    ->options(['color' => 'blue', 'fillColor' => 'blue', 'fillOpacity' => 0.4]) // Adjust color and opacity as needed
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