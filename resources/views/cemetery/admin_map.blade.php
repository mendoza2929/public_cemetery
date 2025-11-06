<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $cemetery->name }} Map - GeoMemoria</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="icon" href="{{ asset('assests/Logo.png') }}">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        /* Sidebar */
        .sidebar {
            height: 100vh;
            background: linear-gradient(180deg, #2c3e50, #1a252f);
            color: #fff;
            position: fixed;
            width: 240px;
            display: flex;
            flex-direction: column;
        }
        .sidebar h2 {
            text-align: center;
            padding: 1.5rem 0;
            font-size: 1.3rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar a {
            color: #ddd;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #3498db;
            color: white;
        }

        /* Main Content */
        .content {
            margin-left: 240px;
            padding: 2rem;
            min-height: 100vh;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        /* Map + Search */
        .map-container {
            position: relative;
            height: 70vh;
            border-radius: 10px;
            overflow: hidden;
        }

        #map {
            width: 100%;
            height: 100%;
        }

        .search-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 1rem;
        }
        .search-bar input {
            flex: 1;
        }

        footer {
            text-align: center;
            color: #777;
            padding: 1em;
            margin-top: 2em;
        }

        /* Plot Legend */
        .legend {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .plot {
            width: 18px;
            height: 18px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }
        .available { background-color: #30a52aff; }
        .sold { background-color: #ff000053; }
        .reserved { background-color: #FFFF00; }
        .quitclaim { background-color: #ff00e6ff; }
        .restricted { background-color: #ff0000ff; }
        .sold_with_burial { background-color: #800080; }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2><img src="{{ asset('assests/Logo.png') }}" alt="Gcash QR Code" style="max-width:200px; width:25%; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.2);"></h2>
        <a href="{{ url('cemetery/admin') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ url('cemetery/1/map') }}" class="active"><i class="bi bi-map"></i> Plots</a>
          <a href="{{ url('cemetery/reservation') }}"><i class="bi bi-calendar-check"></i> Reservations</a>
        <a href="{{ url('auth/logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Plots Overview</h3>
             <div class="d-flex align-items-center gap-3">
               
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createPlotModal">
                    <i class="bi bi-plus-circle"></i> Create Plot
                </button>
            </div>
        </div>


        <div class="card p-4">
            <h5 class="mb-3">{{ $cemetery->name }}</h5>

            <!-- Search Bar -->
            <div class="search-bar">
                <input type="text" id="searchInput" class="form-control" placeholder="Search by deceased name...">
                <button id="searchButton" class="btn btn-primary">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>

            <!-- Map -->
            <div class="map-container mb-3">
                <div id="map"></div>
            </div>

            <!-- Legend -->
            <div class="legend">
                <div class="legend-item"><div class="plot available"></div> Available</div>
                 <div class="legend-item"><div class="plot sold"></div> Sold</div>
                <div class="legend-item"><div class="plot reserved"></div> Reserved</div>
                <div class="legend-item"><div class="plot quitclaim"></div> Quit Claim</div>
                <div class="legend-item"><div class="plot restricted"></div> Restricted</div>
                <div class="legend-item"><div class="plot sold_with_burial"></div> Sold with Burial</div>
            </div>
        </div>

        <footer>&copy; 2025 City Public Cemetery</footer>
    </div>

    <!-- Create Plot Modal -->
    <div class="modal fade" id="createPlotModal" tabindex="-1" aria-labelledby="createPlotModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="createPlotModalLabel"><i class="bi bi-plus-circle"></i> Create New Plot</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="createPlotForm">
                <div class="modal-body">
                <div class="mb-3">
                    <label for="plotNumber" class="form-label">Plot Number</label>
                    <input type="text" class="form-control" id="plotNumber" name="number" placeholder="e.g. 101A" required>
                </div>
                <div class="mb-3">
                    <label for="plotNumber" class="form-label">Section</label>
                    <select name="section_id" id="section_id" class="form-select">
                        @foreach ($section_list as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" name="lat" placeholder="e.g. 8.216345" required>
                </div>

                <div class="mb-3">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" name="lng" placeholder="e.g. 126.351987" required>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select id="status" name="status" class="form-select" required>
                    <option value="available">Available</option>
                    <option value="sold">Sold</option>
                    <option value="reserved">Reserved</option>
                    <option value="quitclaim">Quit Claim</option>
                    <option value="restricted">Restricted</option>
                    <option value="sold_with_burial">Sold with Burial</option>
                    </select>
                </div>
                </div>

                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success">Save Plot</button>
                </div>
            </form>
            </div>
        </div>
    </div>

    <!-- Burial Modal -->
    <div class="modal fade" id="burialModal" tabindex="-1" aria-labelledby="burialModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header bg-primary text-white">
            <h5 class="modal-title" id="burialModalLabel"><i class="bi bi-person-plus"></i> Add Burial</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <form id="burialForm">
            <div class="modal-body">
            <input type="hidden" id="burialPlotId">

            <div class="mb-3">
                <label class="form-label">Plot Number</label>
                <input type="text" class="form-control" id="burialPlotNumber" readonly>
            </div>

            <div class="mb-3">
                <label for="deceasedName" class="form-label">Deceased Name</label>
                <input type="text" class="form-control" id="deceasedName" required placeholder="e.g. Juan Dela Cruz">
            </div>

            <div class="mb-3">
                <label for="deceasedName" class="form-label">Burial Date</label>
                <input type="date" class="form-control" id="burial_date">
            </div>
            <div class="mb-3">
                <label for="deceasedName" class="form-label">Notes</label>
                <input type="text" class="form-control" id="notes" placeholder="Remarks....">
            </div>


            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="burialStatus" name="burialStatus" class="form-select" required>
                    <option value="available">Available</option>
                    <option value="sold">Sold</option>
                    <option value="reserved">Reserved</option>
                    <option value="quitclaim">Quit Claim</option>
                    <option value="restricted">Restricted</option>
                    <option value="sold_with_burial">Sold with Burial</option>
                </select>
            </div>
            </div>

            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Burial</button>
            </div>
        </form>
        </div>
    </div>
    </div>


    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        var cemetery = {!! json_encode($cemetery) !!};

        var map = L.map('map', {
            minZoom: 14,
            maxZoom: 18
        }).setView([parseFloat(cemetery.lat), parseFloat(cemetery.lng)], 16);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles © Esri, USDA, USGS, GeoEye'
        }).addTo(map);

      

        var highlightLayer = L.layerGroup().addTo(map);
        var plotMarkers = {};
        var plotBounds = L.latLngBounds();

        @foreach($cemetery->sections as $section)
            @foreach($section->plots as $plot)
                var plotMarker = L.circleMarker([{{ $plot->lat }}, {{ $plot->lng }}], {
                    color: '{{ $plot->status_color }}',
                    fillColor: '{{ $plot->status_color }}',
                    fillOpacity: 0.8,
                    radius: 3
                }).addTo(map);

                var popupContent = 'Plot {{ $plot->number }} - {{ ucfirst($plot->status) }}';
                @if ($plot->burial)
                    popupContent += '<br>Deceased: {{ $plot->burial->deceased_name }}';
                @endif
                plotMarker.bindPopup(popupContent);

               plotMarker.on('click', function() {
                    const burialId = '{{ $plot->burial_id }}';
                    const status = '{{ $plot->status }}';

                    if (status === 'available') {
                        $('#burialPlotId').val({{ $plot->id }});
                        $('#burialPlotNumber').val('{{ $plot->number }}');
                        $('#burialModal').modal('show');
                    } else if (!burialId){
                         $('#burialPlotId').val({{ $plot->id }});
                        $('#burialPlotNumber').val('{{ $plot->number }}');
                        $('#burialModal').modal('show');
                    }
                     else {
                        if (confirm('This plot is currently "{{ $plot->status }}". Do you want to update its status?')) {
                            $('#plotNumber').val('{{ $plot->number }}');
                            $('#latitude').val('{{ $plot->lat }}');
                            $('#longitude').val('{{ $plot->lng }}');
                            $('#section_id').val('{{ $plot->section_id }}');
                            $('#status').val('{{ $plot->status }}');

                            $('#createPlotModalLabel').text('Update Plot {{ $plot->number }}');
                            $('#createPlotModal').modal('show');
                        }
                    }
                });


                plotMarkers[{{ $plot->id }}] = plotMarker;
                plotBounds.extend([{{ $plot->lat }}, {{ $plot->lng }}]);
            @endforeach
        @endforeach

        if (plotBounds.isValid()) map.fitBounds(plotBounds.pad(0.2));

        map.on('click', function(e) {
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);

            // Remove previous temporary marker if any
            if (window.tempMarker) {
                map.removeLayer(window.tempMarker);
            }

            // Add a marker where admin clicked
            window.tempMarker = L.marker(e.latlng).addTo(map)
                .bindPopup("New Plot Location").openPopup();

            // Fill in form fields
            $('#latitude').val(lat);
            $('#longitude').val(lng);

            // Show modal (optional)
            $('#createPlotModalLabel').text('Create New Plot at (' + lat + ', ' + lng + ')');
            $('#createPlotModal').modal('show');
        });


        // Search
        $('#searchButton').click(function() {
            var query = $('#searchInput').val().trim();
            if (!query) return alert('Enter a name to search.');

            highlightLayer.clearLayers();

            fetch('/cemetery/' + cemetery.id + '/search?query=' + encodeURIComponent(query))
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) return alert('No matching deceased found.');

                    var bounds = L.latLngBounds();
                    data.forEach(burial => {
                        var plot = burial.plot;
                        if (plot && plotMarkers[plot.id]) {
                            var highlight = L.circleMarker([plot.lat, plot.lng], {
                                color: 'yellow',
                                fillColor: 'yellow',
                                fillOpacity: 0.5,
                                radius: 12
                            }).addTo(highlightLayer);
                            highlight.bindPopup('Deceased: ' + burial.deceased_name + '<br>Plot: ' + plot.number);
                            bounds.extend([plot.lat, plot.lng]);
                        }
                    });
                    if (bounds.isValid()) map.fitBounds(bounds.pad(0.2));
                });
        });

        $('#createPlotForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                number: $('#plotNumber').val(),
                lat: $('#latitude').val(),
                lng: $('#longitude').val(),
                status: $('#status').val(),
                 section_id: $('#section_id').val(),
                cemetery_id: cemetery.id,
                "_token": "{{ csrf_token() }}"
            };

            $.ajax({
                url: "{{ url('cemetery/plot_create') }}",
                method: 'POST',
                data: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(response) {
                    alert('Plot created successfully!');
                    $('#createPlotModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Failed to create plot. Please check your inputs.');
                }
            });
        });
     

        $('#burialForm').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                plot_id: $('#burialPlotId').val(),
                deceased_name: $('#deceasedName').val(),
                burial_status: $('#burialStatus').val(),
                burial_date: $('#burial_date').val(),
                notes: $('#notes').val(),
                "_token": "{{ csrf_token() }}"
            };

            $.ajax({
                url: "{{ url('cemetery/add_burial')}}",
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert('Burial record saved successfully!');
                    $('#burialModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Failed to save burial. Please check your inputs.');
                }
            });
        });


        
    </script>
</body>
</html>
