<?php
session_start(); // Start session to access $_SESSION["id"]

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $first_name = $_POST['first_name'];
  $middle_name = $_POST['middle_name'];
  $last_name = $_POST['last_name'];
  $gender = $_POST['gender'];
  $birth_month = $_POST['birth_month'];
  $birth_day = $_POST['birth_day'];
  $birth_year = $_POST['birth_year'];
  $phone_number = $_POST['phone_number'];
  $address = $_POST['address'];

  // Combine full name
  $full_name = trim("$first_name $middle_name $last_name");

  // Connect to database
  $conn = new mysqli('localhost', 'root', '', 'cartsy');
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Make sure session ID is available
  if (isset($_SESSION["id"])) {
    $user_id = $_SESSION["id"];

    date_default_timezone_set('Asia/Manila'); // Use your desired time zone
    $registered_date = date('Y-m-d H:i:s');


    // Update user info in the database
    $stmt = $conn->prepare("UPDATE users SET name=?, gender=?, birth_month=?, birth_day=?, birth_year=?, phone_number=?, address=?, registered_date=? WHERE id=?");
    $stmt->bind_param("sssissssi", $full_name, $gender, $birth_month, $birth_day, $birth_year, $phone_number, $address, $registered_date, $user_id);

    if ($stmt->execute()) {
      $stmt->close();
      $conn->close();
      // Redirect to next verification page
      header("Location: verification-1.php");
      exit();
    } else {
      $error = "Error saving data: " . $stmt->error;
      $stmt->close();
    }
  } else {
    $error = "User not logged in.";
  }

  $conn->close();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Verification Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #140026;
      font-family: Arial, sans-serif;
    }
    .card {
      background-color: #aba7ad;
      border-radius: 10px;
      padding: 2rem;
    }
    .form-control, .form-select {
      background-color: #d7d7d7;
      border: none;
      border-radius: 5px;
    }
    .form-control:focus, .form-select:focus {
      box-shadow: none;
      background-color: #d7d7d7;
    }
    .btn-continue {
      background-color: #e6c065;
      border: none;
      border-radius: 5px;
      padding: 10px 40px;
      float: right;
      color: black;
      font-weight: 500;
    }
    .logo {
      font-size: 24px;
      font-weight: 600;
      padding: 1rem 2rem;
    }
  </style>
</head>
<body>

  <div class="logo bg-white">Cartsy</div>

  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card col-md-8 shadow">
      <h3 class="mb-4">Verification</h3>
      <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
      <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
      <form method="POST">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">First Name</label>
            <input type="text" class="form-control" name="first_name" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Gender</label>
            <div class="d-flex align-items-center gap-3 mt-2">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="Female" id="female" required />
                <label class="form-check-label" for="female">Female</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" value="Male" id="male" required />
                <label class="form-check-label" for="male">Male</label>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label">Middle Name</label>
            <input type="text" class="form-control" name="middle_name" />
          </div>
          <div class="col-md-6">
            <label class="form-label">Phone Number</label>
            <input type="text" class="form-control" name="phone_number" required />
          </div>

          <div class="col-md-6">
            <label class="form-label">Last Name</label>
            <input type="text" class="form-control" name="last_name" required />
          </div>
          <div class="col-md-6">
            <label class="form-label">Date Of Birth</label>
            <div class="d-flex gap-2">
              <select class="form-select" name="birth_month" required>
                <?php
                  $months = ['January', 'February', 'March', 'April', 'May', 'June',
                            'July', 'August', 'September', 'October', 'November', 'December'];
                  foreach ($months as $month) {
                    echo "<option value='$month'>$month</option>";
                  }
                ?>
              </select>
              <select class="form-select" name="birth_day" required>
                <?php for ($i = 1; $i <= 31; $i++) echo "<option value='$i'>$i</option>"; ?>
              </select>
              <select class="form-select" name="birth_year" required>
                <?php for ($i = date("Y"); $i >= 1900; $i--) echo "<option value='$i'>$i</option>"; ?>
              </select>
            </div>
          </div>

          <div class="col-md-12">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" name="address" required />
          </div>
        </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-continue">CONTINUE</button>
        </div>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
