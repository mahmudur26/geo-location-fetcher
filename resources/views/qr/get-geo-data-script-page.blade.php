{{-- resources/views/qr/landing.blade.php --}}
    <!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Opening…</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>html,body{height:100%;margin:0} body{display:flex;align-items:center;justify-content:center;font-family:system-ui}</style>
</head>
<body>
<div>{{ $message ?? 'Preparing…' }}</div>

<script>
    let code = @json($code);
    $(document).ready(function () {
        // Default to null
        let latitude = null;
        let longitude = null;

        function sendLocationData() {
            $.ajax({
                url: "{{ route('qr.action') }}",
                method: "POST",
                data: {
                    latitude: latitude,
                    longitude: longitude,
                    code: code,
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

</body>
</html>
