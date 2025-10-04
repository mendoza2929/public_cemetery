<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Important for mobile scaling -->
    <title>Bislig City Reservation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f4f4f4;
            color: #333;
            line-height: 1.6;
        }

        /* Reuse same header style as landing */
        header {
            background: url("{{ asset('assets/images/header.jpg') }}") no-repeat center center/cover;
            height: 200px;
            display: flex;
            justify-content: center;
            align-items: center;
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
            font-size: 1.8em;
            background: rgba(255,255,255,0.15);
            padding: 0.5em 1em;
            border-radius: 10px;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        /* Reservation Form */
        .reservation-box {
            max-width: 600px;
            margin: 2em auto;
            background: white;
            padding: 2em;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .reservation-box h2 {
            margin-bottom: 1.5em;
            color: #2c3e50;
            text-align: center;
        }

        .reservation-box input,
        .reservation-box select {
            width: 100%;
            padding: 1em;
            margin: 0.8em 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        .reservation-box input:focus,
        .reservation-box select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 6px rgba(52,152,219,0.3);
        }

        .reservation-box button {
            width: 100%;
            padding: 1.2em;
            background: #3498db;
            border: none;
            color: white;
            font-weight: bold;
            font-size: 1rem;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .reservation-box button:hover {
            background: #2980b9;
        }

        footer {
            text-align: center;
            padding: 1em;
            background-color: #2c3e50;
            color: white;
            margin-top: 2em;
        }

        /* 📱 Mobile Responsive */
        @media (max-width: 768px) {
            header {
                height: 150px;
            }
            header h1 {
                font-size: 1.5em;
            }
            .reservation-box {
                margin: 1em;
                padding: 1.5em;
                border-radius: 0; /* full-width style */
                max-width: 100%;
                box-shadow: none;
            }
            .reservation-box input,
            .reservation-box select,
            .reservation-box button {
                font-size: 1.1rem;
                padding: 1.2em;
            }
        }

        @media (max-width: 480px) {
            header {
                height: 120px;
            }
            header h1 {
                font-size: 1.2em;
                padding: 0.4em 0.8em;
            }
            .reservation-box {
                margin: 0;
                padding: 1.2em;
                min-height: 100vh; /* form feels full screen */
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
        }
    </style>
</head>
<body>

<header>
    <h1>Bislig City Public Cemetery Reservation</h1>
</header>

<div class="reservation-box">
    <h2>Reserve a Plot</h2>
    <form action="{{ url('reservation') }}" method="POST">
        
        <input type="text" name="name" placeholder="Your Full Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <select name="cemetery" required>
            <option value="">Select Cemetery</option>
            <option value="1">Cemetery 1</option>
            <option value="2">Cemetery 2</option>
        </select>
        <input type="date" name="date" required>
        <button type="submit">Reserve Now</button>
    </form>
</div>

<footer>
    <p>&copy; 2025 Bislig City Public Cemetery. All rights reserved.</p>
</footer>

</body>
</html>
