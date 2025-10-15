<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #f1c27d, #f0a6c1);
            font-family: 'Arial', sans-serif;
        }

        .navbar {
            background-color: #ffffff;  /* White background for navbar */
        }

        .navbar a {
            color: #333 !important;  /* Dark text for contrast */
        }

        .card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .otp-input {
            width: 45px;
            height: 45px;
            text-align: center;
            font-size: 1.5rem;
            margin-right: 10px;
            border-radius: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .otp-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            outline: none;
        }

        .card-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .btn-success {
            background-color: #4CAF50;
            border: none;
            font-size: 1rem;
            font-weight: bold;
            padding: 12px;
            border-radius: 10px;
        }

        .btn-success:hover {
            background-color: #45a049;
        }

        .text-dark {
            color: #333 !important;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .navbar-brand {
            font-family: 'Arial', sans-serif;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar with White Background -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Cartsy</a>
        </div>
    </nav>

    <!-- OTP Form Section -->
    <div class="container card-container">
        <div class="card p-4" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-4">
                <h3 class="text-dark">Enter OTP Code</h3>
                <img src="https://img.icons8.com/ios/50/000000/locked.png" alt="lock" class="mb-3">
            </div>
            <p class="text-center text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            <form action="#" method="POST">
                <div class="d-flex justify-content-between mb-3">
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                    <input type="text" class="form-control otp-input" maxlength="1" required>
                </div>
                <div class="text-center">
                    <a href="#" class="text-dark">Resend Code</a>
                </div>
                <button type="button" class="btn btn-success w-100 mb-3">Send Code</button>
                
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
