<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Real-Time Geolocation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/geo-location-fetch/style.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Intel+One+Mono:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
</head>
<body>
<div id="map" style="height: 500px;"></div>
<div class="container d-flex justify-content-center">
    <div class="table-section table-responsive">
        <table class="table align-middle table-bordered w-80">
            <thead class="table-dark">
            <tr>
                <th class="text-center">IP</th>
                <th class="text-center">Latitude</th>
                <th class="text-center">Longitude</th>
                <th class="text-center">Full Address</th>
                <th class="text-center">Accuracy (meter)</th>
            </tr>
            </thead>
            <tbody>
            @foreach($geo_data as $data)
                <tr>
                    <td>{{ $data['ip'] }}</td>
                    <td>{{ $data['latitude'] }}</td>
                    <td>{{ $data['longitude'] }}</td>
                    <td>{{ $data['full_address'] }}</td>
                    <td class="text-center">{{ $data['accuracy_in_meter'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


</div>

</body>
</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const geoCoordinates = @json($coordinates);
    document.addEventListener("DOMContentLoaded", function () {
        const map = L.map('map').setView([23.8103, 90.4125], 3); // Set to a neutral zoom

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Loop through coordinates and add markers
        geoCoordinates.forEach(([lat, lng]) => {
            if (lat && lng) {
                L.marker([parseFloat(lat), parseFloat(lng)]).addTo(map)
                    .bindPopup(`Lat: ${lat}, Lng: ${lng}`);
            }
        });
    });

    $(document).ready(function () {
        // Default to null
        let latitude = null;
        let longitude = null;

        function sendLocationData() {
            $.ajax({
                url: "{{ route('location.store') }}",
                method: "POST",
                data: {
                    latitude: latitude,
                    longitude: longitude,
                    _token: "{{ csrf_token() }}"
                },
                success: function (response) {
                    console.log("Location sent:", response);
                },
                error: function (xhr) {
                    console.error("Error:", xhr.responseText);
                }
            });
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    sendLocationData();
                },
                function (error) {
                    console.warn("Geolocation permission denied or unavailable:", error.message);
                    sendLocationData(); // Send nulls
                }
            );
        } else {
            console.warn("Geolocation not supported.");
            sendLocationData(); // Send nulls
        }
    });
</script>
