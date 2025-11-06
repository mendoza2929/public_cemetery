<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - City Public Cemetery</title>
    <link rel="icon" href="{{ asset('assests/Logo.png') }}">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(180deg, #2c3e50, #1a252f);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            color: #fff;
            margin: 0;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border-radius: 15px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            text-align: center;
        }

        .login-container h2 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #fff;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.1);
            border: none;
            color: #fff;
            border-radius: 8px;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            outline: none;
            box-shadow: 0 0 0 2px #3498db;
        }

        .btn-login {
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            width: 100%;
            padding: 0.8rem;
            font-size: 1rem;
            font-weight: bold;
            transition: background 0.3s;
        }

        .btn-login:hover {
            background-color: #2980b9;
        }

        .footer-text {
            font-size: 0.85rem;
            color: #ccc;
            margin-top: 1.5rem;
        }

        .brand-header {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .brand-header i {
            font-size: 2rem;
            color: #3498db;
        }

        .error-message {
            background-color: rgba(231, 76, 60, 0.2);
            color: #ffcccc;
            padding: 0.6rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 1.5rem;
                width: 90%;
            }
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="brand-header">
            <i class="bi bi-tree-fill"></i>
            <h2>Login</h2>
        </div>
        @if (session('error'))
            <div class="error-message">{{ session('error') }}</div>
        @endif

       <form class="form-horizontal" role="form" method="POST" action="{{ url('/auth/login') }}">
             <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="username" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <p class="footer-text mt-4">&copy; 2025 City Public Cemetery</p>
    </div>

</body>
</html>
