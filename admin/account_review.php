<?php
// Start session
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'cartsy';

$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch seller data from the database (you can modify this query as per your table structure)
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Invalid seller ID.";
    exit();
}
$sellerId = intval($_GET['id']);

// Handle approval or rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $verification_status = $_POST['verification_status']; // Get the status from the form

    // Update verification status in the database
    $sql = "UPDATE users SET verification_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $verification_status, $sellerId);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page after processing the request
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $sellerId);
    exit();
}

// Fetch seller data
$sql = "SELECT id, email, username, name, gender, birth_month, birth_day, birth_year, phone_number, address, profile_picture, id_front, id_back, registered_date, verification_status 
        FROM users 
        WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $sellerId);
$stmt->execute();
$result = $stmt->get_result();

// Check if seller data exists
if ($result->num_rows > 0) {
    // Fetch seller's data
    $seller = $result->fetch_assoc();
} else {
    echo "No seller found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Account Approval</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f9f9f9;
    }

    .card {
      border-radius: 1rem;
    }

    hr {
      border-top: 1px solid #dee2e6;
    }

    textarea {
      resize: none;
    }
  </style>
</head>
<body>
  <div class="container my-5">
    <div class="card shadow-lg p-4">
      <h3 class="mb-4 text-primary">🧾 Seller Account Review</h3>

      <!-- Basic Info -->
      <div class="mb-4">
        <h5 class="text-secondary">Profile Information</h5>
        <hr />
        <div class="row">
          <div class="col-md-6">
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($seller['name']); ?></p>
            <p><strong>Gender:</strong> <?php echo htmlspecialchars($seller['gender']); ?></p> <!-- Display Gender -->
            <p><strong>Date of Birth:</strong> <?php echo $seller['birth_month'] . ' ' . $seller['birth_day'] . ', ' . $seller['birth_year']; ?></p> <!-- Display Date of Birth -->
            <p><strong>Location:</strong> <?php echo htmlspecialchars($seller['address']); ?></p> <!-- Display Location -->
          </div>
          <div class="col-md-6">
            <p><strong>Username:</strong> @<?php echo htmlspecialchars($seller['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($seller['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($seller['phone_number']); ?></p>
            <p><strong>Registered On:</strong> <?php echo date('F j, Y', strtotime($seller['registered_date'])); ?></p>
          </div>
        </div>
      </div>

      <!-- Identity Verification -->
      <div class="mb-4">
        <p><strong>Documents:</strong>
          <?php if ($seller['id_front']): ?>
            <a href="/cartsy/seller/<?php echo htmlspecialchars($seller['id_front']); ?>" class="btn btn-sm btn-outline-primary">View Front ID</a>
          <?php endif; ?>
          <?php if ($seller['id_back']): ?>
            <a href="/cartsy/seller/<?php echo htmlspecialchars($seller['id_back']); ?>" class="btn btn-sm btn-outline-secondary">View Back ID</a>
          <?php endif; ?>
        </p>
      </div>

      <!-- Verification Status -->
      <div class="mb-4">
        <p><strong>Verification Status:</strong>
          <span class="badge <?php echo ($seller['verification_status'] === 'Approved') ? 'bg-success' : (($seller['verification_status'] === 'Rejected') ? 'bg-danger' : 'bg-warning'); ?>">
            <?php echo htmlspecialchars($seller['verification_status']); ?>
          </span>
        </p>
      </div>

      <!-- Actions -->
      <form method="POST">
        <div class="d-flex justify-content-end gap-2">
          <!-- Approve button -->
          <button type="submit" name="verification_status" value="Approved" class="btn btn-success">✅ Approve</button>
          <!-- Reject button -->
          <button type="submit" name="verification_status" value="Rejected" class="btn btn-danger">❌ Reject</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
