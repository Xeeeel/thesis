<?php
session_start();
require_once __DIR__ . '/../config/db_config.php'; // Shared PDO connection
$pdo = db(); // Connect via PDO

// ✅ Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: http://localhost/cartsy/admin/login.php");
    exit();
}

// ✅ Validate and get seller ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid seller ID.");
}
$sellerId = intval($_GET['id']);

try {
    // ✅ Handle approval or rejection
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['seller_status'])) {
        $status = $_POST['seller_status'];

        $stmt = $pdo->prepare("UPDATE users SET seller_status = :status WHERE id = :id");
        $stmt->execute([':status' => $status, ':id' => $sellerId]);

        // Redirect back after updating
        header("Location: http://localhost/cartsy/admin/account_review.php?id={$sellerId}");
        exit();
    }

    // ✅ Fetch seller info
    $stmt = $pdo->prepare("
        SELECT id, email, name, gender, birth_month, birth_day, birth_year,
               phone_number, address, profile_picture, id_front, id_back,
               registered_date, seller_status
        FROM users
        WHERE id = :id
    ");
    $stmt->execute([':id' => $sellerId]);
    $seller = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$seller) {
        die("No seller found.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Seller Account Review</title>
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
            <p><strong>Full Name:</strong> <?= htmlspecialchars($seller['name']) ?></p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($seller['gender']) ?></p>
            <p><strong>Date of Birth:</strong> <?= htmlspecialchars($seller['birth_month'] . ' ' . $seller['birth_day'] . ', ' . $seller['birth_year']) ?></p>
            <p><strong>Location:</strong> <?= htmlspecialchars($seller['address']) ?></p>
          </div>
          <div class="col-md-6">
            <p><strong>Email:</strong> <?= htmlspecialchars($seller['email']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($seller['phone_number']) ?></p>
            <p><strong>Registered On:</strong> <?= date('F j, Y', strtotime($seller['registered_date'])) ?></p>
          </div>
        </div>
      </div>

      <!-- Identity Verification -->
      <div class="mb-4">
        <p><strong>Documents:</strong>
          <?php if (!empty($seller['id_front'])): ?>
            <a href="/cartsy/seller/<?= htmlspecialchars($seller['id_front']) ?>" class="btn btn-sm btn-outline-primary" target="_blank">View Front ID</a>
          <?php endif; ?>
          <?php if (!empty($seller['id_back'])): ?>
            <a href="/cartsy/seller/<?= htmlspecialchars($seller['id_back']) ?>" class="btn btn-sm btn-outline-secondary" target="_blank">View Back ID</a>
          <?php endif; ?>
        </p>
      </div>

      <!-- Seller Status -->
      <div class="mb-4">
        <p><strong>Seller Status:</strong>
          <span class="badge 
            <?= $seller['seller_status'] === 'Approved' ? 'bg-success' :
              ($seller['seller_status'] === 'Rejected' ? 'bg-danger' : 'bg-warning'); ?>">
            <?= htmlspecialchars($seller['seller_status']) ?>
          </span>
        </p>
      </div>

      <!-- Actions -->
      <form method="POST">
        <div class="d-flex justify-content-end gap-2">
          <button type="submit" name="seller_status" value="Approved" class="btn btn-success">✅ Approve</button>
          <button type="submit" name="seller_status" value="Rejected" class="btn btn-danger">❌ Reject</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>
