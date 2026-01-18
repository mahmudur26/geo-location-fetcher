<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leaflet Distance Calculator</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 400px;
            width: 100%;
        }
        .controls {
            padding: 15px;
            background: #f4f6f9;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        select, button {
            padding: 8px;
            font-size: 14px;
        }
        #result {
            margin-left: 20px;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
<div class="controls">
    <select id="origin">
        <option value="">Select Origin</option>
        <option value="23.8103,90.4125">Dhaka</option>
        <option value="22.3569,91.7832">Chittagong</option>
        <option value="24.3636,88.6241">Rajshahi</option>
    </select>

    <select id="destination">
        <option value="">Select Destination</option>
        <option value="23.8103,90.4125">Dhaka</option>
        <option value="22.3569,91.7832">Chittagong</option>
        <option value="24.3636,88.6241">Rajshahi</option>
    </select>

    <button onclick="calculateDistance()">Calculate</button>
    <div id="result"></div>
</div>

<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // Initialize map
    var map = L.map('map').setView([23.8103, 90.4125], 7);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    var markers = [];

    function calculateDistance() {
        // Clear previous markers
        markers.forEach(m => map.removeLayer(m));
        markers = [];

        const originVal = document.getElementById('origin').value;
        const destVal = document.getElementById('destination').value;

        if (!originVal || !destVal) {
            document.getElementById('result').innerText = "Please select both locations.";
            return;
        }

        const originCoords = originVal.split(',').map(Number);
        const destCoords = destVal.split(',').map(Number);

        // Add markers
        const originMarker = L.marker(originCoords).addTo(map).bindPopup("Origin").openPopup();
        const destMarker = L.marker(destCoords).addTo(map).bindPopup("Destination").openPopup();
        markers.push(originMarker, destMarker);

        // Draw line between points
        const line = L.polyline([originCoords, destCoords], {color: 'blue'}).addTo(map);
        markers.push(line);

        // Calculate distance (straight line)
        const distance = map.distance(originCoords, destCoords) / 1000; // km
        document.getElementById('result').innerText = `Distance: ${distance.toFixed(2)} km`;

        // Fit map to markers
        map.fitBounds(line.getBounds());
    }
</script>
</body>
</html>
