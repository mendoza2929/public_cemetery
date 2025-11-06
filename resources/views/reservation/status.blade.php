<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservation Status</title>
<link rel="icon" href="{{ asset('assests/Logo.png') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    header {
        background: url("{{ asset('assets/images/header.jpg') }}") no-repeat center center/cover;
        height: 250px;
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
        background: linear-gradient(to right, rgba(2,44,86,0.7), rgba(3,32,53,0.7));
    }
    header h1 {
        position: relative;
        z-index: 1;
        font-size: 2em;
        font-weight: bold;
        text-transform: uppercase;
        padding: 0.5em 1em;
        background: rgba(255,255,255,0.15);
        border-radius: 12px;
        backdrop-filter: blur(6px);
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

    .status-box {
        max-width: 1000px;
        margin: 2em auto;
        background: white;
        padding: 2em;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }

    h2 {
        margin-bottom: 1.5em;
        color: #2c3e50;
        font-size: 1.8em;
    }

    /* Progress line container */
    .progress-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        margin: 2em 0;
        width: 100%;
    }

    /* Grey line behind steps */
    .progress-container::before {
        content: "";
        position: absolute;
        top: 80%;
        left: 8%;
        right: 8%;
        height: 8px;
        background: #ddd;
        transform: translateY(-50%);
        z-index: 0;
        border-radius: 4px;
    }

    /* Blue progress line */
    .progress-container::after {
        content: "";
        position: absolute;
        top: 80%;
        left: 8%;
        height: 8px;
        background: #3498db;
        transform: translateY(-50%);
        z-index: 0;
        border-radius: 4px;
        transition: width 0.6s ease-in-out;
        width: 
        @if($reservation->status == 'pending') 0%;
        @elseif($reservation->status == 'in_process') 50%;
        @elseif($reservation->status == 'done') 84%;
        @endif;
    }

    /* Each step (icon circle) */
    .step {
        background: #ddd;
        color: #666;
        border-radius: 50%;
        z-index: 1;
        width: 90px;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2em;
        box-shadow: 0 3px 8px rgba(0,0,0,0.2);
    }

    .step.active {
        background: #3498db;
        color: white;
    }

    .label {
        margin-top: 0.6em;
        font-weight: bold;
        color: #2c3e50;
        font-size: 1.1em;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .status-box {
            margin: 1em;
            padding: 1.5em;
        }
        .step {
            width: 70px;
            height: 70px;
            font-size: 1.5em;
        }
        h2 { font-size: 1.4em; }
        .label { font-size: 0.9em; }
    }

       footer {
            text-align: center;
            padding: 1em;
            background-color: #2c3e50;
            color: white;
            margin-top: 2em;
        }
</style>
</head>
<body>

<header>
    <h1>Track Reservation</h1>
</header>
    <nav>
        <a href="{{ url('/') }}">Home</a>
    </nav>
<div class="status-box">
    <h2>Reservation # {{ $reservation->reservation_no }}</h2>

    <div class="progress-container">
        <div>
            <div class="step {{ in_array($reservation->status, ['pending','in_process','done']) ? 'active' : '' }}">
                <i class="fa-solid fa-file-pen"></i>
            </div>
            <div class="label">Verify</div>
        </div>
        <div>
            <div class="step {{ in_array($reservation->status, ['in_process','done']) ? 'active' : '' }}">
                <i class="fa-solid fa-cross"></i>
            </div>
            <div class="label">For Review</div>
        </div>
        <div>
            <div class="step {{ $reservation->status == 'done' ? 'active' : '' }}">
                <i class="fa-solid fa-check"></i>
            </div>
            <div class="label">Done</div>
        </div>
    </div>
    
</div>
  <footer>
        <p>&copy; 2025 City Public Cemetery. All rights reserved.</p>
    </footer>

</body>
</html>
