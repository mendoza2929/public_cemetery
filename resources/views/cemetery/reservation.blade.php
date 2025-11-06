<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - City Public Cemetery</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('assests/Logo.png') }}">

    <link rel="icon" href="{{ asset('assests/Logo.png') }}">

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
        <h2><img src="{{ asset('assests/Logo.png') }}" alt="Gcash QR Code" style="max-width:200px; width:25%; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.2);"></h2>
        <a href="{{ url('cemetery/admin') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ url('cemetery/1/map') }}">Plots</a>
        <a href="{{ url('cemetery/reservation') }}"><i class="bi bi-calendar-check"></i> Reservations</a>
        <a href="{{ url('auth/logout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>

    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3>List of Reservations</h3>
            <div>Welcome, <strong>Admin</strong></div>
        </div>

       <div class="card p-3">
            <table id="reservationTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Reservation No</th>
                        <th>Name</th>
                        <th>Name of Deceased</th>
                        <th>Relationship</th>
                        <th>Address</th>
                        <th>Contact Number</th>
                        <th>Date Reserved</th>
                        <th>Payment Method</th>
                        <th>Plot Number</th>
                        <th>Plot Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>


    <footer>
        &copy; 2025 City Public Cemetery Admin Panel.
    </footer>
</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
 $(document).ready(function() {
    $('#reservationTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url('reservation/fetch') }}", 
        columns: [
            { data: 'reservation_no', name: 'reservation_no' },
            { data: 'name', name: 'name' },
            { data: 'name_deceased', name: 'name_deceased' },
            { data: 'relationship', name: 'relationship' },
            { data: 'address', name: 'address' },
            { data: 'number', name: 'number' },
            { data: 'date', name: 'date' },
            { data: 'payment_method', name: 'payment_method' },
            { data: 'plot_number', name: 'plot_number' },
            { data: 'plot_price', name: 'plot_price' },
            {
                data: 'status',
                name: 'status',
                render: function (data, type, row) {
                    const options = [
                        { value: 'pending', text: 'Pending' },
                        { value: 'in_process', text: 'For Review' },
                        { value: 'done', text: 'Done' }
                    ];

                    let html = `<select class="form-select form-select-sm status-select" data-id="${row.id}">`;
                    options.forEach(opt => {
                        html += `<option value="${opt.value}" ${data === opt.value ? 'selected' : ''}>${opt.text}</option>`;
                    });
                    html += `</select>`;
                    return html;
                }
            }
        ]
    });

    $(document).on('change', '.status-select', function() {
        var id = $(this).data('id');
        var status = $(this).val();

        $.ajax({
            url: "{{ url('reservation/update-status') }}",
            type: "POST",
            data: {
                id: id,
                status: status,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                console.log('Status updated:', response);
            },
            error: function(xhr) {
                alert('Error updating status');
            }
        });
    });


});
 
</script>
</html>