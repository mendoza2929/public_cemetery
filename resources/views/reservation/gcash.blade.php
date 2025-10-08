<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gcash Payment - City Public Cemetery</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

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
            height: 250px;
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
            font-size: 2em;
            padding: 0.5em 1em;
            border-radius: 15px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
            text-align: center;
        }

        /* NAV */
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

        /* MAIN CONTENT */
        .main-container {
            max-width: 600px;
            margin: 2em auto;
            background: white;
            padding: 2em;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }

        h2 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 1em;
        }

        ul {
            list-style: none;
            padding: 0;
            margin-bottom: 1.5em;
        }

        ul li {
            background: #f4f4f4;
            padding: 0.75em 1em;
            margin-bottom: 0.5em;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            width: 100%;
            padding: 1em;
            border: none;
            border-radius: 8px;
            background-color: #3498db;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-bottom: 0.5em;
        }

        button:hover {
            background-color: #2980b9;
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
        @media (max-width: 480px) {
            .main-container {
                margin: 1em;
                padding: 1.5em;
            }
            header { height: 180px; }
            header h1 { font-size: 1.4em; padding: 0.4em 0.8em; }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header>
        <h1>Gcash Payment</h1>
    </header>

    <!-- NAV -->
    <nav>
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('reservation') }}">Back to Reservation</a>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="main-container">
        <h2>Send Payment via Gcash</h2>
    <p style="text-align:center;">
    Please send your payment to: <b>09123456789</b>
    </p>
    <p style="text-align:center; margin-top:1em;">
        <img src="{{ asset('assests/gcash.png') }}" alt="Gcash QR Code" style="max-width:200px; width:100%; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.2);">
    </p>


        <h4>Reservation Info</h4>
        <ul>
            <li><strong>Name:</strong> {{ $data['name'] }}</li>
            <li><strong>Address:</strong> {{ $data['address'] }}</li>
            <li><strong>Contact Number:</strong> {{ $data['number'] }}</li>
        </ul>

        <form action="{{ url('reservation') }}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="hidden" name="name" value="{{ $data['name'] }}">
            <input type="hidden" name="address" value="{{ $data['address'] }}">
            <input type="hidden" name="number" value="{{ $data['number'] }}">
            <input type="hidden" name="plot_id" value="{{ $data['plot_id'] }}">
            <input type="hidden" name="payment_method" value="Gcash">

            <button type="submit">Confirm Reservation</button>
        </form>
    </div>

    <!-- FOOTER -->
    <footer>
        <p>&copy; 2025 City Public Cemetery. All rights reserved.</p>
    </footer>
</body>
</html>
