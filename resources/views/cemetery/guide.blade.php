<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting to Google Maps...</title>
    <link rel="icon" href="{{ asset('assests/Logo.png') }}">
</head>
<body>
    <p>Opening directions in Google Maps...</p>
    <script>
        var plotLat = {{ $lat }};
        var plotLng = {{ $lng }};
        var defaultLat = {{ $cemeteryLat }};
        var defaultLng = {{ $cemeteryLng }};

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var userLat = position.coords.latitude;
                var userLng = position.coords.longitude;
                var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                              '&origin=' + userLat + ',' + userLng +
                              '&destination=' + plotLat + ',' + plotLng +
                              '&travelmode=walking' +
                              '&dir_action=navigate';
                window.location.href = mapsUrl;
            }, function(error) {
                var message = '';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message = 'Permission denied. Please allow location access.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'Location data unavailable. Check your GPS or network.';
                        break;
                    case error.TIMEOUT:
                        message = 'Request timed out. Try again or check your connection.';
                        break;
                    default:
                        message = 'Unknown error occurred.';
                }
                alert('Geolocation failed: ' + message + ' Using cemetery center as origin.');
                var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                              '&origin=' + defaultLat + ',' + defaultLng +
                              '&destination=' + plotLat + ',' + plotLng +
                              '&travelmode=walking' +
                              '&dir_action=navigate';
                window.location.href = mapsUrl;
            }, { timeout: 10000 });
        } else {
            alert('Geolocation not supported. Using cemetery center as origin.');
            var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                          '&origin=' + defaultLat + ',' + defaultLng +
                          '&destination=' + plotLat + ',' + plotLng +
                          '&travelmode=walking' +
                          '&dir_action=navigate';
            window.location.href = mapsUrl;
        }
    </script>
</body>
</html>