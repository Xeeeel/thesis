<?php
session_start();
require_once __DIR__ . '/../config/db_config.php';
$pdo = db();

// Optional: ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: http://localhost/cartsy/admin/login.php");
    exit();
}

// Fetch sellers data using PDO
try {
    $stmt = $pdo->query("SELECT id, name, email, registered_date, seller_status FROM users");
    $sellers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database query failed: " . $e->getMessage());
}
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
      background-color: #f8f9fa;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .sidebar {
      height: 100vh;
      background-color: #ffffff;
      border-right: 1px solid #dee2e6;
      padding-top: 1rem;
      position: fixed;
      width: 260px;
      transition: all 0.3s ease-in-out;
    }

    .sidebar a {
      display: flex;
      align-items: center;
      padding: 12px 20px;
      color: #495057;
      text-decoration: none;
      font-weight: 500;
      border-radius: 10px;
      margin: 5px 10px;
      transition: background-color 0.2s ease-in-out;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #e9ecef;
      color: #0d6efd;
    }

    .main-content {
      margin-left: 260px;
      padding: 2rem;
    }

    .header h3 {
      color: #0d6efd;
      font-weight: 600;
    }

    .card {
      border-radius: 10px;
      box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
      border: none;
    }

    .badge-pending {
      background-color: #fff3cd;
      color: #856404;
    }

    .badge-approved {
      background-color: #d1ecf1;
      color: #0c5460;
    }

    .badge-rejected {
      background-color: #f8d7da;
      color: #721c24;
    }

    .table th {
      background-color: #f1f1f1;
      color: #333;
    }

    .table-responsive {
      max-height: 300px;
      overflow-y: auto;
    }

    .table td, .table th {
      vertical-align: middle;
    }

    .navbar {
      background-color: #ffffff;
      border-bottom: 1px solid #dee2e6;
      padding: 1rem 2rem;
    }

    .navbar-brand {
      color: #343a40;
      font-family: "Suranna", serif;
      font-size: 30px;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar sticky-top navbar-light">
    <div class="container-fluid">
      <a class="navbar-brand fs-3" href="#">Cartsy</a>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar d-flex flex-column">
    <h4 class="sidebar-title px-4 mb-3 fs-4 text-dark">
      Admin Dashboard
    </h4>

    <a href="admin.php" class="active">
      🛍️ Sellers Account Approval
    </a>
    <a href="account.php">
      👤 Sellers Product Approval
    </a>
    <a href="report.php">
      ❗ Customer Report
    </a>
    <!-- Logout link at the bottom -->
    <a href="logout.php" class="logout-icon d-flex align-items-center text-danger">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box-arrow-left me-2" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M10 15a1 1 0 0 0 1-1v-2h-1v2H2V2h8v2h1V2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8z"/>
        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
      </svg>
      Logout
    </a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <div class="header mb-4">
      <h3>Sellers Account Approval</h3>
    </div>

    <div class="card p-4">
      <label for="statusFilter" class="form-label">Filter by Status:</label>
      <select id="statusFilter" class="form-select mb-3" style="max-width: 200px;">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
      </select>

      <!-- Search Bar -->
      <label for="searchBar" class="form-label">Search Seller:</label>
      <input type="text" id="searchBar" class="form-control mb-3" placeholder="Search by Seller Name, or Email">

      <!-- Scrollable Table -->
      <div class="table-responsive">
        <table class="table table-hover table-bordered bg-white">
          <thead class="table-light">
            <tr>
              <th>#</th>
              <th>Seller Name</th>
              <th>Email</th>
              <th>Registration Date</th>
              <th>Verification Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody id="sellerTable">
            <?php foreach ($sellers as $index => $seller): ?>
              <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($seller['name']) ?></td>
                <td><?= htmlspecialchars($seller['email']) ?></td>
                <td><?= htmlspecialchars(date('F j, Y', strtotime($seller['registered_date']))) ?></td>
                <td>
                  <span class="badge 
                    <?= $seller['seller_status'] === 'approved' ? 'badge-approved' : 
                    ($seller['seller_status'] === 'rejected' ? 'badge-rejected' : 'badge-pending'); ?>">
                    <?= ucfirst($seller['seller_status']) ?>
                  </span>
                </td>
                <td>
                  <a href="account_review.php?id=<?= $seller['id'] ?>" class="btn btn-sm btn-outline-primary">View</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const searchInput = document.getElementById('searchBar');
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('#sellerTable tr');

    function filterTable() {
      const searchTerm = searchInput.value.toLowerCase();
      const status = statusFilter.value;

      tableRows.forEach(row => {
        const name = row.cells[1].textContent.toLowerCase();
        const username = row.cells[2].textContent.toLowerCase();
        const email = row.cells[3].textContent.toLowerCase();
        const badge = row.cells[5].textContent.toLowerCase();

        const matchesSearch = name.includes(searchTerm) || username.includes(searchTerm) || email.includes(searchTerm);
        const matchesStatus = !status || badge.includes(status);

        row.style.display = matchesSearch && matchesStatus ? '' : 'none';
      });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
  </script>
</body>
</html>
