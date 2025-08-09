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

<form id="geo" method="POST" action="{{ route('qr.action', $code) }}" style="display:none">
    @csrf
    <input type="hidden" name="latitude">
    <input type="hidden" name="longitude">
    <input type="hidden" name="accuracy">
    <input type="hidden" name="ts" value="{{ now()->timestamp }}">
</form>

<script>
    const form = document.getElementById('geo');
    const status = document.createElement('p');
    document.body.appendChild(status);

    function submitWith(lat=null,lng=null,acc=null){
        form.latitude.value = lat ?? '';
        form.longitude.value = lng ?? '';
        form.accuracy.value = acc ?? '';
        form.submit();
    }

    function show(msg) {
        status.innerText = msg;
    }

    if (!('geolocation' in navigator)) {
        show("Geolocation not supported. Submitting without location.");
        submitWith();
    } else {
        show("Getting GPS fix…");

        const timeout = setTimeout(() => {
            show("GPS timed out. Submitting approximate location.");
            submitWith();
        }, 7000); // give up after 7 sec

        navigator.geolocation.getCurrentPosition(
            p => {
                clearTimeout(timeout);
                const acc = p.coords.accuracy;
                if (acc < 100) {
                    show(`Got high-accuracy location (${Math.round(acc)}m).`);
                } else {
                    show(`Location may be inaccurate (~${Math.round(acc)}m).`);
                }
                setTimeout(() => submitWith(p.coords.latitude, p.coords.longitude, acc), 800);
            },
            err => {
                clearTimeout(timeout);
                show("Could not get location. Submitting without.");
                submitWith();
            },
            {
                enableHighAccuracy: true,
                timeout: 6000,
                maximumAge: 0
            }
        );
    }
</script>

</body>
</html>
