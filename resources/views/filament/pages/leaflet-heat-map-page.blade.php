<x-filament-panels::page>
    <div id="map" style="height: 400px;border-radius:15px;"></div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Check if Leaflet and Leaflet Heat plugin are loaded
            console.log("Leaflet:", L);
            console.log("Leaflet Heat Plugin:", L.heatLayer);

            // Initialize the map
            var map = L.map('map').setView([20, 0], 2);

            // Add the OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Get heatmap data from the controller
            var heatmapData = @json($heatmapData);

            // Ensure heatmapData is properly formatted
            console.log("Heatmap Data:", heatmapData);

            // Create the heatmap layer
            if (L.heatLayer) {
                var heat = L.heatLayer(heatmapData, {
                    radius: 25,
                    blur: 15,
                    maxZoom: 17,
                    gradient: {
                        0.2: 'blue',
                        0.5: 'lime',
                        0.7: 'orange',
                        1: 'red'
                    }
                }).addTo(map);
            } else {
                console.error("L.heatLayer is not available.");
            }
        });
    </script>
</x-filament-panels::page>
