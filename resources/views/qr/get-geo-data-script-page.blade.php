{{-- resources/views/qr/landing.blade.php --}}
    <!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Opening…</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        html,body{height:100%;margin:0}
        body{display:flex;align-items:center;justify-content:center;font-family:system-ui}
        .hint{opacity:.65;font-size:.9rem;margin-top:.25rem}
    </style>
</head>
<body>
<div>
    <div id="msg">Getting your location…</div>
    <div class="hint">This helps personalize where this QR is used.</div>
</div>

<form id="geo" method="POST" action="{{ route('qr.action', $code) }}" style="display:none">
    @csrf
    <input type="hidden" name="latitude">
    <input type="hidden" name="longitude">
    <input type="hidden" name="accuracy">
    <input type="hidden" name="ts" value="{{ now()->timestamp }}">
</form>

<script>
    const form = document.getElementById('geo');
    const msg  = document.getElementById('msg');

    function submitWith(lat=null, lng=null, acc=null) {
        form.latitude.value  = lat ?? '';
        form.longitude.value = lng ?? '';
        form.accuracy.value  = acc ?? '';
        form.submit(); // Browser follows server response (redirect/view)
    }

    function setMsg(text){ msg && (msg.textContent = text); }

    // Give GPS a fair chance, then fall back
    const GIVE_UP_MS = 7000;

    if (!('geolocation' in navigator)) {
        setMsg('Location not supported. Continuing…');
        submitWith();
    } else {
        setMsg('Getting a GPS fix…');

        const timer = setTimeout(() => {
            setMsg('Using approximate location. Continuing…');
            submitWith(); // nulls if it took too long or blocked
        }, GIVE_UP_MS);

        navigator.geolocation.getCurrentPosition(
            pos => {
                clearTimeout(timer);
                const { latitude, longitude, accuracy } = pos.coords;
                // Optional: inform user about quality
                if (accuracy < 100) {
                    setMsg(`Got accurate location (~${Math.round(accuracy)} m). Continuing…`);
                } else {
                    setMsg(`Location may be approximate (~${Math.round(accuracy)} m). Continuing…`);
                }
                // Small delay so the message paints
                setTimeout(() => submitWith(latitude, longitude, accuracy ?? null), 400);
            },
            err => {
                clearTimeout(timer);
                setMsg('Could not get location. Continuing…');
                submitWith();
            },
            {
                enableHighAccuracy: true, // prefer GPS on phones
                timeout: GIVE_UP_MS - 500, // leave a little room for fallback
                maximumAge: 0
            }
        );
    }
</script>
</body>
</html>
