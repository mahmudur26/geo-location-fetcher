<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Distance Calculator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: #f4f6f9;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #4a90e2, #50c9c3);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover {
            opacity: 0.9;
        }
        #result {
            margin-top: 20px;
            font-size: 16px;
            text-align: center;
            color: #444;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Distance Calculator</h2>
    <input id="origin" type="text" placeholder="Enter origin location">
    <input id="destination" type="text" placeholder="Enter destination location">
    <button onclick="calculateDistance()">Calculate Distance</button>
    <div id="result"></div>
</div>

<!-- Load Google Maps JavaScript API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_API') }}&libraries=places"></script>
<script>
    let originAutocomplete, destinationAutocomplete;

    function initAutocomplete() {
        originAutocomplete = new google.maps.places.Autocomplete(
            document.getElementById('origin'),
            { types: ['geocode'] }
        );
        destinationAutocomplete = new google.maps.places.Autocomplete(
            document.getElementById('destination'),
            { types: ['geocode'] }
        );
    }

    function calculateDistance() {
        const origin = document.getElementById('origin').value;
        const destination = document.getElementById('destination').value;

        if (!origin || !destination) {
            document.getElementById('result').innerText = "Please enter both locations.";
            return;
        }

        const service = new google.maps.DistanceMatrixService();
        service.getDistanceMatrix(
            {
                origins: [origin],
                destinations: [destination],
                travelMode: 'DRIVING',
                unitSystem: google.maps.UnitSystem.METRIC,
            },
            (response, status) => {
                if (status !== 'OK') {
                    document.getElementById('result').innerText = 'Error: ' + status;
                } else {
                    const distance = response.rows[0].elements[0].distance.text;
                    const duration = response.rows[0].elements[0].duration.text;
                    document.getElementById('result').innerHTML =
                        `<strong>Distance:</strong> ${distance}<br><strong>Duration:</strong> ${duration}`;
                }
            }
        );
    }

    // Initialize autocomplete after page load
    window.onload = initAutocomplete;
</script>
</body>
</html>
