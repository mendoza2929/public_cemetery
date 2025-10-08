<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - City Public Cemetery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
        }
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
        .content {
            margin-left: 240px;
            padding: 2rem;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .card h5 {
            font-weight: 600;
            color: #333;
        }
        footer {
            text-align: center;
            color: #777;
            padding: 1em;
            margin-top: 2em;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2><i class="bi bi-tree-fill"></i> Cemetery Admin</h2>
        <a href="{{ url('cemetery/admin') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ url('cemetery/1/map') }}">Plots</a>
        <a href="#"><i class="bi bi-calendar-check"></i> Reservations</a>
        <a href="#"><i class="bi bi-people"></i> Users</a>
        <a href="{{ url('auth/logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>Dashboard Overview</h3>
            <div>Welcome, <strong>Admin</strong></div>
        </div>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5><i class="bi bi-calendar-check text-primary fs-2"></i></h5>
                <h5>Total Reservations</h5>
                <p class="fs-4 fw-bold text-primary">{{ $reservationCount ? : 0 }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5><i class="bi bi-hourglass-split text-warning fs-2"></i></h5>
                <h5>Pending</h5>
                <p class="fs-4 fw-bold text-warning">{{ $pendingCount ? : 0 }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5><i class="bi bi-arrow-repeat text-info fs-2"></i></h5>
                <h5>In Process</h5>
                <p class="fs-4 fw-bold text-info">{{ $inProcessCount ? : 0 }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5><i class="bi bi-check-circle text-success fs-2"></i></h5>
                <h5>Done</h5>
                <p class="fs-4 fw-bold text-success">{{ $doneCount ? : 0 }}</p>
            </div>
        </div>
    </div>

    <div class="card mt-4 p-3">
        <h5 class="mb-3">Reservations by Month ({{ date('Y') }})</h5>
        <canvas id="monthlyChart" height="150"></canvas>
    </div>

        <div class="row g-3 mt-3">
        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5><i class="bi bi-square text-primary fs-2"></i></h5>
                <h5>Total Plots</h5>
                <p class="fs-4 fw-bold text-primary">{{ $totalPlots ? : 0 }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5><i class="bi bi-square text-success fs-2"></i></h5>
                <h5>Available Plots</h5>
                <p class="fs-4 fw-bold text-success">{{ $availablePlots ? : 0 }}</p>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center p-3">
                <h5><i class="bi bi-square-fill text-danger fs-2"></i></h5>
                <h5>Reserved Plots</h5>
                <p class="fs-4 fw-bold text-danger">{{ $reservedPlots ? : 0 }}</p>
            </div>
        </div>
    </div>




    <div class="card mt-4 p-3">
        <h5 class="mb-3">Plots by Status</h5>
        <canvas id="statusChart" height="150"></canvas>
    </div>

    </div>

    <footer>
        &copy; 2025 City Public Cemetery Admin Panel.
    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('monthlyChart').getContext('2d');

    const monthlyChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            datasets: [{
                label: 'Reservations',
                data: [
                    @foreach($monthlyData as $count)
                        {{ $count }},
                    @endforeach
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Reservations: ' + context.raw;
                        }
                    }
                }
            }
        }
    });

   

    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
        type: 'bar',
        data: {
            labels: ['Available', 'Sold', 'Reserved', 'Restricted', 'Quitclaim', 'Sold with Burial'],
            datasets: [{
                label: 'Number of Plots',
                data: [{{ $availablePlots }}, {{ $soldPlots }}, {{ $reservedPlots }}, {{ $restrictedPlots }}, {{ $quitclaimPlots }}, {{ $soldWithBurialPlots }}],
                backgroundColor: ['rgba(75, 192, 192, 0.7)', 'rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
</script>
</html>