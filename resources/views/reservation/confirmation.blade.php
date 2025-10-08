<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Confirmation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
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
            background: rgba(2,44,86,0.7);
        }

        header h1 {
            position: relative;
            z-index: 1;
            font-size: 2em;
            padding: 0.5em 1em;
            border-radius: 15px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
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
        .container {
            max-width: 600px;
            margin: 2em auto;
            background: white;
            padding: 2em;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            text-align: center;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 1em;
        }

        /* Reservation Info List */
        .reservation-info {
            list-style: none; /* Remove bullets */
            padding: 0;
            margin: 1em 0;
            text-align: left;
        }

        .reservation-info li {
            background: #f9f9f9;
            padding: 0.75em 1em;
            margin-bottom: 0.5em;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .note {
            background: #fff3cd;
            color: #856404;
            padding: 1em;
            border-radius: 8px;
            margin-top: 1em;
            border: 1px solid #ffeeba;
        }

        button {
            width: 100%;
            padding: 1em;
            margin-top: 1em;
            border: none;
            border-radius: 8px;
            background-color: #3498db;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
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
            .container {
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
        <h1>Reservation Confirmed</h1>
    </header>

    <!-- NAV -->
    <nav>
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('reservation') }}">New Reservation</a>
    </nav>

    <!-- MAIN CONTENT -->
    <div class="container">
        <h2>Your Reservation Number:</h2>
        <p style="font-size:1.5em; font-weight:bold; color:#2c3e50;">{{ $reservation->reservation_no }}</p>

        <ul class="reservation-info">
            <li><strong>Name:</strong> {{ $reservation->name }}</li>
            <li><strong>Address:</strong> {{ $reservation->address }}</li>
            <li><strong>Contact Number:</strong> {{ $reservation->number }}</li>
            <li><strong>Payment Method:</strong> {{ $reservation->payment_method }}</li>
            <li><strong>Status:</strong> {{ ucfirst($reservation->status) }}</li>
            <li><strong>Date:</strong> {{ $reservation->date }}</li>
        </ul>

        <div class="note">
            Please screenshot or take note of your reservation number. You will need this to check the status of your reservation.
        </div>

        <form action="{{ url('/') }}">
            <button type="submit">Back to Home</button>
        </form>
    </div>

    <!-- FOOTER -->
    <footer>
        &copy; 2025 City Public Cemetery. All rights reserved.
    </footer>
</body>
</html>
