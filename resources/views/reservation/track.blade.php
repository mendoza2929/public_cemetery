<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <title>Track Reservation - Public Cemetery</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        body {
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        header {
            background: url("{{ asset('assets/images/header.jpg') }}") no-repeat center center/cover;
            height: 220px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
        }
        header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to right, rgba(2, 44, 86, 0.7), rgba(3, 32, 53, 0.7));
        }
        header h1 {
            position: relative;
            z-index: 1;
            font-size: 2.2em;
            font-weight: bold;
            text-transform: uppercase;
            padding: 0.5em 1em;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            backdrop-filter: blur(8px);
        }
        nav {
            background-color: #2c3e50;
            padding: 1em;
            text-align: center;
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
        .main-content {
            max-width: 600px;
            margin: 2em auto;
            background: white;
            padding: 2.5em;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5em;
            color: #2c3e50;
        }
        input {
            width: 100%;
            padding: 1.2em;
            margin: 1em 0;
            border: 2px solid #ccc;
            border-radius: 8px;
            font-size: 1.1em;
        }
        button {
            width: 100%;
            padding: 1.2em;
            background: #3498db;
            border: none;
            color: white;
            font-weight: bold;
            font-size: 1.1em;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.3s;
        }
        button:hover {
            background: #2980b9;
        }
        footer {
            text-align: center;
            padding: 1em;
            background-color: #2c3e50;
            color: white;
            margin-top: 2em;
        }

        /* 📱 Mobile Responsive - Fullscreen Zoom */
        @media (max-width: 768px) {
            header h1 { font-size: 1.6em; }
            .main-content {
                width: 95%;
                margin: 1em auto;
                padding: 2em 1.5em;
                border-radius: 0; /* full width box */
            }
            input, button {
                font-size: 1.2em;
                padding: 1.3em;
            }
            h2 { font-size: 1.5em; }
        }
    </style>
</head>
<body>
    <header>
        <h1>Track Your Reservation</h1>
    </header>
    <nav>
        <a href="{{ url('/') }}">Home</a>
    </nav>
    <div class="main-content">
        <h2>Enter Reservation Number</h2>
        @if(session('error'))
            <p style="color:red; text-align:center;">{{ session('error') }}</p>
        @endif
        <form method="POST" action="{{ url('reservation/track') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <input type="text" name="reservation_no" placeholder="Reservation Number (e.g. RES-2025******)" required>
            <button type="submit">Check Status</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2025 City Public Cemetery. All rights reserved.</p>
    </footer>
</body>
</html>
