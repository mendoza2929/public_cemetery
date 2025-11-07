<!DOCTYPE html>
<html>
<head>
    <title>Reservation Certificate</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            text-align: center;
            padding: 40px;
        }
        .certificate {
            border: 5px double #000;
            padding: 30px;
        }
        h1 {
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
        }
        .details {
            margin-top: 30px;
            font-size: 16px;
            text-align: left;
            margin-left: 100px;
        }
        .signature {
            margin-top: 60px;
            text-align: right;
            margin-right: 80px;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <h1>BISLIG CITY PUBLIC CEMETERY</h1>
        <p>This is to certify that the following reservation has been officially recorded.</p>

        <div class="details">
            <p><strong>Reservation No:</strong> {{ $reservation->reservation_no }}</p>
            <p><strong>Name:</strong> {{ $reservation->name }}</p>
            <p><strong>Deceased:</strong> {{ $reservation->name_deceased }}</p>
            <p><strong>Plot Number:</strong> {{ $reservation->plot_number }}</p>
            <p><strong>Date:</strong> {{ date('F d, Y', strtotime($reservation->date)) }}</p>
            <p><strong>Payment Method:</strong> {{ $reservation->payment_method }} </p>
            <p><strong>Status:</strong> {{ ucfirst($reservation->status) }}</p>
        </div>

        <div class="signature">
            <p>__________________________</p>
            <p><strong>Authorized Signature</strong></p>
        </div>
    </div>
</body>
</html>
