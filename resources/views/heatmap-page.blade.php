<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Heatmap Example</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
    <style>
        #map {
            height: 800px;
            width: 100%;
        }
    </style>
</head>
<body>

    <div id="map"></div>

    <script>
        // Initialize the map
        var map = L.map('map').setView([20, 0], 2);

        // Add a tile layer (OpenStreetMap)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Get the heatmap data passed from the controller
        var heatmapData = @json($heatmapData);

        // Create a heatmap layer with the provided data
        var heat = L.heatLayer(heatmapData, {
            radius: 25,
            blur: 15,
            maxZoom: 1,
            gradient: {
        0.2: 'blue',
        0.4: 'lime',
        0.6: 'yellow',
        0.8: 'orange',
        1.0: 'red'
    }
        }).addTo(map);
    </script>
</body>
</html>
