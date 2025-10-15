<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cartsy Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #d2a754;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            text-align: center;
            position: relative;
        }

        .login-container h2 {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-control {
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            height: 45px;
            border-radius: 8px;
        }

        .login-btn {
            background-color: #000;
            color: white;
            border-radius: 8px;
            width: 100%;
            padding: 10px;
            font-size: 18px;
            border: none;
        }

        .forgot-password, .signup-link {
            color: red;
            font-size: 14px;
            display: block;
            margin: 10px 0;
        }

        .social-login {
            margin-top: 20px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icons i {
            font-size: 32px;
            color: #fff;
            width: 50px;
            height: 50px;
            background-color: #3b5998;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .google {
            background-color: #db4437;
        }

        .background-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 55%;
            border-top-right-radius: 250px 250px;
            border-bottom-right-radius: 250px 250px;
            height: 100%;
            background-image: url('./image/banner.webp');
            background-size: cover;
            background-position: center;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="background-image"></div>
    <div class="login-container">
        <h2>Login</h2>
        <form>
            <input type="text" class="form-control" placeholder="Username" required>
            <input type="password" class="form-control" placeholder="Password" required>
            <a href="#" class="forgot-password">Forgot Password?</a>
            <button type="submit" class="btn login-btn">LOGIN</button>
            <p class="signup-link">No Account??? <a href="#">Sign Up Here</a></p>
        </form>
    </div>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>