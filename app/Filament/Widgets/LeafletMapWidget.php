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

    $geolocationData = $this->fetchGeolocationData();
    $latlngs = [];

    foreach ($geolocationData as $location) {
        $latlngs[] = [(float)$location['lat'], (float)$location['lon']];
    }

    return [
        Polyline::make('line1')
            ->latlngs($latlngs)
            ->options(['color' => 'red', 'weight' => 10])
            ->tooltip('Dynamic Polygon')
            ->popup('Dynamic Polygon'),
    ];

    // return [
    //     Polyline::make('line1')
    //     ->latlngs([
    //         [45.51, -122.68],
    //         [37.77, -122.43],
    //         [34.04, -118.2]
    //     ])->options(['color' => 'red', 'weight' => 10])
    //     ->tooltip('I am a polygon')
    //     ->popup('I am a polygon'),
    // ];
}

    public function getActions(): array
    {
        return [
            Actions\ZoomAction::make(),
            Actions\CenterMapAction::make()->zoom(2),
        ];
    }
}