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
        /* ... keep your existing CSS ... */
        body { background:#f4f6f9; font-family:'Poppins',sans-serif; margin:0; }
        .sidebar { height:100vh; background:linear-gradient(180deg,#2c3e50,#1a252f); color:#fff; position:fixed; width:240px; display:flex; flex-direction:column; }
        .sidebar h2 { text-align:center; padding:1.5rem 0; font-size:1.3rem; border-bottom:1px solid rgba(255,255,255,.1); }
        .sidebar a { color:#ddd; padding:12px 20px; display:block; text-decoration:none; transition:.3s; }
        .sidebar a:hover,.sidebar a.active { background:#3498db; color:white; }
        .content { margin-left:240px; padding:2rem; min-height:100vh; }
        .card { border:none; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,.1); }
        .map-container { position:relative; height:70vh; border-radius:10px; overflow:hidden; }
        #map { width:100%; height:100%; }
        .search-bar { display:flex; gap:10px; margin-bottom:1rem; }
        .search-bar input { flex:1; }
        .legend { display:flex; gap:20px; margin-top:15px; }
        .legend-item { display:flex; align-items:center; gap:6px; }
        .plot { width:18px; height:18px; border-radius:3px; border:1px solid #ccc; }
        .available { background:#30a52aff; }
        .sold { background:#ff000053; }
        .reserved { background:#FFFF00; }
        .quitclaim { background:#ff00e6ff; }
        .restricted { background:#ff0000ff; }
        .sold_with_burial { background:#800080; }
    </style>
</head>

<body>
<!-- ==================== SIDEBAR ==================== -->
<div class="sidebar">
    <h2><img src="{{ asset('assests/Logo.png') }}" alt="Logo" style="max-width:200px;width:25%;border-radius:10px;box-shadow:0 4px 8px rgba(0,0,0,.2);"></h2>
    <a href="{{ url('cemetery/admin') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="{{ url('cemetery/1/map') }}" class="active"><i class="bi bi-map"></i> Plots</a>
    <a href="{{ url('cemetery/reservation') }}"><i class="bi bi-calendar-check"></i> Reservations</a>
    <a href="{{ url('auth/logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
</div>

<!-- ==================== MAIN CONTENT ==================== -->
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Plots Overview</h3>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#plotModal">
            <i class="bi bi-plus-circle"></i> Create Plot
        </button>
    </div>

    <div class="card p-4">
        <h5 class="mb-3">{{ $cemetery->name }}</h5>

        <div class="search-bar">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by deceased name...">
            <button id="searchButton" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
        </div>

        <div class="map-container mb-3"><div id="map"></div></div>

        <div class="legend">
            <div class="legend-item"><div class="plot available"></div> Available</div>
            <div class="legend-item"><div class="plot sold"></div> Sold</div>
            <div class="legend-item"><div class="plot reserved"></div> Reserved</div>
            <div class="legend-item"><div class="plot quitclaim"></div> Quit Claim</div>
            <div class="legend-item"><div class="plot restricted"></div> Restricted</div>
            <div class="legend-item"><div class="plot sold_with_burial"></div> Sold with Burial</div>
        </div>
    </div>
</div>

<!-- ==================== PLOT MODAL (Create / Update) ==================== -->
<div class="modal fade" id="plotModal" tabindex="-1" aria-labelledby="plotModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="plotModalLabel">Create New Plot</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="plotForm">
                <input type="hidden" id="plotId" name="id">
                <input type="hidden" id="plotLat" name="lat">
                <input type="hidden" id="plotLng" name="lng">

                <div class="modal-body">

                    <!-- STEP 1: STATUS SELECT -->
                    <div id="stepStatus">
                        <div class="mb-3">
                            <label class="form-label">Burial Status <span class="text-danger">*</span></label>
                            <select id="statusSelect" class="form-select" required>
                                <option value="" disabled selected>-- Choose status --</option>
                                <option value="available">AVAILABLE (Vacant Lot)</option>
                                <option value="sold">SOLD (No Burial Yet)</option>
                                <option value="sold_with_burial">SOLD WITH BURIAL</option>
                                <option value="reserved">RESERVED</option>
                                <option value="quitclaim">QUITCLAIM</option>
                                <option value="restricted">RESTRICTED</option>
                            </select>
                        </div>
                        <button type="button" id="nextToFields" class="btn btn-primary">Next →</button>
                    </div>

                    <!-- STEP 2: DYNAMIC FIELDS -->
                    <div id="stepFields" class="d-none"></div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="prevToStatus" class="btn btn-outline-secondary d-none">← Back</button>
                    <button type="submit" id="submitPlot" class="btn btn-success d-none">Save Plot</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ==================== JS ==================== -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // ---------- GLOBAL ----------
    const cemetery = {!! json_encode($cemetery) !!};
    const $modal        = $('#plotModal');
    const $stepStatus   = $('#stepStatus');
    const $stepFields   = $('#stepFields');
    const $prevBtn      = $('#prevToStatus');
    const $nextBtn      = $('#nextToFields');
    const $submitBtn    = $('#submitPlot');

    // ---------- MAP ----------
    const map = L.map('map', { minZoom:14, maxZoom:18 })
        .setView([parseFloat(cemetery.lat), parseFloat(cemetery.lng)], 16);

    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles © Esri, USDA, USGS, GeoEye'
    }).addTo(map);

    const highlightLayer = L.layerGroup().addTo(map);
    const plotMarkers = {};
    const plotBounds = L.latLngBounds();

    // ---------- PLOT MARKERS ----------
    @foreach($cemetery->sections as $section)
        @foreach($section->plots as $plot)
            (function(){
                const marker = L.circleMarker([{{ $plot->lat }}, {{ $plot->lng }}], {
                    color: '{{ $plot->status_color }}',
                    fillColor: '{{ $plot->status_color }}',
                    fillOpacity: 0.8,
                    radius: 3
                }).addTo(map);

                let popup = `Plot {{ $plot->number }} - {{ ucfirst($plot->status) }}`;
                @if($plot->burial)
                    popup += `<br>Deceased: {{ $plot->burial->deceased_name }}`;
                @endif
                marker.bindPopup(popup);

                // ---- CLICK ON EXISTING MARKER (EDIT) ----
                marker.on('click', function () {
                    openPlotModal(null, null, {
                        id: {{ $plot->id }},
                        number: '{{ $plot->number }}',
                        lat: {{ $plot->lat }},
                        lng: {{ $plot->lng }},
                        status: '{{ $plot->status }}',
                        section_id: {{ $plot->section_id }},
                        // add any extra data you already have (owner, deceased, etc.)
                    });
                });

                plotMarkers[{{ $plot->id }}] = marker;
                plotBounds.extend([{{ $plot->lat }}, {{ $plot->lng }}]);
            })();
        @endforeach
    @endforeach

    if (plotBounds.isValid()) map.fitBounds(plotBounds.pad(0.2));

    // ---------- MAP CLICK (NEW PLOT) ----------
    map.on('click', function (e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        if (window.tempMarker) map.removeLayer(window.tempMarker);
        window.tempMarker = L.marker(e.latlng).addTo(map)
            .bindPopup('New Plot Location').openPopup();

        openPlotModal(lat, lng);
    });

    // ---------- CREATE PLOT BUTTON ----------
    $('[data-bs-target="#plotModal"]').on('click', () => openPlotModal());

    // ---------- OPEN MODAL (CREATE / UPDATE) ----------
    function openPlotModal(lat = null, lng = null, plot = null) {
        // reset
        $modal.find('form')[0].reset();
        $('#plotId').val('');
        $stepFields.addClass('d-none').empty();
        $stepStatus.removeClass('d-none');
        $prevBtn.addClass('d-none');
        $submitBtn.addClass('d-none');

        if (plot) { // EDIT
            $('#plotModalLabel').text(`Update Plot ${plot.number}`);
            $('#plotId').val(plot.id);
            $('#statusSelect').val(plot.status);
            $('#plotLat').val(plot.lat);
            $('#plotLng').val(plot.lng);
            // go straight to fields
            $nextBtn.trigger('click');
            $('[name="number"]').val(plot.number);
            $('[name="section_id"]').val(plot.section_id);
        } else { // CREATE
            $('#plotModalLabel').text('Create New Plot');
            $('#plotLat').val(lat);
            $('#plotLng').val(lng);
        }

        $modal.modal('show');
    }

    // ---------- FIELD TEMPLATES ----------
    const commonFields = () => `
        <div class="mb-3">
            <label class="form-label">Plot ID <span class="text-danger">*</span></label>
            <input type="text" class="form-control" name="number" required placeholder="e.g. 101A">
        </div>
        <div class="mb-3">
            <label class="form-label">Section <span class="text-danger">*</span></label>
            <select name="section_id" class="form-select" required>
                @foreach ($section_list as $section)
                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Coordinates</label>
            <div class="row">
                <div class="col"><input type="text" class="form-control" id="showLat" readonly></div>
                <div class="col"><input type="text" class="form-control" id="showLng" readonly></div>
            </div>
        </div>
    `;

    const templates = {
        available: () => `${commonFields()}
            <div class="mb-3"><label class="form-label">Remarks</label><textarea class="form-control" name="remarks" rows="2"></textarea></div>`,

        sold: () => `${commonFields()}
            <div class="mb-3"><label class="form-label">Owner / Buyer Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="owner_name" required></div>
            <div class="mb-3"><label class="form-label">Contact Number</label><input type="text" class="form-control" name="owner_contact"></div>
            <div class="mb-3"><label class="form-label">Date Purchased</label><input type="date" class="form-control" name="date_purchased"></div>
            <div class="mb-3"><label class="form-label">Transaction Type</label>
                <select class="form-select" name="transaction_type">
                    <option value="online">Online</option>
                    <option value="over-the-counter">Over-the-Counter</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Remarks</label><textarea class="form-control" name="remarks" rows="2"></textarea></div>`,

        sold_with_burial: () => `${commonFields()}
            <fieldset class="border p-3 mb-3"><legend class="w-auto">Owner Information</legend>
                <div class="mb-3"><label class="form-label">Owner / Buyer Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="owner_name" required></div>
                <div class="mb-3"><label class="form-label">Contact Number</label><input type="text" class="form-control" name="owner_contact"></div>
                <div class="mb-3"><label class="form-label">Date Purchased</label><input type="date" class="form-control" name="date_purchased"></div>
            </fieldset>

            <fieldset class="border p-3 mb-3"><legend class="w-auto">Deceased Information (1-3)</legend>
                <div id="deceasedContainer">
                    <div class="deceased-row mb-3 p-3 border rounded">
                        <h6>Deceased #1</h6>
                        <div class="row g-2">
                            <div class="col-md-6"><input type="text" class="form-control" name="deceased[0][name]" placeholder="Name" required></div>
                            <div class="col-md-3"><input type="date" class="form-control" name="deceased[0][dob]" placeholder="DOB"></div>
                            <div class="col-md-3"><input type="date" class="form-control" name="deceased[0][dod]" placeholder="DOD"></div>
                            <div class="col-md-3"><input type="date" class="form-control" name="deceased[0][burial_date]" placeholder="Burial Date"></div>
                            <div class="col-md-3"><input type="number" class="form-control age-auto" placeholder="Age (auto)" readonly></div>
                            <div class="col-md-3">
                                <select class="form-select" name="deceased[0][sex]">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" id="addDeceased" class="btn btn-sm btn-outline-primary">+ Add another deceased (max 3)</button>
            </fieldset>

            <div class="mb-3"><label class="form-label">Remarks</label><textarea class="form-control" name="remarks" rows="2"></textarea></div>`,

        reserved: () => `${commonFields()}
            <div class="mb-3"><label class="form-label">Applicant Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="applicant_name" required></div>
            <div class="mb-3"><label class="form-label">Contact Number</label><input type="text" class="form-control" name="applicant_contact"></div>
            <div class="mb-3"><label class="form-label">Reservation Date</label><input type="date" class="form-control" name="reservation_date" value="{{ date('Y-m-d') }}"></div>
            <div class="mb-3"><label class="form-label">Valid Until (max 3 days)</label><input type="date" class="form-control" name="reservation_expiry" readonly></div>
            <div class="mb-3"><label class="form-label">Payment Status</label>
                <select class="form-select" name="payment_status">
                    <option value="pending">Pending</option>
                    <option value="complete">Complete</option>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Remarks</label><textarea class="form-control" name="remarks" rows="2"></textarea></div>`,

        quitclaim: () => `${commonFields()}
            <div class="mb-3"><label class="form-label">Previous Owner Name <span class="text-danger">*</span></label><input type="text" class="form-control" name="prev_owner" required></div>
            <div class="mb-3"><label class="form-label">Quitclaim Date</label><input type="date" class="form-control" name="quitclaim_date"></div>
            <div class="mb-3"><label class="form-label">Reason for Quitclaim</label><textarea class="form-control" name="remarks" rows="2"></textarea></div>`,

        restricted: () => `${commonFields()}
            <div class="mb-3"><label class="form-label">Type / Reason <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="restriction_reason" required placeholder="e.g. Pathway, Admin Area, Memorial Zone">
            </div>
            <div class="mb-3"><label class="form-label">Remarks</label><textarea class="form-control" name="remarks" rows="2"></textarea></div>`
    };

    // ---------- NEXT BUTTON ----------
    $nextBtn.on('click', () => {
        const status = $('#statusSelect').val();
        if (!status) return alert('Please select a status first.');

        $stepFields.html(templates[status]()).removeClass('d-none');
        $stepStatus.addClass('d-none');
        $prevBtn.removeClass('d-none');
        $submitBtn.removeClass('d-none');

        // show coordinates
        $('#showLat').val($('#plotLat').val());
        $('#showLng').val($('#plotLng').val());

        // reserved: auto-fill expiry
        if (status === 'reserved') {
            const today = new Date().toISOString().split('T')[0];
            const expiry = new Date(Date.now() + 3*24*60*60*1000).toISOString().split('T')[0];
            $('[name="reservation_date"]').val(today);
            $('[name="reservation_expiry"]').val(expiry);
        }

        // sold_with_burial: add-more deceased
        if (status === 'sold_with_burial') initDeceased();
    });

    // ---------- BACK BUTTON ----------
    $prevBtn.on('click', () => {
        $stepFields.addClass('d-none').empty();
        $stepStatus.removeClass('d-none');
        $prevBtn.addClass('d-none');
        $submitBtn.addClass('d-none');
    });

    // ---------- DECEASED ADD-MORE ----------
    function initDeceased() {
        let count = 1;
        $('#addDeceased').off().on('click', function () {
            if (count >= 3) return alert('Maximum 3 deceased allowed.');
            count++;
            const html = `
                <div class="deceased-row mb-3 p-3 border rounded">
                    <h6>Deceased #${count}</h6>
                    <div class="row g-2">
                        <div class="col-md-6"><input type="text" class="form-control" name="deceased[${count-1}][name]" placeholder="Name"></div>
                        <div class="col-md-3"><input type="date" class="form-control" name="deceased[${count-1}][dob]" placeholder="DOB"></div>
                        <div class="col-md-3"><input type="date" class="form-control" name="deceased[${count-1}][dod]" placeholder="DOD"></div>
                        <div class="col-md-3"><input type="date" class="form-control" name="deceased[${count-1}][burial_date]" placeholder="Burial Date"></div>
                        <div class="col-md-3"><input type="number" class="form-control age-auto" placeholder="Age (auto)" readonly></div>
                        <div class="col-md-3">
                            <select class="form-select" name="deceased[${count-1}][sex]">
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                    </div>
                </div>`;
            $('#deceasedContainer').append(html);
        });

        // auto-age
        $(document).off('change', 'input[name*="[dob]"], input[name*="[dod]"]').on('change', 'input[name*="[dob]"], input[name*="[dod]"]', function () {
            const row = $(this).closest('.deceased-row');
            const dob = row.find('input[name*="[dob]"]').val();
            const dod = row.find('input[name*="[dod]"]').val() || new Date().toISOString().split('T')[0];
            if (dob) {
                const age = new Date(dod).getFullYear() - new Date(dob).getFullYear();
                row.find('.age-auto').val(age);
            }
        });
    }

    // ---------- SUBMIT ----------
    $('#plotForm').on('submit', function (e) {
    e.preventDefault();

    const isUpdate = !!$('#plotId').val();
    const url = isUpdate
        ? `{{ url('cemetery/plot_update') }}/${$('#plotId').val()}`
        : `{{ url('cemetery/plot_create') }}`;

    const fd = new FormData(this);

    // ALWAYS add these core fields
    fd.append('cemetery_id', cemetery.id);
    fd.append('number', $('[name="number"]').val().trim());   // <-- PLOT ID
    fd.append('section_id', $('[name="section_id"]').val());
    fd.append('status', $('#statusSelect').val());

    // Only add coordinates on create
    if (!isUpdate) {
        fd.append('lat', $('#plotLat').val());
        fd.append('lng', $('#plotLng').val());
    }

    fd.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url,
        method: isUpdate ? 'PUT' : 'POST',
        data: fd,
        processData: false,
        contentType: false,
        success: function () {
            alert(isUpdate ? 'Plot updated!' : 'Plot created!');
            $modal.modal('hide');
            location.reload();
        },
        error: function (xhr) {
            alert(xhr.responseJSON?.message || 'Error saving plot.');
        }
    });
});

    // ---------- SEARCH ----------
    $('#searchButton').click(function () {
        const query = $('#searchInput').val().trim();
        if (!query) return alert('Enter a name.');

        highlightLayer.clearLayers();

        fetch(`/cemetery/${cemetery.id}/search?query=${encodeURIComponent(query)}`)
            .then(r => r.json())
            .then(data => {
                if (!data.length) return alert('No results.');

                const bounds = L.latLngBounds();
                data.forEach(b => {
                    const p = b.plot;
                    if (p && plotMarkers[p.id]) {
                        const h = L.circleMarker([p.lat, p.lng], {
                            color: 'yellow', fillColor: 'yellow', fillOpacity: .5, radius: 12
                        }).addTo(highlightLayer);
                        h.bindPopup(`Deceased: ${b.deceased_name}<br>Plot: ${p.number}`);
                        bounds.extend([p.lat, p.lng]);
                    }
                });
                if (bounds.isValid()) map.fitBounds(bounds.pad(.2));
            });
    });
</script>
</body>
</html>