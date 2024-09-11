<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewHeatMapPage extends Controller
{
    // Sample heatmap data (latitude, longitude, and intensity)
    public function getHeatmapData(): array
    {
        return [
            [40.7128, -74.0060, 0.8],  // New York, USA
            [34.0522, -118.2437, 0.6], // Los Angeles, USA
            [41.8781, -87.6298, 0.7],  // Chicago, USA
            [37.7749, -122.4194, 0.5], // San Francisco, USA
            [25.7617, -80.1918, 0.9],  // Miami, USA
            [29.7604, -95.3698, 0.7],  // Houston, USA
            [47.6062, -122.3321, 0.6], // Seattle, USA
            [39.7392, -104.9903, 0.6], // Denver, USA
            [42.3601, -71.0589, 0.7],  // Boston, USA
            [33.7490, -84.3880, 0.8],  // Atlanta, USA
            [51.5074, -0.1278, 0.9],   // London, UK
            [48.8566, 2.3522, 0.8],    // Paris, France
            [52.5200, 13.4050, 0.7],   // Berlin, Germany
            [-33.8688, 151.2093, 0.6], // Sydney, Australia
            [35.6895, 139.6917, 0.9],  // Tokyo, Japan
            [25.276987, 55.296249, 0.8],// Dubai, UAE
            [-33.9249, 18.4241, 0.5],  // Cape Town, South Africa
            [-26.2041, 28.0473, 0.7],  // Johannesburg, South Africa
            [55.7558, 37.6173, 0.6],   // Moscow, Russia
            [39.9042, 116.4074, 0.8],  // Beijing, China
        ];
    }

    // Render the heatmap page
    public function show()
    {
        $heatmapData = $this->getHeatmapData();
        return view('heatmap-page', compact('heatmapData'));
    }
}
