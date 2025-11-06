<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Reservation - City Public Cemetery</title>
    <link rel="icon" href="{{ asset('assests/Logo.png') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        * {
            margin: 0; padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        /* HEADER */
        header {
            background: url("{{ asset('assets/images/header.jpg') }}") no-repeat center center/cover;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            position: relative;
        }

        header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(2, 44, 86, 0.7), rgba(3, 32, 53, 0.7));
        }

        header h1 {
            position: relative;
            z-index: 1;
            font-size: 2.2em;
            padding: 0.6em 1.2em;
            border-radius: 15px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
            text-align: center;
        }

        /* NAVIGATION */
        nav {
            background-color: #2c3e50;
            text-align: center;
            padding: 1em;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 1em;
            font-weight: bold;
        }

        nav a:hover {
            color: #3498db;
        }

        /* MAIN CONTENT SPLIT */
        .main-container {
            display: flex;
            flex-wrap: wrap;
            max-width: 1200px;
            margin: 2em auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            overflow: hidden;
        }

        .form-section {
            flex: 1 1 40%;
            padding: 2em;
        }

        .form-section h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 1em;
        }

        .form-section input,
        .form-section select,
        .form-section button {
            width: 100%;
            padding: 1em;
            margin-bottom: 1em;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        .form-section input:focus,
        .form-section select:focus {
            border-color: #3498db;
            outline: none;
        }

        .form-section button {
            background-color: #3498db;
            color: white;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .form-section button:hover {
            background-color: #2980b9;
        }

        /* MAP */
        .map-section {
            flex: 1 1 60%;
            height: 500px;
            width: 100%;
            min-height: 300px; /* Ensures visible map */
        }

        /* FOOTER */
        footer {
            text-align: center;
            padding: 1.5em;
            background-color: #2c3e50;
            color: white;
            margin-top: 2em;
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .main-container {
                flex-direction: column;
                margin: 1em;
            }
            .form-section {
                padding: 1.5em;
            }
            .map-section {
                height: 400px !important;
                min-height: 350px;
            }
        }

        @media (max-width: 768px) {
            header { height: 200px; }
            header h1 { font-size: 1.5em; padding: 0.5em; }
            .map-section { height: 400px !important; }
            .form-section input,
            .form-section select,
            .form-section button {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            nav a {
                display: block;
                margin: 0.5em 0;
            }
            .main-container {
                margin: 0.5em;
            }
            .map-section {
                height: 320px !important;
                min-height: 300px;
            }
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header>
        <h1>City Public Cemetery Reservation</h1>
    </header>

    <!-- NAV -->
    <nav>
        <a href="{{ url('/') }}">Home</a>
    </nav>

    <!-- FORM + MAP -->
    <div class="main-container">
        <div class="map-section" id="map"></div>
        <div class="form-section">
        <h2>Reserve a Plot</h2>
        <form action="{{ url('reservation') }}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="name_deceased" placeholder="Name of Deceased" required>
            <input type="text" name="relationship" placeholder="Relationship" required>
            <input type="text" name="address" placeholder="Address" required>
            <input type="number" name="number" placeholder="Contact Number" required>

            <select name="plot_id" required>
                <option value="">Select Available Plot</option>
                @foreach ($cemetery->sections as $section)
                    @foreach ($section->plots as $plot)
                        <option value="{{ $plot->id }}">
                            Section {{ $section->name }} - Plot {{ $plot->number }}
                        </option>
                    @endforeach
                @endforeach
            </select>
           <select name="payment_method" id="payment_method" required>
                <option value="">Select Payment Method</option>
                <option value="Cash">Cash</option>
                <option value="Gcash">Gcash</option>
                <option value="Paymaya">PayMaya</option>
                <option value="BankTransfer">Bank Transfer</option>
            </select>

            <button type="submit">Reserve Now</button>
        </form>
    </div>


    </div>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2025 City Public Cemetery. All rights reserved.</p>
    </footer>

    <!-- MAP SCRIPT -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        var cemetery = {!! json_encode($cemetery) !!};

        var map = L.map('map', {
            minZoom: 14,
            maxZoom: 18
        }).setView([parseFloat(cemetery.lat), parseFloat(cemetery.lng)], 16);

        // Satellite layer
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri'
        }).addTo(map);

        // Add markers for available plots
        @foreach($cemetery->sections as $section)
            @foreach($section->plots as $plot)
                var marker = L.circleMarker([{{ $plot->lat }}, {{ $plot->lng }}], {
                    color: 'green',
                    fillColor: 'green',
                    fillOpacity: 0.8,
                    radius: 4
                }).addTo(map);
                marker.bindTooltip("Plot {{ $plot->number }} ({{ $plot->price }})", {
                    direction: 'top'
                });
            @endforeach
        @endforeach

    
        window.addEventListener('load', function() {
            setTimeout(() => {
                map.invalidateSize();
            }, 500);
        });

        window.addEventListener('resize', function() {
            map.invalidateSize();
        });

         document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault();

            const paymentMethod = document.getElementById('payment_method').value;
            const formData = new FormData(this);
            const query = new URLSearchParams(formData).toString();

            let redirectUrl = '';

            if (paymentMethod === 'Gcash') {
                redirectUrl = '/reservation/gcash?' + query;
            } else if (paymentMethod === 'Paymaya') {
                redirectUrl = '/reservation/paymaya?' + query;
            } else if (paymentMethod === 'BankTransfer') {
                redirectUrl = '/reservation/bank?' + query;
            } else {
           
                this.submit();
                return;
            }
            window.location.href = redirectUrl;
        });

    </script>
</body>
</html>
