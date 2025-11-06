<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Public Cemetery - Welcome</title>
  <link rel="icon" href="{{ asset('assests/Logo.png') }}">
  
  <style>
    /* Reset & Base Styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: Arial, sans-serif;
    }

    /* Page Background */
    body {
      line-height: 1.6;
      color: #333;
      background: url("{{ asset('assests/Background1.jpg') }}") no-repeat center center fixed;
      background-size: cover;
      backdrop-filter: blur(2px);
    }

    /* Header Section */
    header {
      background: url("{{ asset('assests/Background1.jpg') }}") no-repeat center center/cover;
      height: 400px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: white;
      position: relative;
      overflow: hidden;
    }

    header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      z-index: 0;
    }

    /* Header Logo */
    header img {
      max-width: 250px;
      width: 60%;
      height: auto;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
      position: relative;
      z-index: 1;
      animation: fadeInDown 1s ease-in-out;
    }

    /* Header Title */
    header h1 {
      position: relative;
      z-index: 1;
      font-size: 2.5em;
      margin-top: 20px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 12px;
      padding: 0.5em 1em;
      backdrop-filter: blur(5px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    /* Navigation */
    nav {
      background-color: rgba(44, 62, 80, 0.9);
      padding: 1em;
    }
    nav ul {
      list-style: none;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 2em;
    }
    nav a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      transition: color 0.3s;
    }
    nav a:hover {
      color: #3498db;
    }

    /* Main Content */
    .main-content {
      max-width: 1200px;
      margin: 2em auto;
      padding: 2em;
      display: flex;
      flex-wrap: wrap;
      gap: 2em;
    }

    .section {
      flex: 1;
      min-width: 300px;
      background: rgba(255, 255, 255, 0.9);
      padding: 1.5em;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.1);
      backdrop-filter: blur(3px);
    }

    .section h2 {
      color: #2c3e50;
      margin-bottom: 1em;
    }

    .btn {
      display: inline-block;
      padding: 0.5em 1em;
      background-color: #3498db;
      color: white;
      text-decoration: none;
      border-radius: 4px;
      transition: background 0.3s;
    }

    .btn:hover {
      background-color: #2980b9;
    }

    /* Footer */
    footer {
      text-align: center;
      padding: 1em;
      background-color: rgba(44, 62, 80, 0.9);
      color: white;
      margin-top: 2em;
    }

    /* Modal */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.6);
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background: white;
      padding: 2em;
      border-radius: 10px;
      max-width: 500px;
      width: 90%;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
      animation: fadeIn 0.3s ease-in-out;
      position: relative;
    }

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

    .close-btn {
      position: absolute;
      top: 15px;
      right: 20px;
      font-size: 24px;
      font-weight: bold;
      color: #333;
      cursor: pointer;
    }

    /* Animations */
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.9); }
      to { opacity: 1; transform: scale(1); }
    }

    /* Responsive */
    @media (max-width: 768px) {
      header {
        height: 300px;
      }
      header h1 {
        font-size: 1.8em;
      }
      header img {
        width: 70%;
      }
      .main-content {
        padding: 1em;
      }
    }

  </style>
</head>
<body>

  <header>
    <img src="{{ asset('assests/Page Name.png') }}" alt="Cemetery Logo">
    <h1>City Public Cemetery</h1>
  </header>

  <nav>
    <ul>
      <li><a href="#">Home</a></li>
      <li><a href="#services">Services</a></li>
      <li><a href="#about">About</a></li>
      <li><a href="#contact">Contact</a></li>
    </ul>
  </nav>

  <div class="main-content">
    <div class="section" id="about">
      <h2>About Us</h2>
      <p>Established to honor and remember, City Public Cemetery provides a peaceful resting place for our community. Learn more about our history and dedication to care.</p>
    </div>

    <div class="section" id="services">
      <h2>Services</h2>
      <p>We offer burial plots, memorials, and guidance for families. Explore our services to find the support you need during difficult times.</p>
      <div style="text-align: center;">
        <a href="#servicesModal" class="btn" id="openServices">View Services</a>
      </div>
    </div>

    <div class="section" id="contact">
      <h2>Contact Us</h2>
      <p>Reach out for inquiries or to schedule a reservation. We’re here to assist you 24/7.</p>
    </div>
  </div>

  <footer>
    <p>&copy; 2025 City Public Cemetery. All rights reserved.</p>
  </footer>

  <!-- Modal -->
  <div id="servicesModal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <h2>Our Services</h2>
      <a href="{{ url('reservation/track') }}">Reservation Number</a>
      <a href="{{ url('reservation') }}">Reservation</a>
      <a href="{{ url('cemetery/' . $encryptedId) }}">View Cemetery</a>
    </div>
  </div>

  <script>
    const modal = document.getElementById("servicesModal");
    const openBtn = document.getElementById("openServices");
    const closeBtn = document.querySelector(".close-btn");

    openBtn.addEventListener("click", e => {
      e.preventDefault();
      modal.style.display = "flex";
    });

    closeBtn.addEventListener("click", () => modal.style.display = "none");

    window.addEventListener("click", e => {
      if (e.target === modal) modal.style.display = "none";
    });
  </script>

</body>
</html>
