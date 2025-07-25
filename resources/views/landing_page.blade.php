<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Real-Time Geolocation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            font-family: sans-serif;
            margin: 40px;
            text-align: center;
        }
        .location-data {
            background: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>📍 Your Geolocation Data is here</h1>
<div id="geoStatus">Fetching location...</div>

</body>
</html>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
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
