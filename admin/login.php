<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cartsy Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css" />
  <style>
    body, html {
      margin: 0;
      padding: 0;
    }

    .main-bg {
      background: linear-gradient(to bottom, #c58900, #7a5600);
    }

    .cartsy-box {
      border: 5px solid white;
      padding: 40px 60px;
    }

    .cartsy-text {
      color: white;
      font-size: 3rem;
      font-family: 'Georgia', serif;
    }

    .login-box {
      background: rgba(255, 255, 255, 0.05);
      width: 100%;
      max-width: 400px;
    }
  </style>
</head>
<body>
  <div class="container-fluid main-bg vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 h-100">
      <!-- Left Side -->
      <div class="col-md-6 d-flex align-items-center justify-content-center border-end">
        <div class="cartsy-box text-center">
          <h1 class="cartsy-text" style="width: 400px; font-size: 70px;">Cartsy</h1>
        </div>
      </div>

      <!-- Right Side -->
      <div class="col-md-6 d-flex align-items-center justify-content-center">
        <div class="login-box shadow rounded p-4">
          <h4 class="text-center fw-bold mb-4 text-white">WELCOME</h4>
          <!-- Form Submission to login.php -->
          <form method="POST" action="submit_login.php">
            <div class="mb-3">
              <label for="username" class="form-label text-white">Username</label>
              <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required />
            </div>
            <div class="mb-3">
              <label for="password" class="form-label text-white">Password</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required />
            </div>
            <button type="submit" class="btn btn-dark w-100 mt-3">LOGIN</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
