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
    function submitWith(lat=null,lng=null,acc=null){
        form.latitude.value = lat ?? '';
        form.longitude.value = lng ?? '';
        form.accuracy.value = acc ?? '';
        form.submit(); // Browser follows whatever response the server returns
    }

    if (!('geolocation' in navigator)) {
        submitWith();
    } else {
        const t = setTimeout(() => submitWith(), 1500); // don’t keep user waiting
        navigator.geolocation.getCurrentPosition(
            p => { clearTimeout(t); submitWith(p.coords.latitude, p.coords.longitude, p.coords.accuracy ?? null); },
            _ => { clearTimeout(t); submitWith(); },
            { enableHighAccuracy: true, timeout: 1200, maximumAge: 0 }
        );
    }
</script>
</body>
</html>
