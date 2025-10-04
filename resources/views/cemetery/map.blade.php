<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoMemoria</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
          html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
        font-family: Arial, sans-serif;
    }
    .top-bar {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        position: absolute;
        top: 10px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        background: rgba(255,255,255,0.9);
        padding: 8px 15px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    .top-bar input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 16px;
    }
    .top-bar button {
        padding: 10px 15px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .top-bar button:hover {
        background-color: #0056b3;
    }
    #map {
        height: 100vh;
        width: 100%;
    }
    </style>
</head>
<body>
     <div class="top-bar">
        <a href="{{ url('/') }}">
            <button>
                <i class="fa fa-home"></i> Home
            </button>
        </a>
        <input type="text" id="searchInput" placeholder="Search by deceased name...">
        <button id="searchButton"><i class="fa fa-search"></i> Search</button>
    </div>

    <div id="map"></div>

    <script>
        var cemetery = {!! json_encode($cemetery) !!};
        var map = L.map('map', {
            minZoom: 14,
            maxZoom: 18
        }).setView([parseFloat(cemetery.lat), parseFloat(cemetery.lng)], 16);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        }).addTo(map);

        L.marker([parseFloat(cemetery.lat), parseFloat(cemetery.lng)])
            .addTo(map)
            .bindPopup(cemetery.name);

        var highlightLayer = L.layerGroup().addTo(map);
        var plotMarkers = {};
        var plotBounds = L.latLngBounds();

        @foreach($cemetery->sections as $section)
            @foreach($section->plots as $plot)
                var plotMarker = L.circleMarker([parseFloat({{ $plot->lat }}), parseFloat({{ $plot->lng }})], {
                    color: '{{ $plot->status_color }}',
                    fillColor: '{{ $plot->status_color }}',
                    fillOpacity: 0.8,
                    radius: 3
                }).addTo(map);

                var popupContent = 'Plot {{ $plot->number }} - {{ $plot->status }}';
                @if ($plot->burial)
                    popupContent += '<br>Deceased: {{ $plot->burial->deceased_name }}';
                @endif
                plotMarker.bindPopup(popupContent);

                plotMarker.on('click', function() {
                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(function(position) {
                            var userLat = position.coords.latitude;
                            var userLng = position.coords.longitude;
                            var plotLat = parseFloat({{ $plot->lat }});
                            var plotLng = parseFloat({{ $plot->lng }});
                            var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                                          '&origin=' + userLat + ',' + userLng +
                                          '&destination=' + plotLat + ',' + plotLng +
                                          '&travelmode=walking' +
                                          '&dir_action=navigate';
                            window.open(mapsUrl, '_blank');
                        }, function(error) {
                            var errorMessage = 'Geolocation failed: ';
                            switch(error.code) {
                                case error.PERMISSION_DENIED:
                                    errorMessage += 'Permission denied. Please enable location access.';
                                    break;
                                case error.POSITION_UNAVAILABLE:
                                    errorMessage += 'Location unavailable. Using cemetery center.';
                                    break;
                                case error.TIMEOUT:
                                    errorMessage += 'Request timed out.';
                                    break;
                                default:
                                    errorMessage += 'An unknown error occurred.';
                            }
                            alert(errorMessage + '\nFalling back to cemetery center.');
                            var defaultLat = parseFloat({{ $cemetery->lat }});
                            var defaultLng = parseFloat({{ $cemetery->lng }});
                            var plotLat = parseFloat({{ $plot->lat }});
                            var plotLng = parseFloat({{ $plot->lng }});
                            var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                                          '&origin=' + defaultLat + ',' + defaultLng +
                                          '&destination=' + plotLat + ',' + plotLng +
                                          '&travelmode=walking' +
                                          '&dir_action=navigate';
                            window.open(mapsUrl, '_blank');
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        });
                    } else {
                        alert('Geolocation not supported. Using cemetery center.');
                        var defaultLat = parseFloat({{ $cemetery->lat }});
                        var defaultLng = parseFloat({{ $cemetery->lng }});
                        var plotLat = parseFloat({{ $plot->lat }});
                        var plotLng = parseFloat({{ $plot->lng }});
                        var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                                      '&origin=' + defaultLat + ',' + defaultLng +
                                      '&destination=' + plotLat + ',' + plotLng +
                                      '&travelmode=walking' +
                                      '&dir_action=navigate';
                        window.open(mapsUrl, '_blank');
                    }
                });

                plotMarkers[{{ $plot->id }}] = plotMarker;
                plotBounds.extend([parseFloat({{ $plot->lat }}), parseFloat({{ $plot->lng }})]);
            @endforeach
        @endforeach

        if (plotBounds.isValid()) {
            map.fitBounds(plotBounds.pad(0.2));
            if (map.getZoom() > 18) map.setZoom(18);
        }

        // Search functionality with click-to-guide
        document.getElementById('searchButton').addEventListener('click', function() {
            var query = document.getElementById('searchInput').value.trim();
            if (!query) {
                alert('Please enter a name to search.');
                return;
            }

            highlightLayer.clearLayers();

            fetch('/cemetery/' + cemetery.id + '/search?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    if (data.length === 0) {
                        alert('No matching deceased found.');
                        return;
                    }

                    var bounds = L.latLngBounds();
                    data.forEach(function(burial) {
                        var plot = burial.plot;
                        if (plot && plotMarkers[plot.id]) {
                            var highlightMarker = L.circleMarker([parseFloat(plot.lat), parseFloat(plot.lng)], {
                                color: 'yellow',
                                fillColor: 'yellow',
                                fillOpacity: 0.5,
                                radius: 12
                            }).addTo(highlightLayer);

                            highlightMarker.bindPopup('Deceased: ' + burial.deceased_name + '<br>Plot: ' + plot.number);

                            // Add click-to-guide functionality
                            highlightMarker.on('click', function() {
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(function(position) {
                                        var userLat = position.coords.latitude;
                                        var userLng = position.coords.longitude;
                                        var plotLat = parseFloat(plot.lat);
                                        var plotLng = parseFloat(plot.lng);
                                        var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                                                      '&origin=' + userLat + ',' + userLng +
                                                      '&destination=' + plotLat + ',' + plotLng +
                                                      '&travelmode=walking' +
                                                      '&dir_action=navigate';
                                        window.open(mapsUrl, '_blank');
                                    }, function(error) {
                                        var errorMessage = 'Geolocation failed: ';
                                        switch(error.code) {
                                            case error.PERMISSION_DENIED:
                                                errorMessage += 'Permission denied. Please enable location access.';
                                                break;
                                            case error.POSITION_UNAVAILABLE:
                                                errorMessage += 'Location unavailable. Using cemetery center.';
                                                break;
                                            case error.TIMEOUT:
                                                errorMessage += 'Request timed out.';
                                                break;
                                            default:
                                                errorMessage += 'An unknown error occurred.';
                                        }
                                        alert(errorMessage + '\nFalling back to cemetery center.');
                                        var defaultLat = parseFloat(cemetery.lat);
                                        var defaultLng = parseFloat(cemetery.lng);
                                        var plotLat = parseFloat(plot.lat);
                                        var plotLng = parseFloat(plot.lng);
                                        var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                                                      '&origin=' + defaultLat + ',' + defaultLng +
                                                      '&destination=' + plotLat + ',' + plotLng +
                                                      '&travelmode=walking' +
                                                      '&dir_action=navigate';
                                        window.open(mapsUrl, '_blank');
                                    }, {
                                        enableHighAccuracy: true,
                                        timeout: 10000,
                                        maximumAge: 0
                                    });
                                } else {
                                    alert('Geolocation not supported. Using cemetery center.');
                                    var defaultLat = parseFloat(cemetery.lat);
                                    var defaultLng = parseFloat(cemetery.lng);
                                    var plotLat = parseFloat(plot.lat);
                                    var plotLng = parseFloat(plot.lng);
                                    var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                                                  '&origin=' + defaultLat + ',' + defaultLng +
                                                  '&destination=' + plotLat + ',' + plotLng +
                                                  '&travelmode=walking' +
                                                  '&dir_action=navigate';
                                    window.open(mapsUrl, '_blank');
                                }
                            });

                            bounds.extend([parseFloat(plot.lat), parseFloat(plot.lng)]);
                        }
                    });

                    if (bounds.isValid()) {
                        map.fitBounds(bounds.pad(0.2));
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    alert('An error occurred during search.');
                });
        });
    </script>
</body>
</html>