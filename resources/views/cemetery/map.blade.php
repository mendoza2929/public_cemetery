<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GeoMemoria</title>
    <link rel="icon" href="{{ asset('assests/Logo.png') }}">
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

        #legend {
            position: absolute;
            bottom: 20px;
            right: 20px;
            background: rgba(255,255,255,.95);
            padding: 12px 16px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,.2);
            z-index: 1000;
            font-family: Arial, sans-serif;
            font-size: 13px;
            max-width: 200px;
            backdrop-filter: blur(4px);
        }
        #legend strong {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }
        .legend-dot {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 1px solid rgba(0,0,0,.3);
            flex-shrink: 0;
        }

        /* Modal styling */
        #burialModal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.6);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        #burialModal .modal-content {
            background: #fff;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        #burialModal button {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
        }
        #locateNowBtn {
            background-color: #007bff;
            color: white;
        }
        #closeModal {
            background-color: #6c757d;
            color: white;
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

    <div id="legend">
        <strong>Plot Status</strong>
        <div>
            @foreach($statusColors as $status => $data)
                <div class="legend-item">
                    <div class="legend-dot" style="background-color: {{ $data['color'] }};"></div>
                    <span>{{ $data['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div id="map"></div>

    <!-- Modal for burial details -->
    <div id="burialModal">
        <div class="modal-content">
            <h3>Burial Details</h3>
            <div id="burialDetails"></div>
            <button id="locateNowBtn">Locate Now</button>
            <button id="closeModal">Close</button>
        </div>
    </div>

    <script>
        var cemetery = {!! json_encode($cemetery) !!};
        var map = L.map('map', { minZoom: 14, maxZoom: 18 })
            .setView([parseFloat(cemetery.lat), parseFloat(cemetery.lng)], 16);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        }).addTo(map);

        L.marker([parseFloat(cemetery.lat), parseFloat(cemetery.lng)])
            .addTo(map)
            .bindPopup(cemetery.name);

        var highlightLayer = L.layerGroup().addTo(map);
        var plotMarkers = {};
        var plotBounds = L.latLngBounds();

        // Modal elements
        var modal = document.getElementById('burialModal');
        var burialDetailsDiv = document.getElementById('burialDetails');
        var locateNowBtn = document.getElementById('locateNowBtn');
        var closeModalBtn = document.getElementById('closeModal');
        var selectedPlot = null;

        closeModalBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

     function showBurialModal(plot) {
        selectedPlot = plot;

        function formatDate(dateString) {
            if (!dateString || dateString === '0000-00-00') return 'N/A';
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return 'N/A';
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }

        if (plot.burial) {
            burialDetailsDiv.innerHTML = `
                <p><strong>Lot No:</strong> ${plot.number}</p>
                <p><strong>Deceased:</strong> ${plot.burial.deceased_name}</p>
                <p><strong>Born:</strong> ${formatDate(plot.burial.date_of_birth)}</p>
                <p><strong>Died:</strong> ${formatDate(plot.burial.date_of_death)}</p>
            `;
        } else {
            burialDetailsDiv.innerHTML = `
                <p><strong>Lot No:</strong> ${plot.number}</p>
                <p>No burial info</p>
            `;
        }

        modal.style.display = 'flex';
    }



        locateNowBtn.addEventListener('click', function() {
            if (!selectedPlot) return;
            modal.style.display = 'none';

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var userLat = position.coords.latitude;
                    var userLng = position.coords.longitude;
                    var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                                  '&origin=' + userLat + ',' + userLng +
                                  '&destination=' + selectedPlot.lat + ',' + selectedPlot.lng +
                                  '&travelmode=walking' +
                                  '&dir_action=navigate';
                    window.open(mapsUrl, '_blank');
                }, function() {
                    alert('Could not get location. Using cemetery center.');
                    var mapsUrl = 'https://www.google.com/maps/dir/?api=1' +
                                  '&origin=' + cemetery.lat + ',' + cemetery.lng +
                                  '&destination=' + selectedPlot.lat + ',' + selectedPlot.lng +
                                  '&travelmode=walking' +
                                  '&dir_action=navigate';
                    window.open(mapsUrl, '_blank');
                });
            } else {
                alert('Geolocation not supported.');
            }
        });

        @foreach($cemetery->sections as $section)
            @foreach($section->plots as $plot)
                var plotMarker = L.circleMarker(
                    [parseFloat({{ $plot->lat }}), parseFloat({{ $plot->lng }})],
                    {
                        color: '{{ $plot->status_color }}',
                        fillColor: '{{ $plot->status_color }}',
                        fillOpacity: 0.8,
                        radius: 3
                    }
                ).addTo(map);

                plotMarker.on('click', function() {
                    showBurialModal({
                        id: {{ $plot->id }},
                        lat: {{ $plot->lat }},
                        lng: {{ $plot->lng }},
                        number: '{{ $plot->number }}',
                        status: '{{ $plot->status }}',
                       burial: {!! $plot->burial ? json_encode([
                        'deceased_name' => $plot->burial->deceased_name,
                        'date_of_birth' => $plot->burial->date_of_birth,
                        'date_of_death' => $plot->burial->date_of_death,
                        'burial_date'   => $plot->burial->burial_date,
                        'notes'         => $plot->burial->notes
                    ]) : 'null' !!}
                    });
                });

                plotMarkers[{{ $plot->id }}] = plotMarker;
                plotBounds.extend([parseFloat({{ $plot->lat }}), parseFloat({{ $plot->lng }})]);
            @endforeach
        @endforeach

        if (plotBounds.isValid()) {
            map.fitBounds(plotBounds.pad(0.2));
            if (map.getZoom() > 18) map.setZoom(18);
        }

        // Search functionality
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
                            var highlightMarker = L.circleMarker(
                                [parseFloat(plot.lat), parseFloat(plot.lng)],
                                { color: 'yellow', fillColor: 'yellow', fillOpacity: 0.5, radius: 12 }
                            ).addTo(highlightLayer);

                            highlightMarker.bindPopup('Deceased: ' + burial.deceased_name + '<br>Plot: ' + plot.number);

                            highlightMarker.on('click', function() {
                                showBurialModal({
                                    id: plot.id,
                                    lat: plot.lat,
                                    lng: plot.lng,
                                    number: plot.number,
                                    status: plot.status,
                                    burial: burial
                                });
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
