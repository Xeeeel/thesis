<?php
// Start session
session_start();

// Database connection
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'cartsy';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetching data for the table (Sellers)
$sql = "SELECT id, name, username, email, registered_date, verification_status FROM users";
$result = $conn->query($sql);
$sellers = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $sellers[] = $row;
    }
}

// Fetching reports (Including product name, buyer name, report reason, and additional details)
$sql_reports = "SELECT br.report_id, br.report_reason, u.name AS buyer_name, p.product_name, br.other_description, br.created_at 
                FROM reports br 
                JOIN users u ON br.buyer_id = u.id
                JOIN products p ON br.product_id = p.product_id";
$result_reports = $conn->query($sql_reports);
$reports = [];

if ($result_reports->num_rows > 0) {
    while ($row = $result_reports->fetch_assoc()) {
        $reports[] = $row;
    }
}

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

    .table td,
    .table th {
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

    <a href="admin.php">
      🛍️ Sellers Product Approval
    </a>
    <a href="account.php">
      👤 Sellers Account Approval
    </a>
    <a href="account.php" class="active">
      ❗ Customer Report
    </a>
    <!-- Logout link at the bottom -->
    <a href="login.php" class="logout-icon d-flex align-items-center text-danger">
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
        <h3>Buyer Reports</h3>
    </div>

    <div class="card p-4">
        <label for="statusFilter" class="form-label">Filter by Report Reason:</label>
        <select id="statusFilter" class="form-select mb-3" style="max-width: 200px;">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
        </select>

        <!-- Search Bar -->
        <label for="searchBar" class="form-label">Search Reports:</label>
        <input type="text" id="searchBar" class="form-control mb-3" placeholder="Search by Buyer Name, Product, or Report Reason">

        <!-- Scrollable Table -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered bg-white">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Buyer Name</th>
                        <th>Product Name</th>
                        <th>Report Reason</th>
                        <th>Additional Details</th>
                        <th>Date Reported</th>
                    </tr>
                </thead>
                <tbody id="reportTable">
                    <?php foreach ($reports as $index => $report): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($report['buyer_name']); ?></td>
                            <td><?php echo htmlspecialchars($report['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($report['report_reason']); ?></td>
                            <td><?php echo htmlspecialchars($report['other_description']); ?></td>
                            <td><?php echo date('F j, Y', strtotime($report['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
  </div>

  <!-- JavaScript for Filtering and Searching -->
  <script>
    const searchInput = document.getElementById('searchBar');
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('#reportTable tr');

    function filterTable() {
      const searchTerm = searchInput.value.toLowerCase();
      const status = statusFilter.value;

      tableRows.forEach(row => {
        const buyerName = row.cells[1].textContent.toLowerCase();
        const productName = row.cells[2].textContent.toLowerCase();
        const reportReason = row.cells[3].textContent.toLowerCase();
        const additionalDetails = row.cells[4].textContent.toLowerCase();

        const matchesSearch = buyerName.includes(searchTerm) || productName.includes(searchTerm) || reportReason.includes(searchTerm) || additionalDetails.includes(searchTerm);
        row.style.display = matchesSearch ? '' : 'none';
      });
    }

    searchInput.addEventListener('input', filterTable);
    statusFilter.addEventListener('change', filterTable);
  </script>

</body>
</html>
