<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Cemetery - Welcome</title>
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
    height: 400px;
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
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to right, rgba(2, 44, 86, 0.7), rgba(3, 32, 53, 0.7));
}


header h1 {
    position: relative;
    z-index: 1;
    font-size: 3em;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
    padding: 0.7em 1.5em;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 15px;
    backdrop-filter: blur(8px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}


/* Smooth fade-in animation */
@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
        header > * {
            position: relative;
            z-index: 1;
        }
        header h1 {
            font-size: 2.5em;
        }
        nav {
            background-color: #2c3e50;
            padding: 1em;
        }
        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 2em;
        }
        nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        nav a:hover {
            color: #3498db;
        }
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2em;
            display: flex;
            flex-wrap: wrap;
            gap: 2em;
        }
        .section {
            flex: 1;
            min-width: 300px;
            background: white;
            padding: 1.5em;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            color: #2c3e50;
            margin-bottom: 1em;
        }
        .section p {
            margin-bottom: 1em;
        }
        .btn {
            display: inline-block;
            padding: 0.5em 1em;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
        footer {
            text-align: center;
            padding: 1em;
            background-color: #2c3e50;
            color: white;
            margin-top: 2em;
        }

        .modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Modal box */
.modal-content {
    background: white;
    padding: 2em;
    border-radius: 10px;
    max-width: 500px;
    width: 90%;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    animation: fadeIn 0.3s ease-in-out;
}

/* Buttons inside modal */
.modal-content a {
    display: block;
    margin: 1em 0;
    padding: 0.8em;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    transition: background 0.3s;
}

.modal-content a:hover {
    background: #2980b9;
}

/* Close button */
.close-btn {
    position: absolute;
    top: 15px;
    right: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #333;
    cursor: pointer;
}

/* Animation */
@keyframes fadeIn {
    from {opacity: 0; transform: scale(0.9);}
    to {opacity: 1; transform: scale(1);}
}

    </style>
</head>
<body>
    <header>
        <h1>Welcome to Bislig City Public Cemetery</h1>
    </header>
    <div class="main-content">
        <div class="section">
            <h2>About Us</h2>
            <p>Established to honor and remember, Bislig City Public Cemetery provides a peaceful resting place for our community. Learn more about our history and dedication to care.</p>
        </div>
        <div class="section">
            <h2>Services</h2>
            <p>We offer burial plots, memorials, and guidance for families. Explore our services to find the support you need during difficult times.</p>
            <div style="text-align: center;">
                <a href="#services" class="btn">View Services</a>
            </div>
        </div>
        <div class="section">
            <h2>Contact Us</h2>
            <p>Reach out for inquiries or to schedule a reservation. We’re here to assist you 24/7.</p>
        </div>
    </div>
    <footer>
        <p>&copy; 2025 Bislig City Public Cemetery. All rights reserved.</p>
    </footer>


    <div id="servicesModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Our Services</h2>
       <a href="{{ url('reservation/track') }}">Reservation Number</a>
       <a href="{{ url('reservation') }}">Reservation</a>

       <a href="{{ url('cemetery/' . $encryptedId) }}">View Cemetery</a>
    </div>
</div>
</body>
<script>
    // Get modal & button
    const modal = document.getElementById("servicesModal");
    const btn = document.querySelector("a[href='#services']");
    const closeBtn = document.querySelector(".close-btn");

    // Open modal
    btn.addEventListener("click", function(e) {
        e.preventDefault();
        modal.style.display = "flex";
    });

    // Close modal
    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Close if clicked outside modal
    window.addEventListener("click", function(e) {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
</script>
</html>